<?php
/**
* Lightchecklist Component for Joomla 3
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Lightchecklist component helper.
 */
class LightchecklistHelper
{		
        public static function getVersion() 
        {
        	$db = JFactory::getDbo();
			$db->setQuery('SELECT `c_par_value` FROM #__checklist_setup WHERE `c_par_name` = "checklist_version"');
			return $db->loadResult();
        }
                
        public static function showTitle($submenu, $icon)  
        {       
         	$document = JFactory::getDocument();
			$title = JText::_('COM_LIGHTCHECKLIST_ADMINISTRATION_'.strtoupper($submenu));
            $document->setTitle($title);
            JToolBarHelper::title($title, $icon);                	               	              
        }

        public static function getActions($categoryId = 0, $listId = 0)
        {
            $user   = JFactory::getUser();
            $result = new JObject;

            if (empty($listId) && empty($categoryId)) {
                $assetName = 'com_lightchecklist';
            }
            else if (empty($listId)) {
                $assetName = 'com_lightchecklist.category.'.(int)$categoryId;
            }
            else {
                $assetName = 'com_lightchecklist.list.'.(int)$listId;
            }

            $actions = array(
                'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
            );

            foreach ($actions as $action) {
                $result->set($action,   $user->authorise($action, $assetName));
            }

            return $result;
        }

        public static function addChecklistSubmenu($vName)
        {
            JHtmlSidebar::addEntry(
                JText::_('COM_LIGHTCHECKLIST_SUBMENU_UNAVAILABLE_LISTS'),
                'index.php?option=com_lightchecklist&view=lists&defaultlist=0',
                $vName == 'user_lists'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_LIGHTCHECKLIST_SUBMENU_DEFAULT_CHECKLIST'),
                'index.php?option=com_lightchecklist&view=lists&defaultlist=1',
                $vName == 'default_lists');

            JHtmlSidebar::addEntry(
                JText::_('COM_LIGHTCHECKLIST_SUBMENU_USERS'),
                'index.php?option=com_lightchecklist&view=users',
                $vName == 'users'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_LIGHTCHECKLIST_TAG_LISTS'),
                'index.php?option=com_lightchecklist&view=tags',
                $vName == 'tags'
            );
        }
}