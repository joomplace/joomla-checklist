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
            $query = $db->getQuery(true);

            $groups = $user->getAuthorisedViewLevels();
            $checkGroups = array();
            $allowGroups = array();

            foreach ($groups as $group){
                if(!in_array($group, $checkGroups)){
                    $checkGroups[] = $group;

                    $query->clear();
                    $query->select($db->qn('rules'))
                        ->from($db->qn('#__viewlevels'))
                        ->where($db->qn('id') .'='. $db->q($group));
                    $db->setQuery($query);
                    $rules = $db->loadResult();

                    if(!empty($rules)){
                        $rules = json_decode($rules);
                        $allowGroups = array_merge($allowGroups, $rules);
                    }
                }
            }

            $allowGroups = array_values(array_unique($allowGroups));

            return $allowGroups;
        }
}