<?php defined('_JEXEC') or die('Restricted access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class LightchecklistModelConfiguration extends JModelAdmin
{
	//----------------------------------------------------------------------------------------------------
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	//----------------------------------------------------------------------------------------------------
	public function getTable($type = 'Configuration', $prefix = 'LightchecklistTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	//----------------------------------------------------------------------------------------------------
	public function getItem($pk = null)
	{
		return $this->getConfig();
	}
	//----------------------------------------------------------------------------------------------------
	public function getForm($data = array(), $loadData = true)
	{
		$app = JFactory::getApplication();
		
		$form = $this->loadForm('com_lightchecklist.configuration', 'configuration', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) return false;
		
		return $form;
	}
	//----------------------------------------------------------------------------------------------------
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_lightchecklist.edit.configuration.data', array());
		
		if (empty($data))
		{
			$data = $this->getItem();
		}
		
		return $data;
	}
	//----------------------------------------------------------------------------------------------------
	public function getConfig()
	{
		return $this->getTable()->getConfig();
	}
	//----------------------------------------------------------------------------------------------------
	public function saveConfig($jform)
	{
		$this->getTable()->saveConfig($jform);
	}
}