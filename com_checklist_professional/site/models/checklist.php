<?php
/**
* Staticcontent component for Joomla 3.0
* @package Staticcontent
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelChecklist extends JModelList
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function checkAllowEdit($userid, $checklist_id)
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();

		if($user->id){

			$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$user_id = $db->loadResult();

			if($user_id == $user->id){
				return true;
			}

		} else if($userid && !$user->id){

			return false;

		}
	}

	public function getCheckedList($checklist_id)
	{

		$user = JFactory::getUser();
		$db = JFactory::getDBO();

		$db->setQuery("SELECT * FROM `#__checklist_users_item` WHERE `user_id` = '".$user->id."' AND `checklist_id` = '".$checklist_id."'");
		$checkedList = $db->loadObjectList();

		$chk_list = array();
		if(count($checkedList)){
			foreach($checkedList as $checked){
				$chk_list[$checked->item_id] = $checked->checked;
			}
		}

		return $chk_list;

	}

	public function getChecklistId()
	{
		$app = JFactory::getApplication();
		if($app->isAdmin()){
			$params = JComponentHelper::getParams('com_checklist');
		} else {
			$params = $app->getParams();
		}

		$id = $params->get('id', 0);
		return $id;
	}

	public function getChecklist(){
		
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$id = $app->input->get('id');

		if(!$id){
			$id = $this->getChecklistId();
		}

		$userid = JFactory::getApplication()->input->get('userid', 0);

		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$id."'");
		$userid = $db->loadResult();

		if(($user->id && $user->id != $userid) || !$user->id){

			$now_date = time();
			$groups = ChecklistHelper::getAllowGroups();

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `id` = '".$id."' AND UNIX_TIMESTAMP(`publish_date`) <= '".$now_date."' AND `list_access` IN (".implode(",", $groups).") AND `default` = 1");
			$checklist = $db->loadObject();

		} else {

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `id` = '".$id."'");
			$checklist = $db->loadObject();

		}

		if(!empty($checklist)){
			$db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_requests` WHERE `user_id` = '".$user->id."' AND `checklist_id` = '".$id."'");
	   		$already_sent = $db->loadResult();

	    	$checklist->already_sent = $already_sent;
	    }

	    if($user->id){
	    	$db->setQuery("SELECT COUNT(*) FROM `#__checklist_rating` WHERE `user_id` = '".$user->id."' AND `checklist_id` = '".$checklist->id."'");
	    	$checklist->rated = $db->loadResult();

	    	if($this->checkAllowEdit($user->id, $checklist->id)){
	    		$checklist->rated = true;
	    	}

	    } else {
	    	$checklist->rated = true;
	    }

	    $db->setQuery("SELECT AVG(`rate`) FROM `#__checklist_rating` WHERE `checklist_id` = '".$checklist->id."'");
	    $checklist->average_rating = $db->loadResult();

	    $db->setQuery("SELECT COUNT(`user_id`) FROM `#__checklist_rating` WHERE `checklist_id` = '".$checklist->id."'");
	    $checklist->rated_users = $db->loadResult();

		return $checklist;
		
	}
		
	public function getGroups(){
	
		$checklist_id = JFactory::getApplication()->input->get('id');
		if(!$checklist_id){
			$checklist_id = $this->getChecklistId();
		}

		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT * FROM `#__checklist_groups` WHERE `checklist_id` = '".$checklist_id."' ORDER BY `ordering`");
		$groups = $db->loadObjectList();
		
		if(count($groups)){
			foreach($groups as &$group){
				
				$group->section_id = str_replace(" ", "-", $group->title);
				
			}
		}
		
		return $groups;
	
	}
	
	public function getItems(){
	
		$checklist_id = JFactory::getApplication()->input->get('id');
		if(!$checklist_id){
			$checklist_id = $this->getChecklistId();
		}

		$db = JFactory::getDBO();
		
		$db->setQuery("SELECT * FROM `#__checklist_items` WHERE `checklist_id` = '".$checklist_id."' ORDER BY `ordering`, `optional`");
		$items = $db->loadObjectList();
		
		if(count($items)){
			foreach($items as &$item){
				
				$item->input_id = str_replace(" ", "", $item->task);
				$item->input_id = str_replace("'", "", $item->input_id);
				$item->input_id = str_replace("\"", "", $item->input_id);
				
				$item->label_for = $item->input_id;

				//added rel="nofollow" and target="_blank"
				$regexp = "/<a[^>]*>(.*)<\/a[^>]*>/";
				if(preg_match_all($regexp, $item->tips, $a_tags)){
					if(count($a_tags)){
						$hrefexp = '/href=["]([^"]*)/';
						foreach ($a_tags[0] as $key => $a) {
							$title = $a_tags[1][$key];
							if(preg_match($hrefexp, $a, $hrefs)){
								$href = $hrefs[1];
							}

							$replacement = '<a href="'.$href.'" rel="nofollow" target="_blank">'.$title.'</a>';
							$item->tips = str_replace($a, $replacement, $item->tips);
						}
					}
				}

				$item->tips = '<li>'.$item->tips.'</li>';
			}
		}
		
		return $items;
	
	}

	public function getTags($checklist_id)
	{
		$db = JFactory::getDBO();

		$db->setQuery("SELECT t.`name` FROM `#__checklist_tags` as t LEFT JOIN `#__checklist_list_tags` as l ON l.`tag_id` = t.`id` WHERE l.`checklist_id` = '".$checklist_id."'");
		$tags = $db->loadColumn();

		return $tags;
	}

	function getTemplateName($template_id)
	{
		$db = JFactory::getDBO();

		$db->setQuery("SELECT `name` FROM `#__checklist_templates` WHERE `id` = '".$template_id."'");
		$templateName = $db->loadResult();

		return $templateName;
	}

}
