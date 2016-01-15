<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelEdit_checklist extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getListData(){
		
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		
		$now = time();

		$checklist_id = JFactory::getApplication()->input->get('checklist_id', 0);
		$list_data = new stdClass;
		$list_data->id = '';
		$list_data->user_id = $user->id;
		$list_data->title = '';
		$list_data->alias = '';
		$list_data->description_before = '';
		$list_data->description_after = '';
		$list_data->default = 0;
		$list_data->published = 1;
		$list_data->publish_date = date('Y-m-d', $now);
		$list_data->list_access = 0;
		$list_data->comment_access = 0;
		$list_data->meta_keywords = '';
		$list_data->meta_description = '';
		$list_data->language = 'en-GB';
		$list_data->template = 2;

		if($checklist_id){

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
			$list_data = $db->loadObject();

		}

		return $list_data;
	}

	public function getGroupsList($access)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$checklist_id = JFactory::getApplication()->input->get('checklist_id', 0);

		if($access == 'list'){
			$db->setQuery("SELECT `list_access` FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
			$access_group = $db->loadResult();
		} else if($access == 'comment'){
			$db->setQuery("SELECT `comment_access` FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
			$access_group = $db->loadResult();
		}

		$db->setQuery("SELECT `id` as `value`, `title` as `text` FROM `#__usergroups` WHERE `title` <> 'Administrator' AND `title` <> 'Super Users'");
		$groups = $db->loadObjectList();

		$option = array();
		$option[] = JHTML::_('select.option', '-'.$user->id, JText::_('COM_CHECKLIST_OWNER'));
		$groups = array_merge($groups, $option);

		if($access == 'list'){
			return JHTML::_('select.genericlist', $groups, 'list_access', 'class="text_area" size="1" ', 'value', 'text', $access_group );
		} else if($access == 'comment'){
			return JHTML::_('select.genericlist', $groups, 'comment_access', 'class="text_area" size="1" ', 'value', 'text', $access_group );
		}
	}

	public function getMetadata()
	{

		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$checklist_id = JFactory::getApplication()->input->get('checklist_id', 0);

		$db->setQuery("SELECT `title` FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
		$title = $db->loadResult();

		$db->setQuery("SELECT `description_before` FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
		$description_before = $db->loadResult();

		$db->setQuery("SELECT `custom_metatags` FROM `#__checklist_lists` WHERE `user_id` = '".$user->id."' AND `id` = '".$checklist_id."'");
		$custom_metatags = $db->loadResult();

		$meta_array = ($custom_metatags != '') ? json_decode($custom_metatags) : new stdClass;
		
		// og tags
		if (empty($meta_array->{'og:description'}))
			$meta_array->{'og:description'} = substr(strip_tags($description_before), 0, 70);

		if (empty($meta_array->{'og:title'}))
			$meta_array->{'og:title'} = substr($title, 0, 70);

		// twitter tags
		if (empty($meta_array->{'twitter:description'}))
			$meta_array->{'twitter:description'} = substr(strip_tags($description_before), 0, 70);

		if (empty($meta_array->{'twitter:title'}))
			$meta_array->{'twitter:title'} = substr($title, 0, 70);

		return $meta_array;
	}

	public function getTags($checklist_id)
	{

		$db = JFactory::getDBO();

		$tags = array();
		if($checklist_id != 0){

			$query = "SELECT b.`name` FROM `#__checklist_list_tags` AS a, `#__checklist_tags` AS b "
				. "WHERE b.`id` = a.`tag_id` AND a.`checklist_id` = '{$checklist_id}' ";
			$db->setQuery($query);

			$tags = $db->loadColumn();
		}

		return $tags;
	}

	public function getLangs($checklist_id)
	{
		$db = JFactory::getDBO();
		if($checklist_id){
			$db->setQuery("SELECT `language` FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$selected_lang = $db->loadResult();
		} else {
			$selected_lang = 'en-GB';
		}

		$db->setQuery("SELECT `lang_code` as `value`, `title` as `text` FROM `#__languages`");
		$languages = $db->loadObjectList();

		$options = array();
		$options[] = JHtml::_('select.option', '*', JText::_('JALL'));
		$languages = array_merge($options, $languages);

		$langs = JHtml::_('select.genericlist', $languages, 'language', 'class="text_area" size="1" ', 'value', 'text', $selected_lang );
		
		return $langs;
	}

	public function getTemplates($template_id)
	{
		$db = JFactory::getDBO();
		$db->setQuery("SELECT `id` as `value`, `name` as `text` FROM `#__checklist_templates`");
		$template_list = $db->loadObjectList();

		$templates = JHtml::_('select.genericlist', $template_list, 'template', 'class="text_area" size="1" ', 'value', 'text', $template_id );
		
		return $templates;
	}

}
