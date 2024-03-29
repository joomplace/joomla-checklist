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

class ChecklistModelUsers extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
		    $config['filter_fields'] = array(
		        'id', 'u.id',
                'name', 'u.name',
                'username', 'u.username',
                'email', 'u.email',
                'lastvisitDate', 'u.lastvisitDate',
                'registerDate', 'u.registerDate'
            );
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = 'u.name', $direction = 'DESC')
	{
		$search = $this->getUserStateFromRequest('com_checklist.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		parent::populateState($ordering, $direction);
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('u.*');
		$query->from('#__users as u');
		$query->join('LEFT', '`#__checklist_users` as cu ON cu.`user_id` = u.`id`');

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->Escape($search, true).'%');
			$query->where('u.`name` LIKE '.$search);
		}

		$orderCol	= $this->state->get('list.ordering','u.name');
		$orderDirn	= $this->state->get('list.direction','DESC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		
		return $query;
	}

	static public function delete($cid)
    {
		$db = JFactory::getDBO();
		$option = "com_checklist";
		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$query = "DELETE FROM `#__checklist_lists`"
			. "\n WHERE id IN ( $cids )"
			;
			$db->setQuery( $query );
			if (!$db->execute()) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			
			$db->setQuery("DELETE FROM `#__checklist_groups` WHERE `checklist_id` IN (".$cids.")");
			$db->query();
				
			$db->setQuery("DELETE FROM `#__checklist_items` WHERE `checklist_id` IN (".$cids.")");
			$db->query();
		}
		
		return true;
	}
}
