<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

if($this->avatar_field != ''){
	if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->userid.'/thm_'.$this->avatar_field)){
		$avatar_path = JURI::root().'images/checklist/avatar/'.$this->userid.'/thm_'.$this->avatar_field;
	} else {
		$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
	}
} else {
	$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
}

$css = "*, *:before, *:after {
	    -moz-box-sizing: inherit !important;
	}

	.checklist-user-avatar, .checklist-user-avatar:before, .checklist-user-avatar:after {
	    -moz-box-sizing: box-sizing !important;
	}

	.checklist-inline
	{
		display: inline-block;
		margin:15px;
	}

	.chk-upload
	{
		vertical-align: top;
	}

	.checklist-signin-button
	{
		margin-left:30px;
	}";

$document->addStyleDeclaration($css);

?>

<div class="alert alert-success" id="alert-success"></div>
<div class="alert alert-danger" id="alert-danger"></div>

<form class="form-horizontal" action="<?php echo JURI::root();?>index.php?option=com_checklist" role="form" method="post" enctype="multipart/form-data" target="" name="profileForm">

<div class="checklist-user-avatar">
	<h2 class="chk-header-form"><?php echo JText::_('COM_CHECKLIST_USER_AVATAR')?></h2>
	<div class="checklist-inline">
		<img src="<?php echo $avatar_path;?>" class="img-thumbnail" id="avatar-image">
	</div>
	<div class="checklist-inline chk-upload">

		<input type="file" name="avatar_file" class="form-control" id="avatar">
		<button onclick="Checklist.uploadFile();" type="button" class="btn btn-primary btn-lg">Upload</button>
		<img src="<?php echo JURI::root();?>components/com_checklist/assets/images/ajax-loader.gif" style="display: none;" id="ajax_loader_profile">

	</div>
	<div class="form-group">
	    <div class="checklist-signin-button">
	      <button type="button" class="btn btn-default" onclick="location.href='<?php echo JRoute::_('index.php?option=com_users&view=login');?>'; return false;">Sign in</button>
	    </div>
	  </div>
</div>

<input type="hidden" name="task" value=""/>
<input type="hidden" name="userid" value="<?php echo $this->userid?>" />
</form>

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
