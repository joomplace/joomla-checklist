<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class LightchecklistModelUser extends JModelAdmin
{
	protected $context = 'com_lightchecklist';

	public function getTable($type = 'User', $prefix = 'LightchecklistTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true) 
	{
		$form = $this->loadForm('com_lightchecklist.user', 'user', array('control' => 'jform', 'load_data' => false));
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

    public function getItem($pk = NULL)
    {
        $db = JFactory::getDBO();
        $pk = JFactory::getApplication()->input->getInt('user_id', 0);
        $db->setQuery("SELECT u.* FROM `#__users` as u WHERE u.`id` = '".$pk."'");
        $item = $db->loadObject();
        return $item;
    }
}
