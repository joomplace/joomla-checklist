<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class LightchecklistModelTag extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getTotal()
	{
		if (empty($this->_total))
		{
			$this->_total = count($this->getChecklists(1));
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

	public function getChecklists($isTotal = 0){
		
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();

		$tag_id = $app->input->get('id');

		if($app->isAdmin()){
			$params = JComponentHelper::getParams('com_lightchecklist');
		} else {
			$params = $app->getParams();
		}

		if(!$tag_id){
			$tag_id = $params->get('tag_id', 0);
		}

		$limit = $app->getUserStateFromRequest('tag.filter.limit', 'limit', 5);
		$this->setState('filter.limit', $limit);

		$limitstart = $app->getUserStateFromRequest('tag.filter.limitstart', 'limitstart', 0);
		$this->setState('filter.limitstart', $limitstart);
		
		$checklists = array();
		
		if($limit){
			$limit_string = (!$isTotal) ? " LIMIT {$limitstart}, {$limit}" : "";
		} elseif(!$limit) {
			$limit_string = "";
		}
		

		$now = time();
		$groups = LightchecklistHelper::getAllowGroups();
		
		$db->setQuery("SELECT l.* FROM `#__checklist_lists` as l LEFT JOIN `#__checklist_list_tags` as lt ON lt.`checklist_id` = l.`id` WHERE lt.`tag_id` = '".$tag_id."' AND l.`default` = '1' AND UNIX_TIMESTAMP(l.`publish_date`) <= '".$now."' AND l.`list_access` IN (".implode(",", $groups).") ".$limit_string);
		$checklists = $db->loadObjectList();

		return $checklists;
	}

	public function getTagname()
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		$tag_id = $app->input->get('id');

		$db->setQuery("SELECT `name` FROM `#__checklist_tags` WHERE `id` = '".$tag_id."'");
		$tag_name = $db->loadResult();

		return $tag_name;
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
