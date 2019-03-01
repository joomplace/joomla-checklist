<?php
/**
* Checklist Deluxe Component for Joomla 3
* @package Checklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');
 
/**
 * Main Controller
 */
class ChecklistControllerLists extends JControllerAdmin
{
        public function save(){
			
        	require_once(JPATH_SITE.'/administrator/components/com_checklist/models/configuration.php');
			$configurationModel = JModelLegacy::getInstance('Configuration', 'ChecklistModel');
			$config = $configurationModel->GetConfig();

			$user = JFactory::getUser();
			$db = JFactory::getDBO();

			$default = JFactory::getApplication()->input->get('chk_default');

			$default = (!$config->moderateChecklist) ? '1' : $default;
			$checklist_id = JFactory::getApplication()->input->get('checklist_id');
			$title = JFactory::getApplication()->input->getString('title');
			$alias = JFactory::getApplication()->input->getString('alias');

			$alias = ($alias!='') ? $alias : $title;
			$alias = str_replace(" ", "-", strtolower($alias));

			$description_before = JFactory::getApplication()->input->getRaw('description_before');
			$description_after = JFactory::getApplication()->input->getRaw('description_after');
			$publish_date = JFactory::getApplication()->input->getString('publish_date');

			if($publish_date == ''){
				$now = time();
				$publish_date = date('Y-m-d', $now);
			}

			$language = JFactory::getApplication()->input->getString('lg');
			$template = JFactory::getApplication()->input->get('template');
			$tags = JFactory::getApplication()->input->getString('tags');

			$tags_array = array();
			if($tags != ''){
				$tags_array = explode(",", $tags);
			}

			$list_access = JFactory::getApplication()->input->get('list_access');
			$comment_access = JFactory::getApplication()->input->get('comment_access');
			$meta_keywords = JFactory::getApplication()->input->getString('meta_keywords');
			$meta_description = JFactory::getApplication()->input->getString('meta_description');
			$cm_names = JFactory::getApplication()->input->get('cm_names', array(), 'array');
			$cm_values = JFactory::getApplication()->input->get('cm_values', array(), 'array');
			
			$metatags = '';
			$custom_metatags = array();
			if(count($cm_names)){
				foreach ($cm_names as $ii => $name) {
					$custom_metatags[$name] = $cm_values[$ii];
				}

				$metatags = json_encode($custom_metatags);
			}
			
			$row = new stdClass;
			$row->id = $checklist_id;
			$row->user_id = $user->id;
			$row->title = $title;
			$row->alias = $alias;
			$row->description_before = $description_before;
			$row->description_after = $description_after;
			$row->default = $default;
			$row->published = 1;
			$row->publish_date = $publish_date;
			$row->list_access = $list_access;
			$row->comment_access = $comment_access;
			$row->meta_keywords = strip_tags($meta_keywords);
			$row->meta_description = strip_tags($meta_description);
			$row->custom_metatags = $metatags;
			$row->language = $language;
			$row->template = $template;
						
			if(!$checklist_id){
				$db->insertObject("#__checklist_lists", $row, 'id');
				$row->id = $db->insertid();

				$html = '<a class="list-group-item" href="'.JURI::root().'index.php?option=com_checklist&view=checklist&id='.$row->id.'"><span checklistid="'.$row->id.'" class="chk-remove-chklist" style="visibility: hidden;"><img class="chk-delete-checklist" src="'.JURI::root().'components/com_checklist/assets/images/delete.png"></span><span checklistid="'.$row->id.'" class="chk-remove-chklist" style="visibility: hidden;"><img class="chk-edit-checklist" src="'.JURI::root().'components/com_checklist/assets/images/edit.png" onclick="Checklist.editChecklist(this);"></span><h4 class="list-group-item-heading">'.$row->title.'</h4><p class="list-group-item-text">'.substr(strip_tags($row->description_before), 0, 500).'</p></a>';

			} else {
				$db->updateObject("#__checklist_lists", $row, 'id');

				$html = substr(strip_tags($row->description_before), 0, 500);
			}
			
			if(count($tags_array) && $row->id){
				foreach ($tags_array as $tag) {

					$tag = trim($tag);
					$db->setQuery("SELECT `id` FROM `#__checklist_tags` WHERE `name` = '".$tag."'");
					$tag_id = $db->loadResult();

					$tag_obj = new stdClass;
					$tag_obj->id = ($tag_id) ? $tag_id : '';
					$tag_obj->name = $tag;
					$tag_obj->default = 0;
					$tag_obj->slug = '';

					if(!$tag_id){
						$db->insertObject('#__checklist_tags', $tag_obj, 'id');
						$tag_id = $db->insertid();
					}

					$db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `tag_id` = '".$tag_id."' AND `checklist_id` = '".$row->id."'");
					$db->execute();

					$object = new stdClass;
					$object->id = '';
					$object->checklist_id = $row->id;
					$object->tag_id = $tag_id;
					$object->isnew = 1;

					$db->insertObject('#__checklist_list_tags', $object, 'id');
				}
			}

			echo $html;
			die;			
		}
				
		public function remove(){

            $user = JFactory::getUser();
            if($user->authorise('core.delete', 'com_checklist')) {
                $db = JFactory::getDBO();
                $id = JFactory::getApplication()->input->getInt('id', 0);
                $db->setQuery("DELETE FROM `#__checklist_lists` WHERE `id` = '".$id."'");
                if($db->query()){

                    $db->setQuery("DELETE FROM `#__checklist_groups` WHERE `checklist_id` = '".$id."'");
                    $db->query();

                    $db->setQuery("DELETE FROM `#__checklist_items` WHERE `checklist_id` = '".$id."'");
                    $db->query();

                    //remove requests
                    $db->setQuery("DELETE FROM `#__checklist_requests` WHERE `checklist_id` = '".$id."'");
                    $db->query();

                    //remove tags assings
                    $db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `checklist_id` = '".$id."'");
                    $db->query();
                }
                echo 'success';
            }
            else {
                echo 'permission';
            }

			die;
		}
}
