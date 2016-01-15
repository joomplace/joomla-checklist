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

class ChecklistModelUsers extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getTotal()
	{
		if (empty($this->_total))
		{
			$this->_total = count($this->getUsers(1));
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

	public function getLists(){
		
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT `u`.*, `chk_u`.* FROM `#__users` AS `u` LEFT JOIN `#__checklist_users` AS `chk_u` ON `chk_u`.`user_id` = `u`.`id`");
		$users = $db->loadObjectList();

		$now = time();
		$groups = ChecklistHelper::getAllowGroups();

		$lists = array();
		if(count($users)){
			foreach ($users as $chk_user) {
				$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$chk_user->id."' AND `list_access` IN (".implode(",", $groups).") AND UNIX_TIMESTAMP(`publish_date`) <= '".$now."' AND `default` = 1");
				$lists[$chk_user->id] = $db->loadObjectList();
			}
		}
				
		return $lists;
		
	}

	public function getUsers($isTotal = 0)
	{
		$app = JFactory::getApplication();
		$limit = $app->getUserStateFromRequest('users.filter.limit', 'limit', 5);
		$this->setState('filter.limit', $limit);

		$limitstart = $app->getUserStateFromRequest('users.filter.limitstart', 'limitstart', 0);
		$this->setState('filter.limitstart', $limitstart);

		$db = JFactory::getDBO();
		
		if($limit){
			$limit_string = (!$isTotal) ? " LIMIT {$limitstart}, {$limit}" : "";
		} elseif(!$limit) {
			$limit_string = "";
		}

		$db->setQuery("SELECT `u`.*, `chk_u`.* FROM `#__users` AS `u` LEFT JOIN `#__checklist_users` AS `chk_u` ON `chk_u`.`user_id` = `u`.`id`".$limit_string);
		$users = $db->loadObjectList();

		return $users;

	}

}
