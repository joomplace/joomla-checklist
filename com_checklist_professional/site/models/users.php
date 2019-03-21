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
	
    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        $limit = $app->input->get('limit', $app->get('list_limit', 0), 'uint');
        $limitstart = $app->input->getInt('limitstart', 0);

        $limitstart = ($limit != 0) ? (floor($limitstart / $limit) * $limit) : 0;

        $this->setState('user.limit', $limit);
        $this->setState('user.start', $limitstart);
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
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('user.start'), $this->getState('user.limit'));
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

		$list_access = '';
		if(count($groups)){
			$list_access = " AND `list_access` IN (".implode(",", $groups).")";
		}

		$lists = array();
		if(count($users)){
			foreach ($users as $chk_user) {
				$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$chk_user->id."'".$list_access." AND UNIX_TIMESTAMP(`publish_date`) <= '".$now."' AND `default` = 1");
				$lists[$chk_user->id] = $db->loadObjectList();
			}
		}
				
		return $lists;
		
	}

	public function getUsers($isTotal = 0)
	{
        $limit = $this->getState('user.limit');
        $limitstart = $this->getState('user.start');

		$db = JFactory::getDBO();
		
        $limit_string = (!$isTotal && $limit != 0) ? " LIMIT {$limitstart}, {$limit}" : "";

		$db->setQuery("SELECT `u`.*, `chk_u`.* FROM `#__users` AS `u` LEFT JOIN `#__checklist_users` AS `chk_u` ON `chk_u`.`user_id` = `u`.`id`".$limit_string);
		$users = $db->loadObjectList();

		return $users;
	}

}
