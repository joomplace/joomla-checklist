<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

/**
 * Checklist Component Controller
 */
class ChecklistController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = array())
        {
        	$view = JFactory::getApplication()->input->getCmd('view', 'dashboard');
            JFactory::getApplication()->input->set('view', $view);
            parent::display($cachable);
        }

        function latestVersion()
        {
        	require_once(JPATH_BASE.'/components/com_checklist/assets/Snoopy.class.php' );
			$jq_version = ChecklistHelper::getVersion();
			$s = new Snoopy();
			$s->read_timeout = 90;
			$s->referer = JURI::root();
			@$s->fetch('http://www.joomplace.com/version_check/componentVersionCheck.php?component=checklist&current_version='.urlencode($jq_version));
			$version_info = $s->results;
			$version_info_pos = strpos($version_info, ":");
			if ($version_info_pos === false) {
				$version = $version_info;
				$info = null;
			} else {
				$version = substr( $version_info, 0, $version_info_pos );
				$info = substr( $version_info, $version_info_pos + 1 );
			}
			if($s->error || $s->status != 200){
		    	echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		    } else if($version == $jq_version){
		    	echo '<font color="green">' . $version . '</font>' . $info;
		    } else {
		    	echo '<font color="red">' . $version . '</font>&nbsp;<a href="http://www.joomplace.com/members-area.html" target="_blank">(Upgrade to the latest version)</a>' ;
		    }
		    exit();
        }

        public function latestNews()
        {
        	require_once(JPATH_BASE.'/components/com_checklist/assets/Snoopy.class.php' );

			$s = new Snoopy();
			$s->read_timeout = 10;
			$s->referer = JURI::root();
			@$s->fetch('http://www.joomplace.com/news_check/componentNewsCheck.php?component=checklist');
			$news_info = $s->results;

			if($s->error || $s->status != 200){
		    	echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		    } else {
			echo $news_info;
		    }
		    exit();
        }
}