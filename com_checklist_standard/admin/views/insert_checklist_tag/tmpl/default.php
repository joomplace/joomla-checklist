<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
?>

<style type="text/css">
	
	body {
		height: auto;
		margin: 0;
		padding: 0;
	}
	
</style>

<script type="text/javascript">
	
	var form = null;
	
	jQuery(document).ready(function ()
	{
		form = getFormControls();
	});
	
	function getFormControls()
	{
		return {
			checklistSelect : document.getElementById('checklist_id')
			};
	}

	function onBtnInsertClick(sender, event)
	{
		event.preventDefault();
		
		var select = document.adminForm.checklist_id;
		if(select.options[select.selectedIndex].value == 0){
			alert('Select checklist please!');
			return false;
		}

		window.parent.checklistInsertTag(form.checklistSelect.value);
	}
	
	function onBtnCancelClick(sender, event)
	{
		window.parent.SqueezeBox.close();
	}
	
</script>

<form name="adminForm" action="index.php" method="post" autocomplete="off">
	<input type="hidden" name="option" value="com_checklist" />
	<input type="hidden" name="view" value="<?php echo $this->getName(); ?>" />
	<input type="hidden" name="view" value="<?php echo $this->getLayout(); ?>" />
	<input type="hidden" name="tmpl" value="component" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
	<div id="j-main-container" class="span7 form-horizontal checklist_insert_checklist_tag">
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('checklist_id'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('checklist_id'); ?>
			</div>
		</div>

		<div class="control-group _buttons_group">
			<button class="btn btn-primary" onclick="onBtnInsertClick(this, event);">
				<?php echo JText::_('COM_CHECKLIST_BE_INSERT_CHECKLIST_TAG_BTN_INSERT'); ?>
			</button>
			<button class="btn" onclick="onBtnCancelClick(this, event);">
				<?php echo JText::_('COM_CHECKLIST_BE_INSERT_CHECKLIST_TAG_BTN_CANCEL'); ?>
			</button>
		</div>
		
	</div>
	
</form>