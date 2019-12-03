<?php
/**
* Staticcontent component for Joomla 3.0
* @package Staticcontent
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('viewport'=>'body'));

$user = JFactory::getUser();

if(!$user->id){
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

?>
<style type="text/css">
	
	*, *:before, *:after {
	    -moz-box-sizing: inherit !important;
	    box-sizing: inherit !important;
	}

	.chk-profile-form, .chk-profile-form:before, .chk-profile-form:after {
	    -moz-box-sizing: border-box !important;
	    box-sizing: border-box !important;
	}

</style>
<div class="alert alert-success" id="alert-success"></div>
<div class="alert alert-danger" id="alert-danger"></div>

<link rel="stylesheet" type="text/css" href="<?php echo JURI::root()?>components/com_checklist/assets/css/profile.css">
<div class="chk-profile-form">
	<h2 class="chk-header-form"><?php echo JText::_('COM_CHECKLIST_USER_DETAILS')?></h2>
	<form class="form-horizontal" action="<?php echo JURI::root();?>index.php?option=com_checklist&task=registration.register" role="form" method="post" name="profileForm">
	
	<div class="form-group">
	    <label for="uname" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_USER_NAME_REGISTER')?></label>
	    <div class="col-sm-10">
	      <input type="text" name="uname" class="form-control" id="uname" placeholder="<?php echo JText::_('COM_CHECKLIST_USER_NAME_PLACEHOLDER_REGISTER')?>" value="">
	    </div>
	</div>

	<div class="form-group">
	    <label for="username" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_LOGIN_NAME_REGISTER')?></label>
	    <div class="col-sm-10">
	      <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo JText::_('COM_CHECKLIST_LOGIN_NAME_PLACEHOLDER_REGISTER')?>" value="">
	    </div>
	</div>

	<div class="form-group">
		<label for="InputEmail" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_EMAIL_REGISTER')?></label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="InputEmail" placeholder="<?php echo JText::_('COM_CHECKLIST_EMAIL_PLACEHOLDER_REGISTER')?>" name="email1">
		</div>
	</div>
	<div class="form-group">
		<label for="InputPassword" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_PASSWORD_REGISTER')?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="InputPassword" placeholder="<?php echo JText::_('COM_CHECKLIST_PASSWORD_PLACEHOLDER_REGISTER')?>" name="password1">
		</div>
	</div>
	<div class="form-group">
		<label for="InputConfirmPassword" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_CONFIRM_PASSWORD_REGISTER')?></label>
		<div class="col-sm-10">
			<input type="password" class="form-control" id="InputConfirmPassword" placeholder="" name="password2">
		</div>
	</div>
	
	<h2 class="chk-header-form"><?php echo JText::_('COM_CHECKLIST_USER_INFORMATION')?></h2>
	
	  <div class="form-group">
	    <label for="website" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_WEBSITE_URL_REGISTER')?></label>
	    <div class="col-sm-10">
	      <input type="text" name="website_field" class="form-control" id="website" placeholder="Website URL" value="">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="twitter" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_TWITTER_REGISTER')?></label>
	    <div class="col-sm-10">
	      <input type="text" name="twitter_field" class="form-control" id="twitter" placeholder="Twitter" value="">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="facebook" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_FACEBOOK_REGISTER')?></label>
	    <div class="col-sm-10">
	      <input type="text" name="facebook_field" class="form-control" id="facebook" placeholder="Facebook" value="">
	    </div>
	  </div>
	  <div class="form-group">
	  	<label for="description" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_ABOUT_REGISTER')?></label>
	  	<div class="col-sm-10">
			<textarea class="form-control" id="description" name="description_field" rows="3"></textarea>
		</div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="button" class="btn btn-default" id="save_button"><?php echo JText::_('COM_CHECKLIST_SAVE_REGISTER')?></button>
	    </div>
	  </div>
	  <input type="hidden" name="task" value="registration.register"/>
	  <?php echo JHtml::_('form.token');?>
	</form>
</div>
<script type="text/javascript">
	
	var email_exists = false;
	var chk_base_URL = '<?php echo JURI::root();?>';
	var Checklist = {};

	Checklist.resetErrorMsg =  function (){
		jQuery(".alert").hide("slow");
	}
	
	Checklist.setErrorMsg = function(type, msg){
		
		if(type == 'danger'){
			jQuery(".alert-danger").html(msg);
			jQuery(".alert-danger").slideDown("slow");
		}
		
		if(type == 'success'){
			jQuery(".alert-success").html(msg);
			jQuery(".alert-success").slideDown("slow");
		}
		
	}

	Checklist.ajaxCheckEmail = function(){

		var email = document.getElementById("InputEmail").value;

		Checklist.doAjax('registration.ajaxcheckemail', {email: email}, Checklist.showResultCheck, null);

	}

	Checklist.showResultCheck = function(response, element){

		if(response == 'not_exists'){

			email_exists = false;
			Checklist.resetErrorMsg();
			Checklist.setErrorMsg('success', '<?php echo JText::_('COM_CHECKLIST_EMAIL_IS_NOT_EXISTS')?>');

		} else if(response == 'exists') {

			email_exists = true;
			Checklist.resetErrorMsg();
			Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_EMAIL_IS_EXISTS')?>');

		}

		return;

	}

	Checklist.bindEmailCheck = function()
	{
		jQuery("#InputEmail").bind("blur", function(){
			var email = document.getElementById("InputEmail").value;

			if(email != "" && !Checklist.validateEmail(email)){
				Checklist.resetErrorMsg();
				Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_EMAIL_IS_NOT_VALIDE')?>');
				return false;
			}

			if(email != ""){
				Checklist.ajaxCheckEmail();
			} else {
				Checklist.resetErrorMsg();
				Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_INPUT_EMAIL_PLEASE')?>');
			}
		});
	}

	Checklist.doAjax = function(task, params, feedback, object){
			
		var object = (typeof(object) != 'undefined') ? object : null;
		jQuery.ajax({
			url: chk_base_URL + "index.php?option=com_checklist&task=" + task + '&tmpl=component',
			type: "POST",
			data: params,
			dataType: "html",
			success: function(html){
				feedback(html, object);
			}
		});
		
	}

	Checklist.validateEmail = function(email) { 
	    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	}

	Checklist.bindSubmitForm = function(){
		
		jQuery("#save_button").bind("click", function(){

			var uname = document.getElementById("uname").value;
			var username = document.getElementById("username").value;
			var email = document.getElementById("InputEmail").value;

			if(uname == "" && username == "" && email == ""){
				Checklist.resetErrorMsg();
				Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_FILL_REQUIRED_FIELDS')?>');
				return false;
			}

			var password = document.getElementById("InputPassword").value;
			var password2 = document.getElementById("InputConfirmPassword").value;

			if(password != "" && password2 != ""){
				if(password !== password2){
					Checklist.resetErrorMsg();
					Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_PASSWORDS_ARE_NOT_MATCHING')?>');
					return false;
				}
			} else {
				Checklist.resetErrorMsg();
				Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_INPUT_PASSWORD_PLEASE')?>');
				return false;
			}

			if(email_exists){
				Checklist.resetErrorMsg();
				Checklist.setErrorMsg('danger', '<?php echo JText::_('COM_CHECKLIST_EMAIL_IS_EXISTS')?>');
				return false;
			}

			var form = document.profileForm;
			form.submit();
		});
	}

	jQuery(document).ready(function(){
		Checklist.bindEmailCheck();
		Checklist.bindSubmitForm();
	});

</script>
<?php } ?>