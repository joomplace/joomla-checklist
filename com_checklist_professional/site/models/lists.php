<?php
/**
* Staticcontent component for Joomla 3.0
* @package Staticcontent
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelLists extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getTotal()
	{
		$db = JFactory::getDBO();
		
		
		if (empty($this->_total))
		{
			$checklists = $this->getChecklists(1);
			if($this->_allow_edit){
				$this->_total = count($checklists['my']);
			} else {
				$this->_total = count($checklists['user']);
			}
		}

		return $this->_total;
	}

	public function getPagination(){

		if (empty($this->_pagination))
		{
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('filter.limitstart'), $this->getState('filter.limit'));
		}

		return $this->_pagination;
	}

	public function getUser($uid){
		
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT u.`id`, u.`name`, chk_u.* FROM `#__checklist_users` AS `chk_u` LEFT JOIN `#__users` AS u ON u.`id` = chk_u.`user_id` WHERE u.`id` = '".$uid."'");
		$data = $db->loadObject();

		if(empty($data)){

			$user = JFactory::getUser($uid);
			$data = new stdClass;

			$data->id = $uid;
			$data->name = $user->name;
			$data->user_id = $uid;
			$data->website_field = '';
			$data->twitter_field = '';
			$data->facebook_field = '';
			$data->google_field = '';
			$data->description_field = '';
			$data->avatar_field = '';
		}

		return $data;
	}

	public function getChecklists($isTotal = 0){
		
		$app = JFactory::getApplication();
		$limit = $app->input->get('limit', $app->getCfg('list_limit', 0), 'uint');
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $limitstart);
		
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$checklists = array();
		
		$this->_allow_edit = false;
		if($userid && $userid == $user->id) $this->_allow_edit = true;
		if(!$userid && $user->id) $this->_allow_edit = true;

		$limit_string = (!$isTotal) ? " LIMIT {$limitstart}, {$limit}" : "";

		if($this->_allow_edit){

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."'".$limit_string);
			$checklists['my'] = $db->loadObjectList();

		} else {

			$now_date = time();
			$groups = ChecklistHelper::getAllowGroups();

			$list_access = '';
			if(count($groups)){
				$list_access = " AND `list_access` IN (".implode(",", $groups).")";
			}

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$userid."'".$list_access." AND UNIX_TIMESTAMP(`publish_date`) <= '".$now_date."' AND `default` = '1'".$limit_string);
			$checklists['user'] = $db->loadObjectList();
		}

		return $checklists;	
	}

	public function getUname(){
		
		$db = JFactory::getDBO();
		
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$db->setQuery("SELECT `name` FROM `#__users` WHERE `id` = '".$userid."'");
		$uname = $db->loadResult();

		return $uname;
	}

	public function getTags($checklists)
	{

		$db = JFactory::getDBO();
		$tags_array = array();

		if(isset($checklists['my']) && count($checklists['my'])){
			foreach ($checklists['my'] as $checklist) {
				$db->setQuery("SELECT t.`name` FROM `#__checklist_tags` as t LEFT JOIN `#__checklist_list_tags` as l ON l.`tag_id` = t.`id` WHERE l.`checklist_id` = '".$checklist->id."'");
				$tags_array[$checklist->id] = $db->loadColumn();
			}
		}

		return $tags_array;
	}

}
