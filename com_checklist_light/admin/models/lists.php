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

class LightchecklistModelLists extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) { $config['filter_fields'] = array('l.id','l.title','l.publish_date','l.default', 'l.user_id'); }
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest('com_lightchecklist.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		parent::populateState();
	}

	public function getTagList()
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT `id` as `value`, `name` as `text` FROM `#__checklist_tags`");
		$taglist = $db->loadObjectList();

		return $taglist;
	}

	public function getRequest($item)
	{
		$db = JFactory::getDBO();

		$db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_requests` WHERE `user_id` = '".$item->user_id."' AND `checklist_id` = '".$item->id."'");
	    $already_sent = $db->loadResult();

	    return $already_sent;
	}

	protected function getListQuery() 
	{
		$session = JFactory::getSession();

		$db = $this->_db;
		$query = $db->getQuery(true);

		$tag_id = $this->getUserStateFromRequest('com_lightchecklist.filter.tag_id', 'filter_tag_id');
		$default = JFactory::getApplication()->input->get('defaultlist', '');

		$query->select('l.*, u.name, cu.avatar_field');
		$query->from('#__checklist_lists as l');

		$query->join('LEFT', '`#__users` as u ON u.id = l.user_id');
		$query->join('LEFT', '`#__checklist_users` as cu ON cu.user_id = u.id');

		if($tag_id){
			$query->join('LEFT', '`#__checklist_list_tags` as lt ON lt.`checklist_id` = l.`id`');
			$query->join('LEFT', '`#__checklist_tags` as t ON t.`id` = lt.`tag_id`');
			$query->where('t.`id` = '.$tag_id);
		}

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->Escape($search, true).'%');
			$query->where('l.`title` LIKE '.$search);
		}

		if($default == '1'){
			$today = time();
			$session->set('com_lightchecklist.defaultlist', 1);
            $query->where($db->qn('l.default') .'='. $db->q('1'));
			$query->where('UNIX_TIMESTAMP(l.`publish_date`) <= '.$today);
		} else {
			$session->set('com_lightchecklist.defaultlist', 0);
			$query->where('l.`user_id` <> 0');
		}

		$orderCol	= $this->state->get('list.ordering','title');
		$orderDirn	= $this->state->get('list.direction','DESC');
		$query->order($db->escape('`'.$orderCol.'` '.$orderDirn));
		
		return $query;
	}

	public function getTags($items)
	{

		$db = JFactory::getDBO();
		
		$tags = array();
		if(count($items)){
			foreach ($items as $item) {
				if($item->id != 0){

					$query = "SELECT b.`name`, b.`id` FROM `#__checklist_list_tags` AS a, `#__checklist_tags` AS b "
						. "WHERE b.`id` = a.`tag_id` AND a.`checklist_id` = '{$item->id}' ";
					$db->setQuery($query);

					$tags[$item->id] = $db->loadObjectList();
				}
			}
		}

		return $tags;
	}

	static public function delete($cid){

		$db = JFactory::getDBO();
		$option = "com_lightchecklist";
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
			$db->execute();
				
			$db->setQuery("DELETE FROM `#__checklist_items` WHERE `checklist_id` IN (".$cids.")");
			$db->execute();

			//Delete requests
			$db->setQuery("DELETE FROM `#__checklist_requests` WHERE `checklist_id` IN (".$cids.")");
			$db->execute();

			//Delete tags
			$db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `checklist_id` IN (".$cids.")");
			$db->execute();
			
		}
		
		return true;
	}
}
