<?php 
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class LightchecklistTableConfiguration extends JTable
{
	//----------------------------------------------------------------------------------------------------
	function __construct(&$db) 
	{
		parent::__construct('#__checklist_config', '', $db);
		
		$this->_trackAssets = true;
	}
	//----------------------------------------------------------------------------------------------------
	public function getConfig()
	{
		$db = $this->_db;
		
		$query = "SELECT * FROM `#__checklist_config`" .
			" ORDER BY `setting_name`";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$config = (object) array();
		
		if (isset($rows))
		{
			foreach ($rows as $row)
			{
				$config->{$row->setting_name} = $row->setting_value;
			}
		}
		
		return $config;
	}
	//----------------------------------------------------------------------------------------------------
	public function saveConfig($jform)
	{
		$db = $this->_db;
		
		try
		{
			$db->transactionStart();
			
			foreach ($jform as $name => $value)
			{
				if ($name != 'rules')
				{
					$query = " UPDATE `#__checklist_config`" .
						" SET `setting_value` = " . $db->quote($value) .
						" WHERE `setting_name` = " . $db->quote($name);
					$db->setQuery($query);
					$db->execute();
				}
			}
			
			$db->transactionCommit();
		}
		catch (Exception $ex)
		{
			$db->transactionRollback();
			throw $ex;
		}
	}
}