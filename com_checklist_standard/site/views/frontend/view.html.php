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

class ChecklistViewFrontend extends JViewLegacy
{
	function display($tpl = null) 
	{
        $user = JFactory::getUser();
		$this->available_checklists = $this->get('Checklists');
		
		$user_data = array();
		$tags_data = array();
		$_allowCopy = array();

		$model = $this->getModel();
		if(count($this->available_checklists)){
			foreach ($this->available_checklists as $checklist) {
				$user_data[$checklist->id] = $model->getUser($checklist->user_id);
				$tags_data[$checklist->id] = $model->getTags($checklist->id);
				$_allowCopy[$checklist->id] = $model->checkAllowCopy($checklist->user_id, $checklist->id);
			}

			$this->tags_data = $tags_data;
			$this->user_data = $user_data;
			$this->_allowCopy = $_allowCopy;

		}		

		$this->pagination = $this->get('Pagination');

		parent::display($tpl);
	}
}