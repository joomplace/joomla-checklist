<?php defined('_JEXEC') or die('Restricted access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

jimport('joomla.access.rules');

class LightchecklistModelPermissions extends JModelLegacy
{
	//----------------------------------------------------------------------------------------------------
	public static function SaveComponentAsset($rules)
	{
		self::GetComponentRootAssetId(); // Contains error check.
		
		self::UpdateExistingAsset('com_lightchecklist', $rules);
	}
	//----------------------------------------------------------------------------------------------------
	public static function SavePublicationAsset($listId, $rules)
	{
		$publicationAssetName = 'com_lightchecklist.list.'.$listId;
		
		self::UpdateExistingAsset($publicationAssetName, $rules);
	}
	//----------------------------------------------------------------------------------------------------
	public static function ResetAllAssets()
	{
		$db = JFactory::getDBO();
		
		$componentRootAssetId = self::GetComponentRootAssetId();
		
		// Adding missing assets.
		
		$query = "SELECT * FROM `#__checklist_lists`" .
			" ORDER BY `id`";
		$db->setQuery($query);
		$lists = $db->loadObjectList();
		
		$query = "SELECT * FROM `#__assets`" .
			" WHERE `name` REGEXP '" . "com_lightchecklist.list." . "'";
			" ORDER BY `id`";
		$db->setQuery($query);
		$assets = $db->loadObjectList();
		
		$listsWithoutAsset = array();
		
		foreach ($lists as $list)
		{
			$assetExists = false;
			
			foreach ($assets as $asset)
			{
				if ($asset->id == $list->asset_id)
				{
					$assetExists = true;
					break;
				}
			}
			
			if (!$assetExists) $listsWithoutAsset[] = $list;
		}
		
		if (count($listsWithoutAsset) > 0)
		{
			foreach ($listsWithoutAsset as $list)
			{
				$listAssetName = 'com_lightchecklist.list.'.$list->id;
				$title = $list->title;
				$rules = "";
				
				$asset = JTable::getInstance('Asset', 'JTable');
				$asset->name = $listAssetName;
				$asset->title = $title;
				$asset->rules = $rules;
				$asset->setLocation($componentRootAssetId, 'last-child');
				$asset->store();
			}
		}
	}
	//----------------------------------------------------------------------------------------------------
	private static function UpdateExistingAsset($assetName, $rules)
	{
		$rules = self::CleanRules($rules);
		$json = self::JsonEncodeRules($rules);
		
		$db = JFactory::getDBO();
		
		$query = "UPDATE `#__assets` SET" .
			" `rules` = " . $db->quote($json) .
			" WHERE `name` = " . $db->quote($assetName);
		$db->setQuery($query);
		$db->execute();
	}
	//----------------------------------------------------------------------------------------------------
	private static function GetComponentRootAssetId()
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT `id` FROM `#__assets`" .
			" WHERE `name` = " . $db->quote('com_lightchecklist');
		$db->setQuery($query);
		$componentRootAssetId = $db->loadResult();
		
		if (!isset($componentRootAssetId)) throw new Exception(JText::_('COM_LIGHTCHECKLIST_BE_NO_COMPONENT_ASSET'));
		
		return $componentRootAssetId;
	}
	//----------------------------------------------------------------------------------------------------
	private static function CleanRules($rulesArray)
	{
		// Removing 'Inherited' permissons.
		
		foreach ($rulesArray as $actionName => $permissions)
		{
			foreach ($permissions as $userGroupId => $permisson)
			{
				if ($permisson == '')
				{
					unset($rulesArray[$actionName][$userGroupId]);
				}
			}
		}
		
		return $rulesArray;
	}
	//----------------------------------------------------------------------------------------------------
	private static function JsonEncodeRules($rules)
	{
		$joomlaAccessRules = new JAccessRules($rules);
		
		return $joomlaAccessRules->__toString();
	}
}