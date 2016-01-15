<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');

$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

if($this->user->avatar_field != ''){
	if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$user->id.'/thm_'.$this->user->avatar_field)){
		$avatar_path = JURI::root().'images/checklist/avatar/'.$user->id.'/thm_'.$this->user->avatar_field;
	} else {
		$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
	}
} else {
	$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
}

?>
<style type="text/css">
	
	.alert
	{
		display: none;
		margin-right: 15px;
	}

	#profileForm *, #profileForm *:before, #profileForm *:after {
		-webkit-box-sizing: border-box !important;
	    -moz-box-sizing: border-box !important;
	    box-sizing: border-box !important;
	}

</style>
<div class="alert alert-success" id="alert-success"></div>
<div class="alert alert-danger" id="alert-danger"></div>

<link rel="stylesheet" type="text/css" href="<?php echo JURI::root()?>components/com_checklist/assets/css/profile.css">
<div class="chk-profile-form">
	<h2 class="chk-header-form"><?php echo JText::_('COM_CHECKLIST_EDIT_PROFILE');?></h2>
	<form class="form-horizontal" action="<?php echo JURI::root();?>index.php?option=com_checklist<?php echo $this->itemid;?>" role="form" method="post" enctype="multipart/form-data" target="" name="profileForm" id="profileForm">
	  <div class="form-group">
	    <label for="uname" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_USER_NAME_PROFILE');?></label>
	    <div class="col-sm-10">
	      <input type="text" name="uname" class="form-control" id="uname" placeholder="<?php echo JText::_('COM_CHECKLIST_USER_NAME_PLACEHOLDER_PROFILE');?>" value="<?php echo $this->user->name;?>">
	    </div>
	  </div>
	  
	  <div class="form-group">
		<label for="avatar" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_AVATAR_PROFILE');?></label>
	    <div class="col-sm-10">
			<input type="file" name="avatar_file" class="form-control" id="avatar" style="width:25%;">
			<button onclick="Checklist.uploadFile();" type="button" class="btn btn-primary btn-lg"><?php echo JText::_('COM_CHECKLIST_UPLOAD_PROFILE');?></button>
			<img src="<?php echo JURI::root();?>components/com_checklist/assets/images/ajax-loader.gif" style="display: none;" id="ajax_loader_profile">
	    </div>
	  </div>
	  <div class="form-group">
	     <img class="media-object chk-author-avatar" id="avatar-image" src="<?php echo $avatar_path; ?>" alt="">
	  </div>
	  <div class="form-group">
	    <label for="website" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_WEBSITE_URL_REGISTER');?></label>
	    <div class="col-sm-10">
	      <input type="text" name="website_field" class="form-control" id="website" placeholder="Website URL" value="<?php echo $this->user->website_field;?>">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="twitter" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_TWITTER_REGISTER');?></label>
	    <div class="col-sm-10">
	      <input type="text" name="twitter_field" class="form-control" id="twitter" placeholder="Twitter" value="<?php echo $this->user->twitter_field;?>">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="facebook" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_FACEBOOK_REGISTER');?></label>
	    <div class="col-sm-10">
	      <input type="text" name="facebook_field" class="form-control" id="facebook" placeholder="Facebook" value="<?php echo $this->user->facebook_field;?>">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="google" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_GOOGLE_PLUS_REGISTER');?></label>
	    <div class="col-sm-10">
	      <input type="text" name="google_field" class="form-control" id="google" placeholder="Google Plus" value="<?php echo $this->user->google_field;?>">
	    </div>
	  </div>
	  <div class="form-group">
	  	<label for="description" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_ABOUT_REGISTER');?></label>
	  	<div class="col-sm-10">
			<textarea class="form-control" id="description" name="description_field" rows="3"><?php echo $this->user->description_field; ?></textarea>
		</div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default"><?php echo JText::_('COM_CHECKLIST_SAVE_REGISTER');?></button>
	      <button class="btn btn-default" onclick="location.href='<?php echo JURI::root()?>index.php?option=com_checklist&view=users<?php echo $this->itemid;?>'; return false;"><?php echo JText::_('COM_CHECKLIST_CANCEL_REGISTER');?></button>
	    </div>
	  </div>
	  <input type="hidden" name="task" value="editprofile.save"/>
	  <input type="hidden" name="userid" value="<?php echo $user->id;?>"/>
	  
	</form>
</div>

<iframe src="javascript:void(0);" style="display:none;" id="_fileUpload" name="_fileUpload"></iframe>
<script type="text/javascript">

var form = document.profileForm;
	
var Checklist = {};

Checklist.uploadFile = function(){
		
	var filename = document.getElementById("avatar").value;

	if(filename != ''){
		form.target = "_fileUpload";
		form.task.value = "editprofile.uploadFile";

		form.submit();
		document.getElementById("ajax_loader_profile").style.display = "inline";
	} else {
		var alertText = document.createTextNode('<?php echo JText::_('COM_CHECKLIST_PLEASE_SELECT_AN_IMAGE');?>');
		document.getElementById('alert-danger').innerHTML = "";
		document.getElementById('alert-danger').appendChild(alertText);
		return false;
	}
}
</script>
