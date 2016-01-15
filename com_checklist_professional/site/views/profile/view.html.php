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

class ChecklistViewProfile extends JViewLegacy
{
	function display($tpl = null) 
	{
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$user = JFactory::getUser();
		if(!$user->id && !$userid){
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_users&view=login');
			return;
		}

		$this->user = $this->get('UserData');		
		$this->checklists = $this->get('Checklists');
		
		require_once(JPATH_SITE.'/administrator/components/com_checklist/models/configuration.php');
		$configurationModel = JModelLegacy::getInstance('Configuration', 'ChecklistModel');
		$this->config = $configurationModel->GetConfig();
		
		$tags_data = array();
		$_allowCopy = array();

		$model = $this->getModel();
		if(count($this->checklists)){
			foreach ($this->checklists as $checklist) {
				$tags_data[$checklist->id] = $model->getTags($checklist->id);
				$_allowCopy[$checklist->id] = $model->checkAllowCopy($checklist->user_id, $checklist->id);
			}

			$this->tags_data = $tags_data;
			$this->_allowCopy = $_allowCopy;
		}

		parent::display($tpl);
	}
}