<?php defined('_JEXEC') or die('Restricted access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class LightchecklistViewSampledata extends JViewLegacy
{
	
	//----------------------------------------------------------------------------------------------------
	function display($tpl = null) 
	{
		$this->addTemplatePath(JPATH_BASE.'/components/com_lightchecklist/helpers/html');
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {

		JToolBarHelper::title(JText::_('COM_LIGHTCHECKLIST').': '.JText::_('COM_LIGHTCHECKLIST_MENU_SAMPLE_DATA'), 'equalizer');
		JToolBarHelper::apply('sampledata.install', 'COM_LIGHTCHECKLIST_INSTALL');
		JToolBarHelper::cancel('sampledata.cancel', 'JTOOLBAR_CANCEL');
	}
}