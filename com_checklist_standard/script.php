<?php
/**
 * Checklist component for Joomla
 * @version $Id: install.checklist.php 2014-06-03 17:30:15
 * @package checklist
 * @subpackage install.checklist.php
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// Don't allow access
defined('_JEXEC') or die('Restricted access');

class com_checklistInstallerScript
{

    function install()
    {
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        // $this->_extract();

        $adminDir = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_checklist';
        if (!JFolder::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist')) {
            JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist');
        }

        if (!JFolder::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist' . DIRECTORY_SEPARATOR . 'avatar')) {
            JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist' . DIRECTORY_SEPARATOR . 'avatar');
        }

        if (!JFolder::exists(JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist')) {
            JFolder::create(JPATH_ROOT . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist');
        }

        if (!JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'preferences_contact_list.png')) {
            JFile::copy($adminDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'preferences_contact_list.png', JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'preferences_contact_list.png');
        }

        if (!JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'rss_tag.png')) {
            JFile::copy($adminDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'rss_tag.png', JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'rss_tag.png');
        }

        if (!JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'settings.png')) {
            JFile::copy($adminDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'settings.png', JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'settings.png');
        }

        if (!JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'to_do_list_checked3.png')) {
            JFile::copy($adminDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'to_do_list_checked3.png', JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'to_do_list_checked3.png');
        }

        if (!JFile::exists(JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'users_2.png')) {
            JFile::copy($adminDir . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'users_2.png', JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'users_2.png');
        }

        JFile::copy($adminDir . DIRECTORY_SEPARATOR . "index.html", JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'index.html');
        JFile::copy($adminDir . DIRECTORY_SEPARATOR . "index.html", JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist' . DIRECTORY_SEPARATOR . 'index.html');
        JFile::copy($adminDir . DIRECTORY_SEPARATOR . "index.html", JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'checklist' . DIRECTORY_SEPARATOR . 'avatar' . DIRECTORY_SEPARATOR . 'index.html');

        $this->_installDatabase();
    }

    function uninstall($parent)
    {
        echo '<p>' . JText::_('COM_CHECKLIST_UNINSTALL_TEXT') . '</p>';
    }

    function update($parent)
    {
        // $this->_extract();
    }

    public function preflight($type, $parent)
    {
        $xml = JFactory::getXML(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' .
            DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'checklist.xml');
        $checklist_old_version = (string)$xml->version;

        if (version_compare('1.1.2', $checklist_old_version) > 0)
        {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $fields = array(
                $db->qn('list_access') . '=' . $db->q(1),
                $db->qn('comment_access') . '=' . $db->q(1)
            );
            $conditions = array();
            $query->update($db->qn('#__checklist_lists'))
                ->set($fields)
                ->where($conditions);
            $db->setQuery($query)
                ->execute();
        }
    }

    function postflight($type, $parent)
    {
        $xml = JFactory::getXML(JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' .
            DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'checklist.xml');
        $checklist_installed_version = (string)$xml->version;

        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
        $chk_this_version = $checklist_installed_version;
        $curr_date = date("Y-m-d", strtotime("-2 months"));

        $configDefaultParams = array(
            'component_version' => $chk_this_version,
            'social_google_plus_use' => 1,
            'social_google_plus_size' =>'medium',
            'social_google_plus_annotation' => 'inline',
            'social_google_plus_language' => 'en-US',
            'social_twitter_use' => 1,
            'social_twitter_size' => 'standart',
            'social_twitter_annotation' => 'horizontal',
            'social_twitter_language' => 'en',
            'social_linkedin_use' => 0,
            'social_linkedin_annotation' => 'none',
            'social_facebook_use' => 1,
            'social_facebook_verb' => 'like',
            'social_facebook_layout' => 'button_count',
            'social_facebook_font' => 'tahoma',
            'useDisqus' => 0,
            'disqusSubDomain' => '',
            'fbadmin' => '',
            'fbappid' => '',
            'moderateChecklist' => 1,
            'useNotification' => 1,
            'emailsNotification' => ''
        );

        $db->setQuery("SELECT COUNT(*) FROM `#__checklist_config`");
        if (!$db->loadResult()) {
            $query = 'INSERT INTO `#__checklist_config` (`setting_name`, `setting_value`) VALUES ';
            foreach($configDefaultParams as $p => $value) {
                $query.="('".$p."', '".$value."'),";
            }
			$db->SetQuery(substr($query, 0, -1));

            $db->execute();
        } else {
            // check all config params
            $db->setQuery("SELECT setting_name FROM `#__checklist_config`");
            $dbsettings = $db->loadColumn();
            $query = 'INSERT INTO `#__checklist_config` (`setting_name`, `setting_value`) VALUES ';
            $count = 0;
            foreach($configDefaultParams as $p => $value) {
                if(!in_array($p, $dbsettings)) {
                    $count++;
                    $query.="('".$p."', '".$value."'),";
                } elseif($p=='component_version') {
                    $db->setQuery("UPDATE `#__checklist_config` SET `setting_value` = '".$chk_this_version."' WHERE `setting_name` = 'component_version'");
                    $db->execute();
                }
            }
            if($count) {
                $db->SetQuery(substr($query, 0, -1));            
                $db->execute(); 
            }

        }

        $db->setQuery("SELECT COUNT(id) FROM `#__checklist_dashboard_items`");
        if (!$db->loadResult()) {
            $db->setQuery("INSERT INTO `#__checklist_dashboard_items` (`id`, `title`, `url`, `icon`, `published`) VALUES (1, 'User checklists', 'index.php?option=com_checklist&view=lists', 'media/com_checklist/preferences_contact_list.png', 1), (2, 'Available checklists', 'index.php?option=com_checklist&view=lists&defaultlist=1', 'media/com_checklist/to_do_list_checked3.png', 1), (3, 'User list', 'index.php?option=com_checklist&view=users', 'media/com_checklist/users_2.png', 1), (4, 'Configuration', 'index.php?option=com_checklist&view=configuration', 'media/com_checklist/settings.png', 1), (5, 'Tags', 'index.php?option=com_checklist&view=tags', 'media/com_checklist/rss_tag.png', 1);");
            $db->execute();
        }

        $newColumns = array(
            'lists' => array(
                'language' => "CHAR(7) NOT NULL",
                'template' => "INT(18) NOT NULL"
            )
        );

        foreach ($newColumns as $table => $fields) {
            $oldColumns = $db->getTableColumns('#__checklist_' . $table);

            foreach ($fields as $key => $value) {
                if (empty($oldColumns[$key])) {
                    $db->setQuery('ALTER TABLE `#__checklist_' . $table . '` ADD `' . $key . '` ' . $value);
                    $db->execute();
                }
            }
        }

        $db->setQuery("CREATE TABLE IF NOT EXISTS `#__checklist_templates` ( `id` int(18) unsigned NOT NULL AUTO_INCREMENT, `name` varchar(100) NOT NULL, PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8");
        $db->execute();

        $db->setQuery("TRUNCATE TABLE `#__checklist_templates`");
        $db->execute();

        $db->setQuery("INSERT INTO `#__checklist_templates` (`id`, `name`) VALUES ('', 'Joomplace Style'), ('', 'Default Template');");
        $db->query();

        // $app->redirect(JURI::root().'administrator/index.php?option=com_checklist&task=install.plugins');
    }

    function _extract()
    {

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.archive');

        // Install frontend
        $source = JPATH_SITE . '/components/com_checklist/frontend.zip';
        $destination = JPATH_SITE . '/components/com_checklist/';
        if (!JFolder::exists($destination)) {
            JFolder::create($destination);
        }

        if (!JArchive::extract($source, $destination)) {
            // If frontend did not extract
            return false;
        }

        // Copy site language file
        JFile::copy(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.ini', JPATH_SITE . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.ini');

        //Delete frontend archive
        JFile::delete(JPATH_SITE . '/components/com_checklist/frontend.zip');

        // Install backend
        $source = JPATH_SITE . '/administrator/components/com_checklist/backend.zip';
        $destination = JPATH_SITE . '/administrator/components/com_checklist/';
        if (!JFolder::exists($destination)) {
            JFolder::create($destination);
        }

        if (!JArchive::extract($source, $destination)) {
            // If backend did not extract
            return false;
        }

        // Copy admin language files
        JFile::copy(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.ini', JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.ini');
        JFile::copy(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_checklist' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.sys.ini', JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . 'en-GB' . DIRECTORY_SEPARATOR . 'en-GB.com_checklist.sys.ini');

        //Delete backend archive
        JFile::delete(JPATH_SITE . '/administrator/components/com_checklist/backend.zip');
    }

    function _installDatabase()
    {
        $db = JFactory::getDBO();
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.path');
        jimport('joomla.base.adapter');

        $sqlfile = JPATH_SITE . '/administrator/components/com_checklist/sql/install.mysql.utf8.sql';
        $buffer = file_get_contents($sqlfile);

        // Graceful exit and rollback if read not successful
        if ($buffer === false) {
            JLog::add(JText::_('JLIB_INSTALLER_ERROR_SQL_READBUFFER'), JLog::WARNING, 'jerror');

            return false;
        }

        // Create an array of queries from the sql file
        $queries = JDatabaseDriver::splitSql($buffer);

        if (count($queries) == 0) {
            // No queries to process
            return 0;
        }

        // Process each query in the $queries array (split out of sql file).
        foreach ($queries as $query) {
            $query = trim($query);

            if ($query != '' && $query{0} != '#') {
                $db->setQuery($query);

                if (!$db->execute()) {
                    JLog::add(JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)), JLog::WARNING, 'jerror');

                    return false;
                }
            }
        }
    }
}

?>