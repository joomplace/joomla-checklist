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

class ChecklistViewTag extends JViewLegacy
{
	protected $pagination;
	
	function display($tpl = null) 
	{
		$app = JFactory::getApplication();
		
		$this->itemid = $app->input->get('Itemid');
		$this->pagination = $this->get('Pagination');
		$this->checklists = $this->get('Checklists');		

		$_allowCopy = array();
		$model = $this->getModel();
		if(count($this->checklists)){
			foreach ($this->checklists as $checklist) {
				$_allowCopy[$checklist->id] = $model->checkAllowCopy($checklist->user_id, $checklist->id);
			}
		}

		$this->_allowCopy = $_allowCopy;
		$this->tag_name = $this->get('Tagname');
		$this->total = $this->get('Total');

		parent::display($tpl);
	}
}