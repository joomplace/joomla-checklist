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

class ChecklistViewMain extends JViewLegacy
{
	function display($tpl = null) 
	{
        $user = JFactory::getUser();
		
		if(!$user->id){
			$layout = 'default_unregistered';
			$this->setLayout($layout);
		} else {
			
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_checklist&view=lists');
			
		}
		
		parent::display($tpl);
	}
}