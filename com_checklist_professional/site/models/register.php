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

class ChecklistModelRegister extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function checkUser($userid)
	{
		$db = JFactory::getDBO();

		$db->setQuery("SELECT COUNT(`user_id`) FROM `#__checklist_users` WHERE `user_id` = '".$userid."'");
		$isTrue = $db->loadResult();

		return $isTrue;
	}

	public function getAvatar()
	{
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$db = JFactory::getDBO();

		$db->setQuery("SELECT `avatar_field` FROM `#__checklist_users` WHERE `user_id` = '".$userid."'");
		$avatar = $db->loadResult();

		return $avatar;
	}

}
