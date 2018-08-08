<?php
/**
* Checklist Deluxe Component for Joomla 3
* @package Checklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');
 
/**
 * Registration Controller
 */
class ChecklistControllerRegistration extends JControllerAdmin
{
		
	public function register()
	{

		$params = JComponentHelper::getParams('com_users');

		// Initialise the table with JUser.
		$user = new JUser;
		$data = JRequest::get('post');
				
		// Prepare the data for the user object.
		
		$data['name'] = $data['uname'];
		$data['email'] = JStringPunycode::emailToPunycode($data['email1']);
		$username = $data['username'];
		$data['password'] = $sendpassword = $data['password1'];
		
		$data['activation'] = '';
		$data['block'] = 0;
	
		$system = $params->get('new_usertype', 2);
		$data['groups'][] = $system;
		
		// Bind the data.
		if (!$user->bind($data))
		{
			$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			return false;
		}
		
		// Load the users plugin group.
		JPluginHelper::importPlugin('user');

		// Store the data.
		if (!$user->save())
		{
			$this->setError($user->getError());
			return false;
		}
		
		$config = JFactory::getConfig();
		$db = JFactory::getDbo();
		
		$row = new stdClass;

		$row->user_id = $user->id;
		$row->website_field = $data['website_field'];
		$row->twitter_field = $data['twitter_field'];
		$row->facebook_field = $data['facebook_field'];
		$row->google_field = $data['google_field'];
		$row->description_field = $data['description_field'];
		$row->avatar_field = '';

		$db->insertObject('#__checklist_users', $row, 'user_id');
		$this->setRedirect(JRoute::_('index.php?option=com_checklist&view=register&userid='.$row->user_id));

		return;
	}
	
	public function login($username, $password)
	{
		$app = JFactory::getApplication();

		// Populate the data array:
		$data = array();
		$data['username'] = $username;
		
		// Get the log in options.
		$options = array();
		$options['remember'] = 1;
		$options['return'] = '';
		
		// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $password;

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			// Success
			$app->setUserState('users.login.form.data', array());
			$app->redirect(JRoute::_('index.php?option=com_checklist&view=lists'));
		}
		else
		{
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}

	public function ajaxcheckemail()
	{
		$app = JFactory::getApplication();
		$email = $app->input->getString('email');

		$db = JFactory::getDbo();

		$db->setQuery("SELECT COUNT(`id`) FROM `#__users` WHERE `email` = '".$email."'");
		$exists = $db->loadResult();

		if(!$exists){
			echo 'not_exists';
		} else {
			echo 'exists';
		}

		die;
	}
}
