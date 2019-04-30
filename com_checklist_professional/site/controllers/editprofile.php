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
class ChecklistControllerEditprofile extends JControllerAdmin
{
        public function save(){

			$user = JFactory::getUser();
			$db = JFactory::getDBO();

			$uname = JFactory::getApplication()->input->getString('uname');
			$website_field = JFactory::getApplication()->input->getString('website_field');
			$twitter_field = JFactory::getApplication()->input->getString('twitter_field');
			$facebook_field = JFactory::getApplication()->input->getString('facebook_field');
			$google_field = JFactory::getApplication()->input->getString('google_field');
			$description_field = JFactory::getApplication()->input->getString('description_field');

			$db->setQuery("UPDATE `#__users` SET `name` = '".$uname."' WHERE `id` = '".$user->id."'");
			$db->execute();

			$user_data = new stdClass;
			$user_data->user_id = $user->id;
			$user_data->website_field = $website_field;
			$user_data->twitter_field = $twitter_field;
			$user_data->facebook_field = $facebook_field;
			$user_data->google_field = $google_field;
			$user_data->description_field = $description_field;

			$db->setQuery("SELECT COUNT(user_id) FROM `#__checklist_users` WHERE `user_id` = '".$user->id."'");
			$exists = $db->loadResult();

			if(!$exists){
				$db->insertObject('#__checklist_users', $user_data, 'user_id');
			} else {
				$db->updateObject('#__checklist_users', $user_data, 'user_id');
			}

            $itemId = $this->input->getInt('Itemid', 0);
            $append = $itemId ? '&Itemid=' . $itemId : '';
			$this->setRedirect(JRoute::_(JURI::root().'index.php?option=com_checklist&view=users' . $append, false), 'Profile successfully saved');
			return;
		}

		public function uploadFile()
		{
			$db = JFactory::getDBO();

			jimport('joomla.filesystem.path');
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');

			$userid = JFactory::getApplication()->input->get("userid");
            $avatar_file = JFactory::getApplication()->input->files->get('avatar_file', array(), 'array');
			$avatar_filename = $avatar_file['name'];
			$avatar_file_tmpname = $avatar_file['tmp_name'];

			$message = '';
			$base_Dir = JPATH_SITE."/images/checklist/avatar/";
			if(!JFolder::exists($base_Dir.$userid)){
				JFolder::create($base_Dir.$userid, 755);
			}

			if (empty($avatar_filename)) {
				$message = JText::_('COM_CHECKLIST_PLEASE_SELECT_AN_IMAGE');
			}
					
					
			if (JFile::exists($base_Dir.$userid.'/'.$avatar_filename)) {
				$message = JText::_('COM_CHECKLIST_IMAGE').$avatar_filename.JText::_('COM_CHECKLIST_ALREADY_EXISTS');
			}
		
			if ((strcasecmp(substr($avatar_filename,-4),".gif")) && (strcasecmp(substr($avatar_filename,-4),".jpg")) && (strcasecmp(substr($avatar_filename,-4),".png")) && (strcasecmp(substr($avatar_filename,-4),".bmp")) ) {
				$message = JText::_('COM_CHECKLIST_ACCEPTED_FILES');
			}

			if($message != ''){

				echo "<script type='text/javascript'>";
				echo "var alertText = document.createTextNode('".$message."');"."\n";
				echo "parent.document.getElementById('alert-danger').innerHTML='';";
				echo "parent.document.getElementById('alert-danger').appendChild(alertText);
				parent.document.getElementById('alert-danger').style.display = 'block';
				parent.document.getElementById('ajax_loader_profile').style.display = 'none';
				parent.form.target = '';
				parent.form.task.value = 'editprofile.save';
				</script>";
				die;
			}

			$error = false;
			if (!JFile::move($avatar_file_tmpname, $base_Dir.$userid.'/'.$avatar_filename) || !JPath::setPermissions($base_Dir.$userid.'/'.$avatar_filename)) {
				$message = JText::_('COM_CHECKLIST_UPLOAD_OF').$avatar_filename.JText::_('COM_CHECKLIST_FAILED');
				$error = true;
			} else {
				$message = JText::_('COM_CHECKLIST_UPLOAD_OF')." ".$avatar_filename.JText::_('COM_CHECKLIST_SUCCESSFUL');
			}

			if($error){
				$idAlert = 'alert-danger';
			} else {
				$idAlert = 'alert-success';

				$db->setQuery("UPDATE `#__checklist_users` SET `avatar_field` = '".$avatar_filename."' WHERE `user_id` = '".$userid."'");
				$db->execute();
			}

			$original_filename = $base_Dir.$userid.'/'.$avatar_filename;

			$image = new JImage( $original_filename );
			$image->cropResize( 100, 100, false );

			$croped_filename = $base_Dir.$userid.'/thm_'.$avatar_filename;
			$image->toFile( $croped_filename );

			echo "<script type='text/javascript'>";
			echo "var alertText = document.createTextNode('".$message."');"."\n";
			
			echo "parent.document.getElementById('alert-danger').innerHTML='';"."\n";
			echo "parent.document.getElementById('alert-danger').style.display = 'none';"."\n";

			echo "parent.document.getElementById('alert-success').innerHTML='';"."\n";
			echo "parent.document.getElementById('alert-success').style.display = 'none';"."\n";

			echo "parent.document.getElementById('".$idAlert."').appendChild(alertText);
				parent.document.getElementById('".$idAlert."').style.display = 'block';
				parent.document.getElementById('ajax_loader_profile').style.display = 'none';"."\n";
			echo "parent.document.getElementById('avatar-image').src = '".JURI::root()."images/checklist/avatar/".$userid.'/thm_'.$avatar_filename."'"."\n";

			echo "parent.form.target = '';
				parent.form.task.value = 'editprofile.save';
				</script>";
			die;

		}
}
