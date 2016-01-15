<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

if(!defined('COMPONENT_IMAGES_URL')) define('COMPONENT_IMAGES_URL', JURI::root().'administrator/components/COM_CHECKLIST/assets/images/');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');
?>
<?php echo $this->loadTemplate('menu');?>
<style>
/*** Configuration ***/

.checklist_google_plus_preview {
	float: left;
}
.checklist_twitter_preview {
	float: left;
}
.checklist_twitter_preview_notice {
	float: left;
	margin: 6px 0 0 10px;
	color: #886622;
	cursor: default;
}
.checklist_linkedin_preview {
	float: left;
}
.checklist_facebook_preview {
	float: left;
}
.checklist_global_permissions {
	margin-left: 0px !important;
}

</style>
<script type="text/javascript">
	
	var form = null;
	
	jQuery(document).ready(function ()
	{
	    jQuery('#viewTabs a:first').tab('show');
	    jQuery('#socialTabs a:first').tab('show');
		
		updateGooglePlusPreview();
		updateTwitterPreview();
		updateLinkedinPreview();
		updateFacebookPreview();
	});

	
	function updateGooglePlusPreview()
	{
		var size = BootstrapFormHelper.getRadioGroupValue('jform_social_google_plus_size');
		var annotation = BootstrapFormHelper.getRadioGroupValue('jform_social_google_plus_annotation');
		
		var previewImg = document.getElementById('social_google_plus_preview');
		
		previewImg.setAttribute('src', '<?php echo COMPONENT_IMAGES_URL.'social/'; ?>' + 'googleplus-' + size + '-' + annotation + '.png');
	}
	
	function updateTwitterPreview()
	{
		var size = BootstrapFormHelper.getRadioGroupValue('jform_social_twitter_size');
		var annotation = BootstrapFormHelper.getRadioGroupValue('jform_social_twitter_annotation');
		
		var previewImg = document.getElementById('social_twitter_preview');
		
		previewImg.setAttribute('src', '<?php echo COMPONENT_IMAGES_URL.'social/'; ?>' + 'twitter-' + size + '-' + annotation + '.png');
		
		// Showing notice.
		
		var noticeDiv = document.getElementById('social_twitter_preview_notice');
		
		if (size == 'large' && annotation == 'vertical')
		{
			noticeDiv.innerHTML = '<?php echo JText::_('COM_CHECKLIST_BE_CONFIG_TWITTER_PREVIEW_NOTICE'); ?>';
		}
		else
		{
			noticeDiv.innerHTML = '';
		}
	}
	
	function updateLinkedinPreview()
	{
		var annotation = BootstrapFormHelper.getRadioGroupValue('jform_social_linkedin_annotation');
		
		var previewImg = document.getElementById('social_linkedin_preview');
		
		previewImg.setAttribute('src', '<?php echo COMPONENT_IMAGES_URL.'social/'; ?>' + 'linkedin-' + annotation + '.png');
	}
	
	function updateFacebookPreview()
	{
		var verb = BootstrapFormHelper.getRadioGroupValue('jform_social_facebook_verb');
		var layout = BootstrapFormHelper.getRadioGroupValue('jform_social_facebook_layout');
		
		var previewImg = document.getElementById('social_facebook_preview');
		
		previewImg.setAttribute('src', '<?php echo COMPONENT_IMAGES_URL.'social/'; ?>' + 'facebook-' + verb + '-' + layout + '.png');
	}

	function onRadioGooglePlusSizeClick(sender, event)
	{
		updateGooglePlusPreview();
	}
	
	function onRadioGooglePlusAnnotationClick(sender, event)
	{
		updateGooglePlusPreview();
	}
	
	function onRadioTwitterSizeClick(sender, event)
	{
		updateTwitterPreview();
	}
	
	function onRadioTwitterAnnotationClick(sender, event)
	{
		updateTwitterPreview();
	}
	
	function onRadioLinkedinAnnotationClick(sender, event)
	{
		updateLinkedinPreview();
	}
	
	function onRadioFacebookVerbClick(sender, event)
	{
		updateFacebookPreview();
	}
	
	function onRadioFacebookLayoutClick(sender, event)
	{
		updateFacebookPreview();
	}

	function onResetPermissoionsLinkClick(sender, event)
	{
		if (confirm('<?php echo JText::_('COM_CHECKLIST_BE_CONFIG_RESET_ALL_PERMISSIONS_CONFIRM'); ?>'))
		{
			var link = "<?php echo JURI::root().'administrator/index.php?option=COM_CHECKLIST&task=configuration.reset_permissions&tmpl=component'; ?>";
			var width = 350;
			var height = 120;
			
			var linkElement = document.createElement('a');
			linkElement.href = link;
			
			SqueezeBox.fromElement(linkElement, { handler: 'iframe', size: { x: width, y: height }, url: link });
		}
	}
	
	Joomla.submitbutton = function(task)
	{
		if (task == 'configuration.cancel')
		{
			Joomla.submitform(task, document.adminForm);
			return;
		}
		
		if (task == 'configuration.apply')
		{
			Joomla.removeMessages();

			if (!document.formvalidator.isValid(document.adminForm))
			{
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
				return;
			}
			
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>

<form name="adminForm" id="adminForm" action="index.php" method="post" autocomplete="off" class="form-validate" onsubmit="return false;">
	<input type="hidden" name="option" value="<?php echo 'COM_CHECKLIST'; ?>" />
	<input type="hidden" name="view" value="<?php echo $this->getName(); ?>" />
	<input type="hidden" name="layout" value="<?php echo $this->getLayout(); ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
	<div id="j-main-container" class="span12 form-horizontal">
		
		<ul class="nav nav-tabs" id="viewTabs">
			<li><a href="#tab_social" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_SOCIAL_TAB");?></a></li>
			<li><a href="#tab_global_permissions" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_PERMISSIONS_TAB");?></a></li>
			<li><a href="#tab_global_config" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_GLOBAL_CONFIG_TAB");?></a></li>
		</ul>
		
		<div class="tab-content">
			
			<div class="tab-pane" id="tab_social">
				
				<ul class="nav nav-tabs" id="socialTabs">
					<li><a href="#tab_social_google" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_GOOGLEPLUS_SUBPANEL");?></a></li>
					<li><a href="#tab_social_twitter" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_TWITTER_SUBPANEL");?></a></li>
					<li><a href="#tab_social_linkedin" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_LINKEDIN_SUBPANEL");?></a></li>
					<li><a href="#tab_social_facebook" data-toggle="tab"><?php echo  JText::_("COM_CHECKLIST_BE_CONFIG_FACEBOOK_SUBPANEL");?></a></li>
				</ul>
				
				<div class="tab-content">
					
					<?php
					//==================================================
					// Google+.
					//==================================================
					?>
					
					<div class="tab-pane" id="tab_social_google">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_google_plus_use'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_google_plus_use'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_google_plus_size'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_google_plus_size'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_google_plus_annotation'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_google_plus_annotation'); ?>
							</div>
						</div>
						<div class="control-group">
							
							<div class="control-label">
								<?php echo $this->form->getLabel('social_google_plus_language'); ?>
							</div>
							<div class="controls">
								<?php echo $this->googlePlusLanguageOptions; ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php
								echo JHTML::_("tooltip", JText::_("COM_CHECKLIST_BE_CONFIG_GOOGLEPLUS_PREVIEW_DESC") . '<br/><br/>' .
									'<span>' . "* " . JText::_("COM_CHECKLIST_BE_CONFIG_GOOGLEPLUS_PREVIEW_NOLANG") . '</span>',
									JText::_("COM_CHECKLIST_BE_CONFIG_GOOGLEPLUS_PREVIEW"), null,
									'<label>' . JText::_("COM_CHECKLIST_BE_CONFIG_GOOGLEPLUS_PREVIEW") . '</label>', null);
								?>
							</div>
							<div class="controls">
								<img id="social_google_plus_preview" class="checklist_google_plus_preview" />
							</div>
						</div>
					</div>
					
					<?php
					//==================================================
					// Twitter.
					//==================================================
					?>
					
					<div class="tab-pane" id="tab_social_twitter">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_twitter_use'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_twitter_use'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_twitter_size'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_twitter_size'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_twitter_annotation'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_twitter_annotation'); ?>
							</div>
						</div>
						<div class="control-group">
							
							<div class="control-label">
								<?php echo $this->form->getLabel('social_twitter_language'); ?>
							</div>
							<div class="controls">
								<?php echo $this->twitterLanguageOptions; ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php
								echo JHTML::_("tooltip", JText::_("COM_CHECKLIST_BE_CONFIG_TWITTER_PREVIEW_DESC") . '<br/><br/>' .
									'<span>' . "* " . JText::_("COM_CHECKLIST_BE_CONFIG_TWITTER_PREVIEW_NOLANG") . '</span>',
									JText::_("COM_CHECKLIST_BE_CONFIG_TWITTER_PREVIEW"), null,
									'<label>' . JText::_("COM_CHECKLIST_BE_CONFIG_TWITTER_PREVIEW") . '</label>', null);
								?>
							</div>
							<div class="controls">
								<img id="social_twitter_preview" class="checklist_twitter_preview" />
								<div id="social_twitter_preview_notice" class="checklist_twitter_preview_notice"></div>
							</div>
						</div>
					</div>
					
					<?php
					//==================================================
					// LinkedIn.
					//==================================================
					?>
					
					<div class="tab-pane" id="tab_social_linkedin">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_linkedin_use'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_linkedin_use'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_linkedin_annotation'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_linkedin_annotation'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php
								echo JHTML::_("tooltip", JText::_("COM_CHECKLIST_BE_CONFIG_LINKEDIN_PREVIEW_DESC"),
									JText::_("COM_CHECKLIST_BE_CONFIG_LINKEDIN_PREVIEW"), null,
									'<label>' . JText::_("COM_CHECKLIST_BE_CONFIG_LINKEDIN_PREVIEW") . '</label>', null);
								?>
							</div>
							<div class="controls">
								<img id="social_linkedin_preview" class="checklist_linkedin_preview" />
							</div>
						</div>
					</div>
					
					<?php
					//==================================================
					// Facebook.
					//==================================================
					?>
					
					<div class="tab-pane" id="tab_social_facebook">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('fbadmin'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('fbadmin'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('fbappid'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('fbappid'); ?>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_facebook_use'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_facebook_use'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_facebook_verb'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_facebook_verb'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('social_facebook_layout'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('social_facebook_layout'); ?>
							</div>
						</div>
						<div class="control-group">
							
							<div class="control-label">
								<?php echo $this->form->getLabel('social_facebook_font'); ?>
							</div>
							<div class="controls">
								<?php echo $this->facebookFontOptions; ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php
								echo JHTML::_("tooltip", JText::_("COM_CHECKLIST_BE_CONFIG_FACEBOOK_PREVIEW_DESC") . '<br/><br/>' .
									'<span>' . "* " . JText::_("COM_CHECKLIST_BE_CONFIG_FACEBOOK_PREVIEW_NOFONT") . '</span>',
									JText::_("COM_CHECKLIST_BE_CONFIG_FACEBOOK_PREVIEW"), null,
									'<label>' . JText::_("COM_CHECKLIST_BE_CONFIG_FACEBOOK_PREVIEW") . '</label>', null);
								?>
							</div>
							<div class="controls">
								<img id="social_facebook_preview" class="checklist_facebook_preview" />
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
			
			<div class="tab-pane" id="tab_global_permissions">
				<div class="controls checklist_global_permissions">
					<?php echo $this->form->getInput('rules'); ?>
					<button class="btn btn-small" onclick="onResetPermissoionsLinkClick(this, event);">
						<i class="icon-key"></i>
						<?php echo JText::_("COM_CHECKLIST_BE_CONFIG_RESET_ALL_PERMISSIONS"); ?>
					</button>
				</div>
				
			</div>
			<div class="tab-pane" id="tab_global_config">
				<fieldset class="form-horizontal">
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('moderateChecklist'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('moderateChecklist'); ?></div>
					</div>
				</fieldset>
				<fieldset class="form-horizontal">
					<legend><?php echo JText::_('COM_CHECKLIST_SETTINGS_EMAILS');?></legend>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('useNotification'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('useNotification'); ?></div>
					</div>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('emailsNotification'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('emailsNotification'); ?></div>
					</div>
				</fieldset>
			</div>
			
		</div>
		
	</div>
	
</form>