<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/tagmanager-master/tagmanager.css");
?>
<style type="text/css">
	
	span.tm-tag:hover
	{
		background-color: #D5ECF2;
	}

	.tag-container
	{
		min-height: 330px;
	}

</style>

<div class="tag-container">
<?php
if(isset($this->checklists[$this->item->id]) && count($this->checklists[$this->item->id])){?>
	<?php foreach ($this->checklists[$this->item->id] as $checklist) {?>

	<span class="tm-tag"><a style="text-decoration:none;" title="<?php echo $this->escape(strip_tags($checklist->title)); ?>" href="javascript:void(0);" onclick="redirectToList(<?php echo $checklist->id?>);"><?php echo $this->escape($checklist->title); ?></a><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/list_bullets.png" onclick="redirectToCheckist(<?php echo $checklist->id?>);return false;" style="cursor:pointer;margin-left:6px;"><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/delete.png" onclick="removeCheckist(this, <?php echo $checklist->id?>);return false;" style="cursor:pointer;margin-left:6px;"></span>
	
	
	<?php } ?>
<?php } ?>
</div>
<hr/>
<div>
	<?php echo $this->lists;?>
	<button style="margin-left:5px;" onclick="addChecklist(<?php echo $this->item->id?>);" class="btn">Add</button>
</div>
<input type="hidden" name="tag_id" id="tag_id" value="<?php echo $this->item->id?>">

<script type="text/javascript">
	function redirectToList(checklist_id)
		{
			parent.location.href = 'index.php?option=com_checklist&task=list.edit&id=' + checklist_id;
			return false;
		}

	function redirectToCheckist(checklist_id)
	{
		parent.location.href = 'index.php?option=com_checklist&view=checklist&id=' + checklist_id;
			return false;
	}

	function addChecklist(tag_id)
	{
		var checklist_id = jQuery('#checklist_id').val();
		var tag_id = jQuery('#tag_id').val();

		if(checklist_id == ''){
			alert('Select checklist please!');
			return false;
		}

		parent.jQuery.ajax({ 
			url: "index.php?option=com_checklist&task=tags.addChecklist",
			type: "POST",
			data: { checklist_id : checklist_id, tag_id: tag_id },
			dataType: "html",
			success: function(response){

				if(response == 'success'){
					var title = document.getElementById('checklist_id').options[document.getElementById('checklist_id').selectedIndex].text;

					var html = '<span class="tm-tag"><a style="text-decoration:none;" title="" href="javascript:void(0);" onclick="redirectToList('+checklist_id+');">' + title + '</a><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/list_bullets.png" onclick="redirectToCheckist('+checklist_id+');return false;" style="cursor:pointer;margin-left:6px;"><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/delete.png" onclick="removeCheckist(this, '+checklist_id+');return false;" style="cursor:pointer;margin-left:6px;"></span>';
					jQuery('.tag-container').append(html);
				} else if(response == 'exists'){
					alert('<?php echo JText::_('COM_CHECKLIST_CHECKLIST_ALREADY_ASSIGN');?>');
				}

			}

		});
	}

	function removeCheckist(element, checklist_id)
	{
		var tag_id = jQuery('#tag_id').val();

		parent.jQuery.ajax({ 
			url: "index.php?option=com_checklist&task=tags.removeChecklist",
			type: "POST",
			data: { checklist_id : checklist_id, tag_id: tag_id },
			dataType: "html",
			success: function(response){
				if(response == 'success'){
					jQuery(element).parent().remove();
				}
			}
		});
	}
</script>