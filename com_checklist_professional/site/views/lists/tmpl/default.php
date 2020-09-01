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
JHtml::_('behavior.core');

$Itemid = ($this->itemid) ? "&Itemid=".$this->itemid : "";
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

$css = "
	@import '".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/style.css';

	@font-face {

		font-family: 'ChecklistSet';
		src:url('".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.eot');
		src:url('".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.eot?#iefix') format('embedded-opentype'),
			url('".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.woff') format('woff'),
			url('".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.ttf') format('truetype'),
			url('".JURI::root()."components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.svg') format('svg');
		font-weight: normal;
		font-style: normal;

	}
	";

$document->addStyleDeclaration($css);

if($this->allow_edit){
	$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/zebra_dialog.css");
	$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/lists.css");

	$document->addScript(JURI::root()."components/com_checklist/assets/js/zebra_dialog.js");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/lists.js");
}

?>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<!-- User Profile -->
<?php 

$your_checklist = '';
if($this->uid == $user->id) $your_checklist = JText::_('COM_CHECKLIST_YOUR_CHECKLISTS');

if($this->user_data->avatar_field != ''){
	if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->user_data->id.'/thm_'.$this->user_data->avatar_field)){
		$avatar_path = JURI::root().'images/checklist/avatar/'.$this->user_data->id.'/thm_'.$this->user_data->avatar_field;
		$original_path = JURI::root().'images/checklist/avatar/'.$this->user_data->id.'/'.$this->user_data->avatar_field;
	} else {
		$original_path = $avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
	}
} else {
	$original_path = $avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
}

?>

<ul class="media-list">
	<li class="media chk-author-block">
    <a class="pull-left fancybox" href="<?php echo $original_path;?>">
      <img class="media-object chk-author-avatar" src="<?php echo $avatar_path; ?>" alt="">
    </a>
    <div class="media-body">
    	<h4 class="media-heading chk-heading"><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=profile&userid='.$this->uid);?>"><?php echo $this->user_data->name; ?></a><?php echo $your_checklist;?></h4>
	    <div class="clearfix chk-social-buttons">
	    	<?php if($this->user_data->website_field != ''){?>
	        	<span class="chk-authorsite"><i class="chk-icon-earth"></i><a href="<?php echo $this->user_data->website_field;?>" target="_blank" rel="nofollow" class="chk-usersite-link"><?php echo $this->user_data->website_field;?></a></span>
	        <?php } ?>
	        <?php if($this->user_data->twitter_field != ''){?>
				<span class="chk-authortwitter"><i class="chk-icon-tweet"></i><a href="<?php echo $this->user_data->twitter_field;?>" target="_blank" rel="nofollow" class="chk-twitter-link"><?php echo $this->user_data->twitter_field;?></a></span>
			<?php } ?>
			<?php if($this->user_data->facebook_field != ''){?>
				<span class="chk-authorfacebook"><i class="chk-icon-facebook"></i><a href="<?php echo $this->user_data->facebook_field;?>" target="_blank" rel="nofollow" class="chk-facebook-link">Facebook</a></span>
			<?php } ?>
        </div>
        <div class="chk-user-description">
    		<?php echo $this->user_data->description_field ?>
    	</div>
    </div>
  </li>
</ul>

<div style="margin-left:25px;">
<?php if($this->allow_edit){ ?>
<div class="alert alert-success"></div>
<div class="alert alert-danger"></div>

<?php if($user->authorise('core.create', 'com_checklist')) { ?>
<p>
	<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=edit_checklist&tmpl=component');?>" type="button" class="btn btn-primary btn-lg various btn-warning" data-fancybox-type="iframe"><?php echo JText::_('Create your own checklist')?></a>
</p>
<?php } ?>

<form name="adminForm" id="adminForm"
      action="<?php echo /*JRoute::_('index.php?option=com_checklist&view=lists'.$Itemid);*/ htmlspecialchars(JUri::getInstance()->toString());?>"
      method="post">

<div class="list-group my-checklist">
<h4><?php echo JText::_('COM_CHECKLIST_MY_OWN_CHECKLIST');?></h4>

<?php if(count($this->checklists['my'])): ?>

	<?php foreach($this->checklists['my'] as $checklist):?>
		<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist->id);?>" class="list-group-item">
		
			<span class="chk-remove-chklist" checklistid="<?php echo $checklist->id?>"><img src="<?php echo JURI::root()?>components/com_checklist/assets/images/delete.png" class="chk-delete-checklist" /></span>
			<span class="chk-remove-chklist" checklistid="<?php echo $checklist->id?>"><img src="<?php echo JURI::root()?>components/com_checklist/assets/images/edit.png" class="chk-edit-checklist"/></span>
			
			<h4 class="list-group-item-heading"><?php echo $checklist->title;?><?php if($checklist->default):?> (<small><?php echo JText::_('COM_CHECKLIST_AVAILABLE');?></small>)<?php endif;?></h4>
			<p class="list-group-item-text"><?php echo strip_tags($checklist->description_before);?></p>
			
		</a>
	<?php endforeach; ?>

<?php endif;?>
</div>
<!-- Pagination -->
<div class="checklist-pagination">
	<?php echo $this->pagination->getListFooter(); ?>
</div>

</form>

<?php } else {?>
<form name="adminForm" id="adminForm"
      action="<?php echo /*JRoute::_('index.php?option=com_checklist&view=lists'.$Itemid);*/ htmlspecialchars(JUri::getInstance()->toString());?>"
      method="post">
<div class="list-group user-checklist">
	
	<?php if(count($this->checklists['user'])): ?>
		<?php foreach($this->checklists['user'] as $checklist):?>
			<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist->id.'&userid='.$this->userid);?>" class="list-group-item">		
				<h4 class="list-group-item-heading"><?php echo $checklist->title;?><?php if($checklist->default):?> (<small><?php echo JText::_('COM_CHECKLIST_AVAILABLE');?></small>)<?php endif;?></h4>
				<p class="list-group-item-text"><?php echo strip_tags($checklist->description_before);?></p>
			</a>
		<?php endforeach; ?>
	<?php endif;?>
</div>
<!-- Pagination -->
<div class="checklist-pagination">
	<?php echo $this->pagination->getListFooter(); ?>
</div>

</form>
<?php }?>

</div>


<iframe id="content_iframe" style="display:none;width:100%;height:100%;" src="javascript:void(0);"></iframe>

<?php if($this->allow_edit){ ?>
<script type="text/javascript">

	var chk_base_URL = '<?php echo JURI::root();?>';
	Checklist.bindShowTools();
	Checklist.bindChecklistsTools();
	Checklist.onInitApplication();

	var input_title_of_checklist = "<?php echo JText::_('COM_CHECKLIST_INPUT_TITLE_OF_CHECKLIST');?>";
	var checklist_was_successfully_added = "<?php echo JText::_('COM_CHECKLIST_WAS_SUCCESSFULLY_ADDED');?>";
	var are_you_sure_to_remove_checklist = "<?php echo JText::_('COM_CHECKLIST_ARE_YOU_SURE_TO_REMOVE_CHECKLIST');?>";
	var chk_confirm_window = "<?php echo JText::_('COM_CHECKLIST_CONFIRM_WINDOW');?>";
	var chk_confirm = "<?php echo JText::_('COM_CHECKLIST_CONFIRM');?>";
	var chk_cancel = "<?php echo JText::_('COM_CHECKLIST_CANCEL');?>";
	var checklist_was_successfully_removed = "<?php echo JText::_('COM_CHECKLIST_WAS_SUCCESSFULLY_REMOVED');?>";
	var chk_checklist_name = "<?php echo JText::_('COM_CHECKLIST_CHECKLIST_NAME');?>";
	var chk_checklist_name_placeholder = "<?php echo JText::_('COM_CHECKLIST_NAME_PLACEHOLDER');?>";
	var chk_checklist_description = "<?php echo JText::_('COM_CHECKLIST_CHECKLIST_DESCRIPTION');?>";
	var chk_save = "<?php echo JText::_('COM_CHECKLIST_SAVE');?>";
	var chk_cancel = "<?php echo JText::_('COM_CHECKLIST_CANCEL');?>";
	var checklist_was_successfully_saved = "<?php echo JText::_('COM_CHECKLIST_WAS_SUCCESSFULLY_SAVED');?>";

</script>
<?php }?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox();
		jQuery(".various").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: true,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	});
</script>
