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

class ChecklistViewEdit_checklist extends JViewLegacy
{
	
	function display($tpl = null) 
	{
		
        $user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		$model = $this->getModel();
		$this->access_list = $model->getGroupsList('list');
		$this->access_comment = $model->getGroupsList('comment');
		$this->item = $this->get('ListData');

		$this->metadata = $model->getMetadata();

		$checklist_id = JFactory::getApplication()->input->get('checklist_id');
		$this->tags = $model->getTags($checklist_id);
		$this->langs = $model->getLangs($checklist_id);
		$this->templates = $model->getTemplates($this->item->template);

		parent::display($tpl);
	}
}