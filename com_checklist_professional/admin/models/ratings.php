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

class ChecklistModelRatings extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) { $config['filter_fields'] = array('l.title', 'u.name', 'r.rate'); }
		parent::__construct($config);
	}
	
	protected function populateState()
	{
		$search = $this->getUserStateFromRequest('com_checklist.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		parent::populateState();
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('r.*, u.name, l.id, l.title');
		$query->from('#__checklist_rating as r');

		$query->join('LEFT', '`#__users` as u ON u.`id` = r.`user_id`');
		$query->join('LEFT', '`#__checklist_lists` as l ON l.`id` = r.`checklist_id`');

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->Escape($search, true).'%');
			$query->where('u.`name` LIKE '.$search.' OR l.`title` LIKE '.$search);
		}

		$orderCol	= $this->state->get('list.ordering','name');
		$orderDirn	= $this->state->get('list.direction','DESC');
		$query->order($db->escape('`'.$orderCol.'` '.$orderDirn));
		
		return $query;
	}

}
