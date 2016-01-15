<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

if($this->avatar_field != ''){
	if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->item->id.'/thm_'.$this->avatar_field)){
		$avatar_path = JURI::root().'images/checklist/avatar/'.$this->item->id.'/thm_'.$this->avatar_field;
	} else {
		$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
	}
} else {
	$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
}


?>
<script type="text/javascript">

Joomla.submitbutton = function(task)
	{
		if (task == 'user.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

</script>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_checklist&layout=edit&user_id='.(int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="user-form" class="form-validate">

	<div id="j-main-container" class="span10 form-horizontal">
	<ul class="nav nav-tabs" id="userTabs">
	    <li class="active"><a href="#user-details" data-toggle="tab"><?php echo  JText::_('COM_CHECKLIST_USER_DETAILS');?></a></li>
	    <li><a href="#user-info" data-toggle="tab"><?php echo  JText::_('COM_CHECKLIST_USER_INFO');?></a></li>
	</ul>
	<div class="tab-content">
	    <div class="tab-pane active" id="user-details">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <label class=" required control-label" for="jform_avatar" id="jform_name-lbl"><?php echo JText::_('COM_CHECKLIST_USER_AVATAR')?></label>
	                </div>
					<div class="controls">
						<input type="file" name="avatar_file" class="btn" id="avatar">
						<button onclick="Checklist.uploadFile();" type="button" class="btn btn-primary btn-lg"><?php echo JText::_('COM_CHECKLIST_UPLOAD_PROFILE');?></button>
						<img src="<?php echo JURI::root();?>components/com_checklist/assets/images/ajax-loader.gif" style="display: none;" id="ajax_loader_profile">
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
						<label class=" required control-label"></label>
	                </div>
	                <div class="controls">
						<img class="media-object chk-author-avatar" id="avatar-image" src="<?php echo $avatar_path; ?>" alt="">
	                </div>
	            </div>
				<div class="control-group">
	                <div class="control-label">
					    <label class=" required control-label" for="jform_name" id="jform_name-lbl"><?php echo JText::_('COM_CHECKLIST_UNAME')?><span class="star">&nbsp;*</span></label>
	                </div>
					<div class="controls">
						<input type="text" aria-required="true" required="" size="30" value="<?php echo $this->item->name?>" id="jform_name" name="jform[name]" class="" aria-invalid="false">
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <label class=" required control-label" for="jform_username" id="jform_username-lbl"><?php echo JText::_('COM_CHECKLIST_USERNAME')?><span class="star">&nbsp;*</span></label>
	                </div>
					<div class="controls">
						<input type="text" aria-required="true" required="" size="30" value="<?php echo $this->item->username?>" id="jform_username" name="jform[username]" class="" aria-invalid="false">
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <label class=" required control-label" for="jform_email" id="jform_email-lbl"><?php echo JText::_('COM_CHECKLIST_USER_EMAIL')?><span class="star">&nbsp;*</span></label>
	                </div>
					<div class="controls">
						<input type="text" aria-required="true" required="" size="30" value="<?php echo $this->item->email?>" id="jform_email" name="jform[email]" class="" aria-invalid="false">
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    
	                </div>
					<div class="controls">
						
					</div>
				</div>
			</fieldset>
		</div>
		<div class="tab-pane" id="user-info">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('website_field'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('website_field'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('twitter_field'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('twitter_field'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
					    <?php echo $this->form->getLabel('facebook_field'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('facebook_field'); ?>
					</div>
		        </div>
		        <div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('google_field'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('google_field'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('description_field'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('description_field'); ?>
					</div>
				</div>
			</fieldset>
		</div>
			    
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('user_id'); ?>
	<?php echo JHtml::_('form.token'); ?>	
	</div>
</form>
<iframe src="javascript:void(0);" style="display:none;" id="_fileUpload" name="_fileUpload"></iframe>

<script type="text/javascript">

var form = document.adminForm;
	
var Checklist = {};

Checklist.uploadFile = function(){
		
	var filename = document.getElementById("avatar").value;

	if(filename != ''){
		form.target = "_fileUpload";
		form.task.value = "editprofile.uploadFile";

		form.submit();
		document.getElementById("ajax_loader_profile").style.display = "inline";
	} else {
		alert('<?php echo JText::_('COM_CHECKLIST_PLEASE_SELECT_AN_IMAGE');?>');
		return false;
	}
}
</script>
