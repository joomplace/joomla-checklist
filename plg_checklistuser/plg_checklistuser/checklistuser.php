<?php
/**
* Checklist Plugin for Joomla
* @version $Id: checklistuser.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage checklistuser.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');


class plgUserChecklistuser extends JPlugin
{
	
	public function onUserAfterSave($user, $isnew, $success, $msg)
	{
		$app = JFactory::getApplication();

		$args = array();
		$args['username']	= $user['username'];
		$args['email']		= $user['email'];
		$args['fullname']	= $user['name'];
		$args['password']	= $user['password'];
		
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT * FROM `#__checklist_users` WHERE `user_id` = ".$user['id']);
		$row = $db->loadObject();
		
		if ($isnew) {
			
			$row	= new stdClass();
		
			$row->user_id		= $user['id'];
			$row->website_field	= '';
			$row->twitter_field	= '';
			$row->facebook_field	= '';
			$row->google_field	= '';
			$row->description_field	= '';
			$row->avatar_field		= '';
			
			$db->insertObject( '#__checklist_users' , $row );
			
		}
		else {
			$db->updateObject( '#__checklist_users' , $row , 'user_id' );
		}
	}

	
	public function onUserAfterDelete($user, $succes, $msg)
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT user_id FROM `#__checklist_users` WHERE `user_id` = ".$user['id']);
		$exists = $db->loadResult();
		
		if ($exists){
			$db->setQuery("DELETE FROM `#__checklist_users` WHERE `user_id` = ".$user['id']);
			$db->query();
		}
	}

	
}
