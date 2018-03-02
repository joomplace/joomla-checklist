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

class ChecklistModelUser extends JModelAdmin
{
	protected $context = 'com_checklist';

	public function getTable($type = 'User', $prefix = 'ChecklistTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		$form = $this->loadForm('com_checklist.user', 'user', array('control' => 'jform', 'load_data' => false));
		if (empty($form)) {
			return false;
		}
        
        $item = $this->getChecklistItem();
		$form->bind($item);

		return $form;
	}

	public function getAvatar()
	{
		$db = JFactory::getDBO();

		$id = JFactory::getApplication()->input->get('user_id');
		$db->setQuery("SELECT `avatar_field` FROM `#__checklist_users` WHERE `user_id` = '".$id."'");
		$avatar = $db->loadResult();

		return $avatar;
	}

	public function getChecklistItem()
	{
		$db = JFactory::getDBO();

		$id = JFactory::getApplication()->input->get('user_id');
		$db->setQuery("SELECT cu.* FROM `#__checklist_users` as cu WHERE cu.`user_id` = '".$id."'");
		$item = $db->loadObject();

		return $item;
	}

	public function getItem()
	{
		$db = JFactory::getDBO();

		$id = JFactory::getApplication()->input->get('user_id');
		$db->setQuery("SELECT u.* FROM `#__users` as u WHERE u.`id` = '".$id."'");
		$item = $db->loadObject();

		return $item;

	}
}
