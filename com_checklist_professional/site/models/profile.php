<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelProfile extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getUserData()
	{
		$userid = JFactory::getApplication()->input->get('userid', 0);

		$user = JFactory::getUser();
		$userid = ($userid) ? $userid : $user->id;

		$db = JFactory::getDBO();

		$db->setQuery("SELECT `u`.*, `chk_u`.* FROM `#__users` AS `u` LEFT JOIN `#__checklist_users` AS `chk_u` ON `chk_u`.`user_id` = `u`.`id` WHERE u.`id` = '".$userid."'");
		$user_data = $db->loadObject();

		return $user_data;
	}

	public function getChecklists()
	{
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$user = JFactory::getUser();
		$userid = ($userid) ? $userid : $user->id;
		
		$db = JFactory::getDBO();

		$now = time();
		$groups = ChecklistHelper::getAllowGroups();

		$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$userid."' AND UNIX_TIMESTAMP(`publish_date`) <= '".$now."' AND `list_access` IN (".implode(",", $groups).") AND `default` = 1");
		$checklists = $db->loadObjectList();

		$checklists = $this->getRatings($checklists);

		return $checklists;
	}

	public function getRatings($checklists)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		if(count($checklists)){
			foreach ($checklists as &$checklist) {
				$db->setQuery("SELECT AVG(`rate`) FROM `#__checklist_rating` WHERE `checklist_id` = '".$checklist->id."'");
	    		$checklist->average_rating = $db->loadResult();
			}
		}

		return $checklists;
	}

	public function getTags($checklist_id)
	{
		$db = JFactory::getDBO();
		$tags_array = array();
	
		$db->setQuery("SELECT t.`name`, t.`id` FROM `#__checklist_tags` as t LEFT JOIN `#__checklist_list_tags` as l ON l.`tag_id` = t.`id` WHERE l.`checklist_id` = '".$checklist_id."'");
		$tags_array[$checklist_id] = $db->loadAssocList();

		return $tags_array;
	}

	public function checkAllowCopy($userid, $checklist_id)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		if(!$user->id){
			return 0;
		}

		if($user->id == $userid){
			$db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."' AND `user_id` = '".$user->id."' AND `default` = '1'");
			$my = $db->loadResult();

			if($my){
				return 0;
			}
		}

		return 1;
	}

}
