<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class ChecklistModelList extends JModelAdmin
{
	protected $context = 'com_checklist';

	public function getTable($type = 'List', $prefix = 'ChecklistTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		$form = $this->loadForm('com_checklist.list', 'list', array('control' => 'jform', 'load_data' => false));
		if (empty($form)) {
			return false;
		}
        
        $item = $this->getItem();
		$form->bind($item);

		return $form;
	}

	public function getTags()
	{

		$db = JFactory::getDBO();
		$id = JFactory::getApplication()->input->get('id', 0);
		$tags = array();
		if($id != 0){

			$query = "SELECT b.`name` FROM `#__checklist_list_tags` AS a, `#__checklist_tags` AS b "
				. "WHERE b.`id` = a.`tag_id` AND a.`checklist_id` = '{$id}' ";
			$db->setQuery($query);

			$tags = $db->loadColumn();
		}

		return $tags;
	}

	public function getAuthor()
	{
        //seems not needed now
	    $db = JFactory::getDBO();
		$id = JFactory::getApplication()->input->get('id', 0);

		$author_id = 0;
		if($id){
			$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$id."'");
			$author_id = $db->loadResult();
		}

		$db->setQuery("SELECT `id` as value, `name` as `text` FROM `#__users`");
		$users = $db->loadObjectList();

		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('COM_CHECKLIST_SELECT_AUTHOR'));
		$users = array_merge($options, $users);

		$authors = JHTML::_('select.genericlist', $users, 'author_id', 'class="input"', 'value', 'text', $author_id);

		return $authors;
	}

	public function getMetadata()
	{
		$db = JFactory::getDBO();
		$id = JFactory::getApplication()->input->get('id', 0);

		$title = $description_before = '';
		$custom_metatags = '';
		if($id){
			$db->setQuery("SELECT `title` FROM `#__checklist_lists` WHERE `id` = '".$id."'");
			$title = $db->loadResult();

			$db->setQuery("SELECT `description_before` FROM `#__checklist_lists` WHERE `id` = '".$id."'");
			$description_before = $db->loadResult();

			$db->setQuery("SELECT `custom_metatags` FROM `#__checklist_lists` WHERE `id` = '".$id."'");
			$custom_metatags = $db->loadResult();
		}

		$meta_array = ($custom_metatags != '') ? json_decode($custom_metatags) : new stdClass;
		
		// og tags
		if (empty($meta_array->{'og:description'}))
			$meta_array->{'og:description'} = substr(strip_tags($description_before), 0, 70);

		if (empty($meta_array->{'og:title'}))
			$meta_array->{'og:title'} = substr($title, 0, 70);

		// twitter tags
		if (empty($meta_array->{'twitter:description'}))
			$meta_array->{'twitter:description'} = substr(strip_tags($description_before), 0, 70);

		if (empty($meta_array->{'twitter:title'}))
			$meta_array->{'twitter:title'} = substr($title, 0, 70);

		return $meta_array;
	}

}
