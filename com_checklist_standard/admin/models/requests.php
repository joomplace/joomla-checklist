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

class ChecklistModelRequests extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) { $config['filter_fields'] = array('l.id','l.title'); }
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest('com_checklist.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		parent::populateState();
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$checklist_id = JFactory::getApplication()->input->get('checklist_id');

		$query->select('r.id as id, l.id as checklist_id, l.title');
		$query->from('#__checklist_lists as l');
		
		$query->join('RIGHT', '`#__checklist_requests` as r ON r.`checklist_id` = l.`id`');
		
		if($checklist_id){
			$query->where("l.id = '".$checklist_id."'");
		}

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->Escape($search, true).'%');
			$query->where('l.`title` LIKE '.$search);
		}

		$orderCol	= $this->state->get('list.ordering','title');
		$orderDirn	= $this->state->get('list.direction','DESC');
		$query->order($db->escape('`'.$orderCol.'` '.$orderDirn));
		
		return $query;
	}

}
