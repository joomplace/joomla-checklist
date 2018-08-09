<?php defined('_JEXEC') or die('Restricted access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class LightchecklistControllerGeneral extends JControllerLegacy
{
	//----------------------------------------------------------------------------------------------------
	public function get_latest_component_version()
	{
		require_once(JPATH_SITE."/administrator/components/com_lightchecklist/libs/Snoopy.php");
		require_once(JPATH_SITE."/administrator/components/com_lightchecklist/libs/MethodsForXml.php");
		
		$configurationModel = JModelLegacy::getInstance("Configuration", 'LightchecklistModel');
		$config = $configurationModel->getConfig();

		// Making request.
		
		$snoopy = new Snoopy();
		$snoopy->read_timeout = 90;
		$snoopy->referer = JURI::root();
		@$snoopy->fetch("http://www.joomplace.com/version_check/componentVersionCheck.php?component=checklist&current_version=".urlencode($config->component_version));
		
		$error = $snoopy->error;
		$status = $snoopy->status;
		
		$versionInfo = $snoopy->results;
		$versionInfoPos = strpos($versionInfo, ":");
		
		if ($versionInfoPos === false)
		{
			$version = $versionInfo;
			$info = "";
		}
		else
		{
			$version = substr($versionInfo, 0, $versionInfoPos);
			$info = substr($versionInfo, $versionInfoPos + 1);
		}
		
		// Returning data.
		
		@ob_clean();
		header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: text/xml; charset=utf-8');
		
		$xml = array();
		
		$xml[] = "<\x3fxml version=\"1.0\" encoding=\"UTF-8\"\x3f>";
		$xml[] = '<root>';
		$xml[] = 	'<error>' . MethodsForXml::XmlEncode($error) . '</error>';
		$xml[] = 	'<status>' . MethodsForXml::XmlEncode($status) . '</status>';
		$xml[] = 	'<version>' . MethodsForXml::XmlEncode($version) . '</version>';
		$xml[] = 	'<info>' . MethodsForXml::XmlEncode($info) . '</info>';
		$xml[] = '</root>';
		
		print(implode("", $xml));
		
		jexit();
	}
	//----------------------------------------------------------------------------------------------------
	public function get_latest_news()
	{
		require_once(JPATH_SITE."/administrator/components/com_lightchecklist/libs/Snoopy.php");
		require_once(JPATH_SITE."/administrator/components/com_lightchecklist/libs/MethodsForXml.php");
		
		// Making request.
		
		$snoopy = new Snoopy();
		$snoopy->read_timeout = 10;
		$snoopy->referer = JURI::root();
		@$snoopy->fetch("http://www.joomplace.com/news_check/componentNewsCheck.php?component=checklist");
		
		$error = $snoopy->error;
		$status = $snoopy->status;
		
		$content = $snoopy->results;
		
		// Returning data.
		
		@ob_clean();
		header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: text/xml; charset=utf-8');
		
		$xml = array();
		
		$xml[] = "<\x3fxml version=\"1.0\" encoding=\"UTF-8\"\x3f>";
		$xml[] = '<root>';
		$xml[] = 	'<error>' . MethodsForXml::XmlEncode($error) . '</error>';
		$xml[] = 	'<status>' . MethodsForXml::XmlEncode($status) . '</status>';
		$xml[] = 	'<content>' . MethodsForXml::XmlEncode($content) . '</content>';
		$xml[] = '</root>';
		
		print(implode("", $xml));
		
		jexit();
	}
	//----------------------------------------------------------------------------------------------------
	public function show_changelog()
	{
		@ob_clean;
		header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: text/html; charset=utf-8');

        $msg = JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CANNOT_LOAD_CHANGELOG');
        if(function_exists('file_get_contents')) {
            $html = file_get_contents('https://www.joomplace.com/joomla-components/checklist-extension.html');
            if($html) {
                $ch = preg_match("/id=\"change\"[^>]+>(((?!div>)[\s\S])*)<\/div>/", $html, $m);
                if($ch && !empty($m[1])) {
                    $msg = $m[1];
                }
            }
        }
        echo $msg;
        jexit();
    }
}