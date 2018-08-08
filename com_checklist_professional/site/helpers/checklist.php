<?php
/**
* Checklist Deluxe Component for Joomla 3
* @package Checklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
 
/**
 * Checklist Deluxe component helper.
 */
class ChecklistHelper
{
                
        public static function showTitle($submenu)  
        {       
         	$document = JFactory::getDocument();
			$title = JText::_('COM_STATICCONTENT_ADMINISTRATION_'.strtoupper($submenu));
            $document->setTitle($title);               	               	              
        }

        public static function getAllowGroups(){

        	$user = JFactory::getUser();
			$db = JFactory::getDBO();

			//Permissions
			$db->setQuery("SELECT `id` FROM `#__usergroups` WHERE `title` LIKE '%Administrator%'");
			$adminId = $db->loadResult();

			$db->setQuery("SELECT `id` FROM `#__usergroups` WHERE `title` LIKE '%Super%'");
			$superId = $db->loadResult();

			$adminArray = array ($adminId, $superId);
					
			$groups = array();
			foreach ($user->groups as $groupId => $group) {

				if(!$groupId){
					//Nothing to do
				} else {
					$groups[] = $groupId;

					if(in_array($groupId, $adminArray)){
						$db->setQuery("SELECT `id` FROM `#__usergroups` WHERE `id` <> '".$groupId."'");
						$allId = $db->loadColumn();

						$groups = array_merge($groups, $allId);
					}

					$groups[] = '-'.$user->id;
				}
			}

			return $groups;
        }
		
}