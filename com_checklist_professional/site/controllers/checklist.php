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
 
class ChecklistControllerChecklist extends JControllerAdmin
{

		public function saverate(){

			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('idBox', '');
			$rate = JFactory::getApplication()->input->get('rate', '');

			$response = new stdClass;
			$response->error = false;

			$db->setQuery("INSERT INTO `#__checklist_rating` (`id`, `checklist_id`, `user_id`, `rate`) VALUES ('', '".$checklist_id."', '".$user->id."', '".$rate."')");
			if(!$db->execute()){
				$response->error = true;
			}

			$response->message = "Your rate has been successfuly recorded. Thanks for your rate";
			$response->server = "<strong>Success answer :<\/strong> Success : Your rate has been recorded. Thanks for your rate :)<br \/><strong>Rate received :<\/strong> 10<br \/><strong>ID to update :<\/strong> 6";

			$json_response = json_encode($response);

			echo $json_response;
			die;
		}

		public function pdf(){

			$config = new JConfig();
			defined(_PDF_GENERATED) or define('_PDF_GENERATED', JText::_('COM_CHECKLIST_PDF_GENERATED'));

			$user = JFactory::getUser();
			$db = JFactory::getDBO();
			$checklist_id = JFactory::getApplication()->input->get('checklist_id', '');

			chdir(JPATH_SITE);
		
			require_once(JPATH_SITE . '/components/com_checklist/assets/tcpdf/tcpdf.php');
			
			$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->setPrintHeader(false);
			$pdf->setPrintFooter(false);
			$pdf->SetMargins(20, 10, 25);
			
			// set font
			$pdf->AddPage();

			$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(242, 245, 250), 'strokeColor'=>array(68, 139, 207)));

			$db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$checklist = $db->loadObject();

            $fontFamily = 'dejavusans';

			$pdf->setFontSubsetting(false);
            $pdf->SetFont($fontFamily, 'B', 15, '', false);
			$pdf->Write(5, $pdf->cleanText($checklist->title), '', 0);
			$pdf->Ln();
			$pdf->Ln();

            $pdf->SetFont($fontFamily, '', 10, '', false);
			$pdf->Write(5, $pdf->cleanText($checklist->description_before), '', 0);
			$pdf->Ln();
			$pdf->Ln();

			$db->setQuery("SELECT * FROM `#__checklist_groups` WHERE `checklist_id` = '".$checklist_id."' ORDER BY `ordering`");
			$groups = $db->loadObjectList();

			if(count($groups)){
				foreach ($groups as $group) {

                    $pdf->SetFont($fontFamily, 'B', 12, '', false);
					$pdf->Write(5, $pdf->cleanText($group->title), '', 0);
					$pdf->Ln();
					$pdf->Ln();

					$db->setQuery("SELECT * FROM `#__checklist_items` WHERE `checklist_id` = '".$checklist_id."' AND `group_id` = '".$group->id."' ORDER BY `ordering`");
					$items = $db->loadObjectList();
					
					if(count($items)){
						foreach ($items as $ii => $item) {

                            $pdf->SetFont($fontFamily, 'U', 10, '', false);
							$pdf->CheckBox('task_'.$group->id.'_'.$item->id, 5, false, array(), array(), 'OK');

							$pdf->setTextColor(54, 145, 212);
							$pdf->Cell(35, 5, $item->task);
							$pdf->Ln();
							
							$pdf->setTextColor(0, 0, 0);
                            $pdf->SetFont($fontFamily, 'I', 8, '', false);
							$pdf->Write(5, $pdf->cleanText($item->tips), '', 0);
							$pdf->Ln();
							$pdf->Ln();
						}
					}

					
				}
			}

            $pdf->SetFont($fontFamily, '', 10, '', false);
			$pdf->Write(5, $pdf->cleanText($checklist->description_after), '', 0);
			$pdf->Ln();
			$pdf->Ln();

			$pdf->lastPage();

			$data = $pdf->Output('', 'S'); 
		
			@ob_end_clean();
			header("Content-type: application/pdf");
			header("Content-Length: ".strlen(ltrim($data)));
			header("Content-Disposition: attachment; filename=checklist.pdf");
			echo $data;
			die;
		}

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
			<h2 class="checklist-section-header">'.$title.'<span class="chk-add-item"><img style="cursor:pointer;" src="'.JURI::root().'components/com_checklist/assets/images/minus.png" class="chk-ajax-remove-group hasTooltip" title="'.JText::_('COM_CHECKLIST_REMOVE_BLOCK').'"/>&nbsp;<img style="cursor:pointer;" src="'.JURI::root().'components/com_checklist/assets/images/list_add.png" class="chk-open-item-form hasTooltip" title="'.JText::_('COM_CHECKLIST_ADD_TASK').'"/></span></h2>
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
			$template = JFactory::getApplication()->input->getString('template');

			require_once(JPATH_SITE.'/components/com_checklist/assets/markdown/Parsedown.php');
			$tips = Parsedown::instance()->parse($tips);
			
			$checklist_id = JFactory::getApplication()->input->get('id', '');
			if(!$this->checkAccess($checklist_id)) exit();
			
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
			
			if($template == 'default'){
				$class = ($optional) ? 'class="optional li-handle"' : 'class="li-handle"';

				$html = '<li '.$class.' itemid="'.$lastid.'"><input type="checkbox" tabindex="'.($max_ordering+1).'" id="'.$input_id.'" class="chk-checkbox"><label for="'.$input_id.'">'.$title.'<a id="details-'.$input_id.'" class="checklist-info-icon" data-parent="#chk-main" data-toggle="collapse"><span class="glyphicon glyphicon-info-sign"></span></a><img src="'.JURI::root().'components/com_checklist/assets/images/trash_recyclebin_empty_closed_w.png" class="chk-tools" onclick="Checklist.ajaxRemoveItem(this, event);"/><img src="'.JURI::root().'components/com_checklist/assets/images/pencil2.png" class="chk-tools pencil" onclick="Checklist.editItem(this, event);"/></label><ul class="panel-collapse collapse"><li>'.$tips.'</li></ul></li>';
			} else {
				$class = ($optional) ? 'class="optional li-handle"' : 'class="li-handle"';
				$html = '<li '.$class.' itemid="'.$lastid.'"><input type="checkbox" tabindex="'.($max_ordering+1).'" id="'.$input_id.'"><label for="'.$input_id.'">'.$title.'<img src="'.JURI::root().'components/com_checklist/assets/images/trash_recyclebin_empty_closed_w.png" class="chk-tools" onclick="Checklist.ajaxRemoveItem(this, event);"/><img src="'.JURI::root().'components/com_checklist/assets/images/pencil2.png" class="chk-tools pencil" onclick="Checklist.editItem(this, event);"/></label><em class="icon-info" id="details-'.$input_id.'"></em><ul style="max-height: 0px;" class="checklist-section-details"><li>'.$tips.'</li></ul></li>';
			}
			
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
			if(!$this->checkAccess($checklist_id)) exit();
			
			$groupid = JFactory::getApplication()->input->get('groupid');
			$itemid = JFactory::getApplication()->input->get('item');
			
			$db->setQuery("SELECT `task`, `tips`, `optional` FROM `#__checklist_items` WHERE `id` = '".$itemid."' AND `checklist_id` = '".$checklist_id."' AND `group_id` = '".$groupid."'");
			$data = $db->loadAssoc();
			
			$checked = ($data['optional']) ? 'checked="checked"' : '';
			
			$html = '<form class="form-horizontal chk-edititem-form" id="chk-edititem-form-'.$itemid.'" style="">
					<div class="chk-arrow"></div>
					<div class="form-group">
						<div class="col-sm-12" style="width:89%;">
							<input type="text" placeholder="Task name" id="inputItem_'.$itemid.'" class="form-control" value="'.str_replace("\"", "", $data['task']).'">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12" style="width:85%;">							
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

			$db = JFactory::getDBO();
			$user = JFactory::getUser();
			
			$db->setQuery("SELECT `user_id` FROM `#__checklist_lists` WHERE `id` = '".$checklist_id."'");
			$user_id = $db->loadResult();

			if($user_id != $user->id) return false;
			
			return true;
		}
		
		public function getEditor(){
			
			$groupid = JFactory::getApplication()->input->get('gid');
			
			echo '<div class="markdown-editor" id="tips_'.$groupid.'">';
			die;
		}

		public function switchEditMode()
		{
			$checklist_id = JFactory::getApplication()->input->getInt('id', 0);
			if(!$this->checkAccess($checklist_id)){
                return false;
            }

            $session = JFactory::getSession();
            $edit_mode = $session->get('edit_mode.'.$checklist_id, '');
			if(!$edit_mode){
                $session->set('edit_mode.'.$checklist_id, 1);
			}

			$sefUrl = JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist_id);
			echo $sefUrl;

			die;
		}

		public function switchViewMode()
		{
            $checklist_id = JFactory::getApplication()->input->getInt('id', 0);
            if(!$this->checkAccess($checklist_id)){
                return false;
            }

            $session = JFactory::getSession();
            $edit_mode = $session->get('edit_mode.'.$checklist_id, '');
			if($edit_mode){
                $session->clear('edit_mode.'.$checklist_id);
			}

			$sefUrl = JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist_id);
			echo $sefUrl;

			die;
		}

		public function sendRequest()
		{
			$user = JFactory::getUser();
			$db = JFactory::getDBO();

			require_once(JPATH_SITE.'/administrator/components/com_checklist/models/configuration.php');
			$configurationModel = JModelLegacy::getInstance('Configuration', 'ChecklistModel');
			$config = $configurationModel->GetConfig();

			$checklist_id = JFactory::getApplication()->input->get('id');
			if(!$this->checkAccess($checklist_id)) return false;

			if($config->useNotification && $config->emailsNotification!=''){
			
				$emails = explode(",", $config->emailsNotification);

				if (count($emails)){
	               
	            	$mailer = JFactory::getMailer();

	                $config = new JConfig();
	                $mailfrom = $config->mailfrom;
	                $sitename = $config->fromname;

					$sender = array($mailfrom, $sitename);
					$mailer->setSender($sender);
					
					foreach ($emails as &$email){
						$email = trim($email);
					}

					$request_url = JURI::root().'administrator/index.php?option=com_checklist&view=requests&checklist_id='.$checklist_id;
					$userName = $user->name;

					$mailer->addRecipient($emails);
					$body   = sprintf(JText::_('COM_CHECKLIST_REQUEST_EMAIL_BODY'), $userName, $request_url);
					$mailer->isHTML(true);
					$mailer->setSubject(JText::_('COM_CHECKLIST_REQUEST_EMAIL_SUBJECT'));
					$mailer->setBody($body);

	                $send = $mailer->Send();
					if ( $send !== true ) {
					    echo 'Error sending email: ' . $send->__toString();
					    die;

					}
					                              
	            } else {
	            	echo 'specified emails please!';
					die;
	            }
	        }

	        $db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_requests` WHERE `user_id` = '".$user->id."' AND `checklist_id` = '".$checklist_id."'");
	        $already_sent = $db->loadResult();

	        if(!$already_sent){
	        	$db->setQuery("INSERT INTO `#__checklist_requests` (`id`, `user_id`, `checklist_id`, `confirm`) VALUES ('', '".$user->id."', '".$checklist_id."', 0)");
	        	$db->execute();
	        }

	        echo 'success';
	        die;
		}
}
