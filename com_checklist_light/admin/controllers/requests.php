<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class LightchecklistControllerRequests extends JControllerAdmin
{	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function ajaxconfirm()
	{

		$db = JFactory::getDBO();

		$request_id = JFactory::getApplication()->input->get('request_id');
		$message = JFactory::getApplication()->input->get('message', '', 'raw');

		$db->setQuery("SELECT `checklist_id` FROM `#__checklist_requests` WHERE `id` = '".$request_id."'");
		$checklist_id = $db->loadResult();

		if($checklist_id){

			$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$user_id = $db->loadResult();

			$user = JFactory::getUser($user_id);

			//Send email notification
			$message = sprintf($message, $user->name);
			$subject = JText::_('COM_LIGHTCHECKLIST_USER_CONFIRM_NOTIFICATION_EMAIL_SUBJECT');
			$emails = array($user->email);

			if($this->sendEmailNotification($emails, $message, $subject)){

				$db->setQuery("UPDATE `#__checklist_lists` SET `default` = '1' WHERE `id` = '".$checklist_id."'");
				$db->execute();

				$db->setQuery("DELETE FROM `#__checklist_requests` WHERE `id` = '".$request_id."'");
				$db->execute();

				echo "success";
			} else {
				echo "faild";
			}
		}

		die;
	}

	public function ajaxreject(){

		$db = JFactory::getDBO();

		$request_id = JFactory::getApplication()->input->get('request_id');
		$message = JFactory::getApplication()->input->get('message', '', 'raw');
		
		$db->setQuery("SELECT `checklist_id` FROM `#__checklist_requests` WHERE `id` = '".$request_id."'");
		$checklist_id = $db->loadResult();

		if($checklist_id){

			$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$user_id = $db->loadResult();

			$user = JFactory::getUser($user_id);

			//Send email notification
			$message = sprintf($message, $user->name);
			$subject = JText::_('COM_LIGHTCHECKLIST_USER_REJECT_NOTIFICATION_EMAIL_SUBJECT');
			$emails = array($user->email);

			if($this->sendEmailNotification($emails, $message, $subject)){

				$db->setQuery("DELETE FROM `#__checklist_requests` WHERE `id` = '".$request_id."'");
				$db->execute();

				echo "success";

			} else {
				echo "faild";
			}
		}

		die;
	}

	public function sendEmailNotification($emails, $message, $subject){

		$mailer = JFactory::getMailer();

        $config = new JConfig();
        $mailfrom = $config->mailfrom;
        $sitename = $config->fromname;

		$sender = array($mailfrom, $sitename);
		$mailer->setSender($sender);
		
		foreach ($emails as &$email){
			$email = trim($email);
		}

		$mailer->addRecipient($emails);
		$body   = $message;
		$mailer->isHTML(true);
		$mailer->setSubject($subject);
		$mailer->setBody($body);

        $send = $mailer->Send();
		if ( $send !== true ) {
		    echo 'Error sending email: ' . $send->__toString();
		    die;
		}

		return true;

	}

}
