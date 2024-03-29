<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class ChecklistControllerTags extends JControllerAdmin
{

	protected $text_prefix = 'COM_CHECKLIST_TAG';
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getModel($name = 'Tags', $prefix = 'ChecklistModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	public function save()
	{
		$db = JFactory::getDBO();
		$tag_id = JFactory::getApplication()->input->get('id', 0);
		$name = JFactory::getApplication()->input->getString('name');

		$db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_tags` WHERE `id` = '".$tag_id."'");
		$exists = $db->loadResult();

		$db->setQuery("SELECT `id` FROM `#__checklist_tags` WHERE `name` = '".$name."'");
		$double_row = $db->loadResult();

		if(!$exists){
			if(!$double_row){
				$db->setQuery("INSERT INTO `#__checklist_tags` (`id`, `name`, `default`, `slug`) VALUES ('', '".$name."', 0, '')");
				$db->execute();
			}
		} else {
			if(!$double_row){
				$db->setQuery("UPDATE `#__checklist_tags` SET `name` = '".$name."' WHERE `id` = '".$tag_id."'");
				$db->execute();
			}
		}

		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_checklist&view=tags');

		return true;
	}

	public function addChecklist()
	{
		$db = JFactory::getDBO();

		$tag_id = JFactory::getApplication()->input->get('tag_id', 0);
		$checklist_id = JFactory::getApplication()->input->get('checklist_id', 0);

		$db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_list_tags` WHERE `checklist_id` = '".$checklist_id."' AND `tag_id` = '".$tag_id."'");
		$exists = $db->loadResult();

		if(!$exists){
			$db->setQuery("INSERT INTO `#__checklist_list_tags` (`id`, `checklist_id`, `tag_id`, `isnew`) VALUES ('', '".$checklist_id."', '".$tag_id."', 1)");
			$db->execute();

			echo "success";
		} else {
			echo "exists";
		}

		die;
	}

	public function removeChecklist()
	{
		$db = JFactory::getDBO();

		$tag_id = JFactory::getApplication()->input->get('tag_id', 0);
		$checklist_id = JFactory::getApplication()->input->get('checklist_id', 0);

		$db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `checklist_id` = '".$checklist_id."' AND `tag_id` = '".$tag_id."'");
		$db->execute();

		echo "success";
		die;
	}
}
