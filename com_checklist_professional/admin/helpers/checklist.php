<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Checklist component helper.
 */
class ChecklistHelper
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
			$title = JText::_('COM_CHECKLIST_ADMINISTRATION_'.strtoupper($submenu));
            $document->setTitle($title);
            JToolBarHelper::title($title, $icon);                	               	              
        }

        public static function getActions($categoryId = 0, $listId = 0)
        {
            $user   = JFactory::getUser();
            $result = new JObject;

            if (empty($listId) && empty($categoryId)) {
                $assetName = 'com_checklist';
            }
            else if (empty($listId)) {
                $assetName = 'com_checklist.category.'.(int)$categoryId;
            }
            else {
                $assetName = 'com_checklist.list.'.(int)$listId;
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
                JText::_('COM_CHECKLIST_SUBMENU_USER_CHECKLIST'),
                'index.php?option=com_checklist&view=lists&defaultlist=0',
                $vName == 'user_lists'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_CHECKLIST_SUBMENU_DEFAULT_CHECKLIST'),
                'index.php?option=com_checklist&view=lists&defaultlist=1',
                $vName == 'default_lists');

            JHtmlSidebar::addEntry(
                JText::_('COM_CHECKLIST_SUBMENU_USERS'),
                'index.php?option=com_checklist&view=users',
                $vName == 'users'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_CHECKLIST_SUBMENU_RATINGS'),
                'index.php?option=com_checklist&view=ratings',
                $vName == 'ratings'
            );

            JHtmlSidebar::addEntry(
                JText::_('COM_CHECKLIST_TAG_LISTS'),
                'index.php?option=com_checklist&view=tags',
                $vName == 'tags'
            );
        }
}