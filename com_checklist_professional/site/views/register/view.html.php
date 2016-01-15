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

class ChecklistViewRegister extends JViewLegacy
{
	function display($tpl = null) 
	{
		$app = JFactory::getApplication();
		$userid = JFactory::getApplication()->input->get('userid', 0);

		if(!$userid){
			$layout = 'default';
			$this->setLayout($layout);
		} else {

			$this->userid = $userid;

			$model = $this->getModel();
			if(!$model->checkUser($userid)){
				$app->redirect(JRoute::_('index.php?option=com_checklist&view=main'));
			}

			$this->avatar_field = $this->get('Avatar');
			$layout = 'default_avatar';
			$this->setLayout($layout);
		}

		parent::display($tpl);
	}
}