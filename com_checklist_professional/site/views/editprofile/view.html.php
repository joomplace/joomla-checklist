<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class ChecklistViewEditprofile extends JViewLegacy
{
	function display($tpl = null) 
	{
        $user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		if(!$user->id){
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', 'This area is for registered users only. Please log in to access it.'));
		} else{

			$this->user = $this->get('User');	
			$itemid = JFactory::getApplication()->input->get('Itemid', 0);
			$this->itemid = ($itemid) ? '&Itemid='.$itemid : '';

			parent::display($tpl);
		}
	}
}