<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class ChecklistControllerConfiguration extends JControllerAdmin
{
	//----------------------------------------------------------------------------------------------------
	public function getModel($name = 'Configuration', $prefix = 'ChecklistModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
	//----------------------------------------------------------------------------------------------------
	public function apply() 
	{
		$jinput = JFactory::getApplication()->input;
		
		$jform = $jinput->get('jform', array(), 'array');
		
		$permissionsModel = JModelLegacy::getInstance('Permissions', 'ChecklistModel');
		$permissionsModel->SaveComponentAsset($jform['rules']);
		
		$this->getModel()->saveConfig($jform);
		
		JFactory::getApplication()->redirect('index.php?option=com_checklist&view=configuration', JText::_('JLIB_APPLICATION_SAVE_SUCCESS'), 'message');
	}
	//----------------------------------------------------------------------------------------------------
	public function cancel() 
	{
		JFactory::getApplication()->redirect('index.php?option=com_checklist');
	}
	//----------------------------------------------------------------------------------------------------
	public function reset_permissions()
	{
		@ob_clean;
		header('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Type: text/html; charset=utf-8');
		
		$error = "";
		
		try
		{
			$permissionsModel = JModelLegacy::getInstance('Permissions', 'ChecklistModel');
			$permissionsModel->ResetAllAssets();
		}
		catch (Exception $ex)
		{
			$error = $ex->getMessage();
		}
		
		if ($error == "")
		{
			echo '<div style="color:#009900;">' .
					JText::_('COM_CHECKLIST_BE_CONFIG_RESET_ALL_PERMISSIONS_SUCCESS') .
				'</div>';
		}
		else
		{
			echo '<div style="color:#990000;">' .
					htmlspecialchars($error) .
				'</div>';
		}
		
		jexit();
	}
}