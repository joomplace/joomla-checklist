<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class LightchecklistViewDashboard extends JViewLegacy
{

    public $messageTrigger = false;

	function display($tpl = null) 
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::base().'components/com_lightchecklist/assets/js/MethodsForXml.js');
		$document->addScript(JURI::base().'components/com_lightchecklist/assets/js/MyAjax.js');
		$document->addStyleSheet(JURI::base().'components/com_lightchecklist/assets/css/checklist.css');

		$this->addTemplatePath(JPATH_BASE.'/components/com_lightchecklist/helpers/html');
        $this->dashboardItems = $this->get('Items');
		$this->addToolBar();
		$this->setDocument();
      	
		$configurationModel = JModelLegacy::getInstance('Configuration', 'LightchecklistModel');
		$this->config = $configurationModel->getConfig();

		parent::display($tpl);
	}

	protected function addToolBar() 
	{
		JToolBarHelper::title(JText::_('COM_LIGHTCHECKLIST').': '.JText::_('COM_LIGHTCHECKLIST_MANAGER_DASHBOARD'), 'dashboard');
	}

	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_LIGHTCHECKLIST').': '.JText::_('COM_LIGHTCHECKLIST_MANAGER_DASHBOARD'));
		$document->addScript('components/com_lightchecklist/assets/js/js.js');
	}
}