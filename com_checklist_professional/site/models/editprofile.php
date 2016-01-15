<?php
/**
* Staticcontent component for Joomla 3.0
* @package Staticcontent
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelEditprofile extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getUser(){
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT u.`name`, chk_u.* FROM `#__checklist_users` AS `chk_u` LEFT JOIN `#__users` AS u ON u.`id` = chk_u.`user_id` WHERE u.`id` = '".$user->id."'");
		$data = $db->loadObject();

		if(empty($data)){
			$data = new stdClass;
			$data->user_id = $user->id;
			$data->website_field = '';
			$data->twitter_field = '';
			$data->facebook_field = '';
			$data->google_field = '';
			$data->description_field = '';
			$data->avatar_field = '';
			
			$db->setQuery("SELECT u.`name` FROM `#__users` AS `u` WHERE u.`id` = '".$user->id."'");
			$data->name = $db->loadResult();

		}

		return $data;
	}

}
