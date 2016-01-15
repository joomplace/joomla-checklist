<?php defined('_JEXEC') or die('Restricted access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class LightchecklistControllerSampledata extends JControllerAdmin
{
	//----------------------------------------------------------------------------------------------------
	public function getModel($name = 'Sampledata', $prefix = 'LightchecklistModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	//----------------------------------------------------------------------------------------------------
	public function install() 
	{
		$jinput = JFactory::getApplication()->input;
		$post = JRequest::get('post');
		$this->getModel()->installSampledata($post);
		
		JFactory::getApplication()->redirect('index.php?option=com_lightchecklist&view=sampledata', JText::_('COM_LIGHTCHECKLIST_INSTALL_SAMPLEDATA_SUCCESS'), 'message');
	}
	//----------------------------------------------------------------------------------------------------
	public function cancel() 
	{
		JFactory::getApplication()->redirect('index.php?option=com_lightchecklist');
	}
}