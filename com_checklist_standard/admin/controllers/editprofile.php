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
 * Upload Controller
 */
class ChecklistControllerEditprofile extends JControllerForm
{
		public function uploadFile()
		{
			$db = JFactory::getDBO();

			jimport('joomla.filesystem.path');
			jimport('joomla.filesystem.file');
			jimport('joomla.filesystem.folder');

			$post = JRequest::get("post");
			$userid = $post['jform']['user_id'];

			$avatar_file = $_FILES['avatar_file'];
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
				echo "alert('".$message."');"."\n";
				echo "parent.document.getElementById('ajax_loader_profile').style.display = 'none';
				parent.form.target = '';
				parent.form.task.value = '';
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
			echo "alert('".$message."');"."\n";
			
			echo "parent.document.getElementById('ajax_loader_profile').style.display = 'none';"."\n";
			echo "parent.document.getElementById('avatar-image').src = '".JURI::root()."images/checklist/avatar/".$userid.'/thm_'.$avatar_filename."'"."\n";

			echo "parent.form.target = '';
				parent.form.task.value = '';
				</script>";
			die;

		}
}
