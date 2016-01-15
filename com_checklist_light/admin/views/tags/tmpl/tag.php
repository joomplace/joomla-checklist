<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
?>

<fieldset class="adminform">
	<div class="control-group">
		<?php echo JText::_('COM_LIGHTCHECKLIST_TAG_NAME'); ?>
		<input type="text" id="tagName" value="<?php echo $this->item->name;?>" size="35" class="input" name="name" style="margin:10px;"/>
		<button type="button" class="btn" onclick="redirectToTags(<?php echo $this->item->id;?>);">Save&Close</button>
		<input type="hidden" id="tagid" name="tagid" value="<?php echo $this->item->id;?>" />
	</div>
</fieldset>
	
<script type="text/javascript">
	function redirectToTags(tag_id)
	{
		var name = document.getElementById('tagName').value;
		if(name == ''){
			alert('<?php echo JText::_('COM_LIGHTCHECKLIST_NAME_IS_EMPTY');?>');
			return false;
		}
		name = encodeURIComponent(name);

		parent.location.href = 'index.php?option=com_lightchecklist&task=tags.save&id=' + tag_id + '&name=' + name;
		return false;
	}

	function submitOnKeyPress(event)
	{
		var tag_id = document.getElementById('tagid').value;
		//Press Enter
		if(event.keyCode == 13){
			redirectToTags(tag_id);
			return false;
		} else {
			return false;
		}
	}

	window.onkeydown = function(event)
	{
		event.preventDefault;
		submitOnKeyPress(event);
	}

</script>