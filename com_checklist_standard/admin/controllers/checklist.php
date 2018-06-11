<?php
/**
* Checklist Deluxe Component for Joomla 3
* @package Checklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');
 
/**
 * Main Controller
 */
 
class ChecklistControllerChecklist extends JControllerForm
{
        public function addgroup(){
			
			$db = JFactory::getDBO();
			$title = JFactory::getApplication()->input->getString('title');
			$id = JFactory::getApplication()->input->get('id', '');
			if(!$this->checkAccess($id)) exit();
			
			$db->setQuery("SELECT MAX(ordering) FROM `#__checklist_groups` WHERE `checklist_id` = '".$id."'");
			$max_ordering = $db->loadResult();
			
			$max_ordering++;
			$db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES ('', '".$id."', ".$db->quote($title).", '".$max_ordering."')");
			$db->query();
			$lastid = $db->insertid();
			
			$section_id = str_replace(" ", "-", $title);
			
			$html = '<section id="'.$section_id.'" class="checklist-section" groupid="'.$lastid.'">
			<h2 class="checklist-section-header">'.$title.'<span class="chk-add-item"><img style="cursor:pointer;" src="'.JURI::root().'components/com_checklist/assets/images/minus.png" class="chk-ajax-remove-group" />&nbsp;<img style="cursor:pointer;" src="'.JURI::root().'components/com_checklist/assets/images/list_add.png" class="chk-open-item-form" /></span></h2>
			<div class="chk-add-item-form">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-12">
							<input type="text" placeholder="Task name" id="inputItem'.$lastid.'" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<textarea placeholder="Tips" id="inputTips'.$lastid.'" rows="3" class="form-control"></textarea>
						</div>
					</div>
                    <div style="margin:5px;" class="checklist-group-tools">
                        <div>
                            <a href="javascript: void(0);" gid="'.$lastid.'" class="chr-dialog-window">Edit with Markdown Editor</a>&nbsp;<img src="'.JURI::root().'components/com_checklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader-editor'.$lastid.'"/>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="">
                                    <input type="checkbox" id="inputOptional'.$lastid.'" value="1" class="chk-optional"> Optional
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-12">
                                <button groupid="'.$lastid.'" class="btn btn-sm chk-save-item" type="button">Save</button>
                                <button class="btn btn-sm chk-close-item-form" type="button">Cancel</button>
                                <img id="ajax-loader'.$lastid.'" style="display:none;" src="'.JURI::root().'components/com_checklist/assets/images/ajax-loader.gif">
                            </div>
                        </div>
                    </div>
				</form>
			</div>
			
			<ul class="checklist-section-list"></ul>
			</section>';
			
			echo $html;
			die;			
		}
		
		public function additem(){
			
			$db = JFactory::getDBO();
			
			$title = JFactory::getApplication()->input->getString('title');
			$tips = JFactory::getApplication()->input->get('tips', '', 'raw');
						
			require_once(JPATH_SITE.'/components/com_checklist/assets/markdown/Parsedown.php');
			$tips = Parsedown::instance()->parse($tips);
			
			$checklist_id = JFactory::getApplication()->input->get('id', '');
						
			$groupid = JFactory::getApplication()->input->get('groupid', '');
			$optional = JFactory::getApplication()->input->get('optional', '');
			
			$db->setQuery("SELECT MAX(ordering) FROM `#__checklist_items` WHERE `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."'");
			$max_ordering = $db->loadResult();
			
			$max_ordering++;
			
			$db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`, `checked`) VALUES ('', '".$checklist_id."', '".$groupid."', ".$db->quote($title).", ".$db->quote($tips).", '".$optional."', '".$max_ordering."', '0')");
			$db->query();
			$lastid = $db->insertid();
			
			$input_id = str_replace(" ", "", $title);
			$input_id = str_replace("'", "", $input_id);
			$input_id = str_replace("\"", "", $input_id);
			
			$class = ($optional) ? 'class="optional li-handle"' : 'class="li-handle"';
			
			$html = '<li '.$class.' itemid="'.$lastid.'"><input type="checkbox" tabindex="'.($max_ordering+1).'" id="'.$input_id.'"><label for="'.$input_id.'">'.$title.'<img src="'.JURI::root().'components/com_checklist/assets/images/trash_recyclebin_empty_closed_w.png" class="chk-tools" onclick="Checklist.ajaxRemoveItem(this, event);"/><img src="'.JURI::root().'components/com_checklist/assets/images/pencil2.png" class="chk-tools pencil" onclick="Checklist.editItem(this, event);"/></label><em class="" id="details-'.$input_id.'"></em><ul style="max-height: 0px;" class="checklist-section-details"><li>'.$tips.'</li></ul></li>';
			
			echo $html;
			die;
		}
		
		public function removegroup(){
			
			$db = JFactory::getDBO();
			
			$checklist_id = JFactory::getApplication()->input->get('id', '');
			if(!$this->checkAccess($checklist_id)) exit();
			
			$groupid = JFactory::getApplication()->input->get('groupid', '');
			
			$db->setQuery("DELETE FROM `#__checklist_groups` WHERE `id` = '".$groupid."' AND `checklist_id` = '".$checklist_id."'");
			$db->query();
			
			$db->setQuery("DELETE FROM `#__checklist_items` WHERE `group_id` = '".$groupid."' AND `checklist_id` = '".$checklist_id."'");
			$db->query();

			die;
		}
		
		public function reordergroups(){
			
			$db = JFactory::getDBO();
						
			$groupids = $_REQUEST['groupids'];
			$groupids = json_decode($groupids);
			
			$checklist_id = JFactory::getApplication()->input->get('id');
			if(!$this->checkAccess($checklist_id)) exit();
			
			if(count($groupids)){
				foreach($groupids as $ordering => $group_id){
					$query = "UPDATE `#__checklist_groups` SET `ordering` = '".$ordering."' WHERE `checklist_id` = '".$checklist_id."' AND `id` = '".$group_id."'";
					$db->setQuery($query);
					$db->query();
				}
			}
			
			die;
		}
		
		public function reorderitems(){
			
			$db = JFactory::getDBO();
						
			$itemids = $_REQUEST['itemids'];
			$itemids = json_decode($itemids);
			
			$checklist_id = JFactory::getApplication()->input->get('id');
			if(!$this->checkAccess($checklist_id)) exit();
			
			$groupid = JFactory::getApplication()->input->get('groupid');
			
			if(count($itemids)){
				foreach($itemids as $ordering => $itemid){
					$query = "UPDATE `#__checklist_items` SET `ordering` = '".$ordering."' WHERE `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."' AND `id` = '".$itemid."'";
					$db->setQuery($query);
					$db->query();
				}
			}
			
			die;
			
		}
		
		public function checkeditem(){
			
			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('id');
						
			$groupid = JFactory::getApplication()->input->get('groupid');
			$itemid = JFactory::getApplication()->input->get('item');
			$checked = JFactory::getApplication()->input->get('checked');
			
			$db->setQuery("SELECT COUNT(id) FROM `#__checklist_users_item` WHERE `item_id` = '".$itemid."' AND `user_id` = '".$user->id."' AND `checklist_id` = '".$checklist_id."'");
			$exists = $db->loadResult();

			if(!$exists){
				$db->setQuery("INSERT INTO `#__checklist_users_item` (`id`, `user_id`, `item_id`, `checklist_id`, `checked`) VALUES ('', '".$user->id."', '".$itemid."', '".$checklist_id."', '".$checked."')");
				$db->execute();
			} else {
				$db->setQuery("UPDATE `#__checklist_users_item` SET `checked` = '".$checked."' WHERE `checklist_id` = '".$checklist_id."' AND `user_id` = '".$user->id."' AND `item_id` = '".$itemid."'");
				$db->execute();
			}

			die;			
		}

		public function resetchecked()
		{
			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('id');

			$db->setQuery("UPDATE `#__checklist_users_item` SET `checked` = '0' WHERE `checklist_id` = '".$checklist_id."' AND `user_id` = '".$user->id."'");
			$db->execute();

			die;
		}
		
		public function removeitem(){
			
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('id');
			if(!$this->checkAccess($checklist_id)) exit();
			
			$groupid = JFactory::getApplication()->input->get('groupid');
			$itemid = JFactory::getApplication()->input->get('item');
			
			$db->setQuery("DELETE FROM `#__checklist_items` WHERE `id` = '".$itemid."' AND `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."'");
			$db->query();
			die;			
		}
		
		public function getitem(){
		
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('id');
						
			$groupid = JFactory::getApplication()->input->get('groupid');
			$itemid = JFactory::getApplication()->input->get('item');
			
			$db->setQuery("SELECT `task`, `tips`, `optional` FROM `#__checklist_items` WHERE `id` = '".$itemid."' AND `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."'");
			$data = $db->loadAssoc();
			
			$checked = ($data['optional']) ? 'checked="checked"' : '';
			
			$html = '<form class="form-horizontal chk-edititem-form" id="chk-edititem-form-'.$itemid.'" style="">
					<div class="chk-arrow"></div>
					<div class="form-group">
						<div class="col-sm-12">
							<input type="text" placeholder="Task name" id="inputItem_'.$itemid.'" class="form-control" value="'.str_replace("\"", "", $data['task']).'">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">							
							<textarea id="inputTips_'.$itemid.'" rows="3" class="form-control">'.$data['tips'].'</textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<label for="inputOptional_'.$itemid.'">
								<input type="checkbox" id="inputOptional_'.$itemid.'" value="1" class="chk-optional" '.$checked.'> Optional
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-12">
							<button class="btn btn-sm" type="button" groupid="'.$groupid.'" itemid="'.$itemid.'" onclick="Checklist.ajaxChangedItem(this);">Save</button>
							<button class="btn btn-sm" type="button" onclick="Checklist.closeEditItemForm('.$itemid.')">Cancel</button>
							<br/><img id="ajax_loader_'.$itemid.'" style="display: none;" src="'.JURI::root().'components/com_checklist/assets/images/ajax-loader.gif">
						</div>
					</div>
				</form>';
			
			echo $html;
			die;
		}
		
		public function ajaxsaveitem(){
			
			$db = JFactory::getDBO();
			
			$title = JFactory::getApplication()->input->getString('title');
			$tips = JFactory::getApplication()->input->get('tips', '', 'raw');
						
			require_once(JPATH_SITE.'/components/com_checklist/assets/markdown/Parsedown.php');
			$tips = Parsedown::instance()->parse($tips);
			
			$checklist_id = JFactory::getApplication()->input->get('id');
			if(!$this->checkAccess($checklist_id)) exit();
			
			$optional = JFactory::getApplication()->input->get('optional');
			$groupid = JFactory::getApplication()->input->get('groupid');
			$itemid = JFactory::getApplication()->input->get('item');
			
			$db->setQuery("UPDATE `#__checklist_items` SET `task` = ".$db->quote($title).", `tips` = ".$db->quote($tips).", `optional` = '".$optional."' WHERE `id` = '".$itemid."' AND `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."'");
			$db->query();
			
			$json_array = array();
			$json_array['title'] = $title;
			$json_array['tips'] = '<li>'.$tips.'</li>';
			$json_array['groupid'] = $groupid;
			$json_array['itemid'] = $itemid;
			$json_array['optional'] = $optional;
			
			$json = json_encode($json_array);
			echo $json;
			die;
		}
		
		public function checkAccess($checklist_id){
			
			return true;
		}
		
		public function getEditor(){
			
			$groupid = JFactory::getApplication()->input->get('gid');
			
			echo '<div class="markdown-editor" id="tips_'.$groupid.'">';
			die;
		}
}
