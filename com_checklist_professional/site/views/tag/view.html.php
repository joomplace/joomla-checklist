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
		
		require_once(JPATH_SITE.'/administrator/components/com_checklist/models/configuration.php');
		$configurationModel = JModelLegacy::getInstance('Configuration', 'ChecklistModel');
		$this->config = $configurationModel->GetConfig();

		$this->itemid = $app->input->get('Itemid');
		$this->pagination = $this->get('Pagination');
		$this->checklists = $this->get('Checklists');		

		$user_data = array();
		$tags_data = array();
		$_allowCopy = array();

		$model = $this->getModel();
		if(count($this->checklists)){
			foreach ($this->checklists as $checklist) {
				$user_data[$checklist->id] = $model->getUser($checklist->user_id);
				$_allowCopy[$checklist->id] = $model->checkAllowCopy($checklist->user_id, $checklist->id);
			}

			$this->tags_data = $tags_data;
			$this->user_data = $user_data;
			$this->_allowCopy = $_allowCopy;

		}

		$this->tag_name = $this->get('Tagname');
		$this->total = $this->get('Total');

		parent::display($tpl);
	}
}