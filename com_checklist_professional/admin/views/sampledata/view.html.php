<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class ChecklistViewSampledata extends JViewLegacy
{
	
	//----------------------------------------------------------------------------------------------------
	function display($tpl = null) 
	{
		$this->addTemplatePath(JPATH_BASE.'/components/com_checklist/helpers/html');
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		JToolBarHelper::title(JText::_('COM_CHECKLIST').': '.JText::_('COM_CHECKLIST_MENU_SAMPLE_DATA'), 'equalizer');
		JToolBarHelper::apply('sampledata.install', 'COM_CHECKLIST_INSTALL');
		JToolBarHelper::cancel('sampledata.cancel', 'JTOOLBAR_CANCEL');
	}
}