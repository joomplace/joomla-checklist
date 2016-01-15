<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class LightchecklistControllerList extends JControllerForm
{
    
	public function cancel(){

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$defaultlist = $session->get('com_lightchecklist.defaultlist');

		if($defaultlist){
			$app->redirect('index.php?option=com_lightchecklist&view=lists&defaultlist=1');
		} else {
			$app->redirect('index.php?option=com_lightchecklist&view=lists&defaultlist=0');
		}
	}

	public function save($key = null, $urlVar = null){
		
		$return = parent::save();
		$task = JFactory::getApplication()->input->get('task');

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$defaultlist = $session->get('com_lightchecklist.defaultlist');

		if($task == 'save'){
			if($defaultlist){
				$app->redirect('index.php?option=com_lightchecklist&view=lists&defaultlist=1');
			} else {
				$app->redirect('index.php?option=com_lightchecklist&view=lists&defaultlist=0');
			}
		}

		return $return;
	}
}