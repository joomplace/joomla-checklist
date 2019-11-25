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

class ChecklistModelTags extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
		    $config['filter_fields'] = array(
		        'id', 't.id',
                'name', 't.name'
            );
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = 't.name', $direction = 'DESC')
	{
		$search = $this->getUserStateFromRequest('com_checklist.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		parent::populateState($ordering, $direction);
	}

	protected function getListQuery() 
	{
		$db = $this->_db;
		$query = $db->getQuery(true);

		$query->select('t.*');
		$query->from('#__checklist_tags as t');

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->Escape($search, true).'%');
			$query->where('t.`name` LIKE '.$search);
		}

		$orderCol	= $this->state->get('list.ordering','t.name');
		$orderDirn	= $this->state->get('list.direction','DESC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		
		return $query;
	}

	public function getChecklists($tag)
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT l.* FROM `#__checklist_lists` as l LEFT JOIN `#__checklist_list_tags` as lt ON lt.`checklist_id` = l.`id` WHERE lt.`tag_id` = '".$tag->id."'");
		$checklists = $db->loadObjectList();

		return $checklists;
	}

	public function getTag(){
		
		$db = JFactory::getDBO();
		$tag_id = JFactory::getApplication()->input->get('id');
		
		if($tag_id){
			$db->setQuery("SELECT * FROM `#__checklist_tags` WHERE `id` = '".$tag_id."'");
			$tag = $db->loadObject();
		} else {
			$tag = new stdClass;
			$tag->id = '';
			$tag->name = '';
		}

		return $tag;
	}

	public function getAllList()
	{
		$db = JFactory::getDBO();

		$options = array();
		$options[] = JHTML::_('select.option', '', JText::_('COM_CHECKLIST_SELECT_LIST'));
		$db->setQuery("SELECT `id` as `value`, `title` as `text` FROM `#__checklist_lists`");
		$lists = $db->loadObjectList();

		$lists = array_merge($options, $lists);
		return $lists;
	}

	static public function delete($cid){

		$db = JFactory::getDBO();
		$option = "com_checklist";
		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$query = "DELETE FROM `#__checklist_tags`"
			. "\n WHERE id IN ( $cids )"
			;
			$db->setQuery( $query );
			if (!$db->execute()) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
			
			$db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `tag_id` IN (".$cids.")");
			$db->query();
			
		}
		
		return true;
	}
}
