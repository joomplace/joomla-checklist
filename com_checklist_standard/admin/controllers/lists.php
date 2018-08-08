<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class ChecklistControllerLists extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Lists', $prefix = 'ChecklistModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function delete()
	{
		$return = parent::delete();

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$defaultlist = $session->get('com_checklist.defaultlist');
	
		if($defaultlist){
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=1');
		} else {
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=0');
		}
		

		return $return;
	}

}
