<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class ChecklistControllerUser extends JControllerForm
{
    public function save()
    {
    	$task = JFactory::getApplication()->input->getString('task');
    	
    	$user_id = JFactory::getApplication()->input->get('user_id');

    	if($task == 'apply'){
    		$this->setRedirect('index.php?option=com_checklist&view=user&layout=edit&user_id='.$user_id, JText::_('COM_CHECKLIST_USER_SAVED'));
    	} else if($task == 'save'){
    		$this->setRedirect('index.php?option=com_checklist&view=users', JText::_('COM_CHECKLIST_USER_SAVED'));
    	}
    	return true;
    }
}