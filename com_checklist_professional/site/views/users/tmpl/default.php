<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('viewport'=>'body'));
JHtml::_('script', 'system/core.js', false, true);

$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

$itemid = JFactory::getApplication()->input->getInt('Itemid', 0);
$itemid = $itemid ? '&Itemid='.$itemid : '';
?>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<style type="text/css">

	@import "<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/style.css";

	@font-face {

		font-family: 'ChecklistSet';
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.eot');
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.eot?#iefix') format('embedded-opentype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.woff') format('woff'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.ttf') format('truetype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/icons/ChecklistSet/fonts/ChecklistSet.svg') format('svg');
		font-weight: normal;
		font-style: normal;

	}

	.media-list {
		margin-right: 20px;
	}

	.chk-edit-profile-btn
	{
		margin-left: 10px;
		border-bottom: 1px solid #CCCCCC;
    	clear: both;
    	padding: 10px 10px 20px;
	}
	
</style>
<?php if($user->id){?>
<div class="chk-edit-profile-btn">
	<button class="btn btn-primary btn-lg" type="button" onclick="window.location.href='<?php echo JRoute::_('index.php?option=com_checklist&view=editprofile'.$itemid); ?>'; return false;"><?php echo JText::_('COM_CHECKLIST_EDIT_PROFILE');?></button>
</div>
<?php }?>

<?php if(count($this->users)){?>
	<ul class="media-list">
	<?php foreach ($this->users as $user){
		if($user->avatar_field != ''){
			if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$user->id.'/thm_'.$user->avatar_field)){
				$avatar_path = JURI::root().'images/checklist/avatar/'.$user->id.'/thm_'.$user->avatar_field;
				$original_path = JURI::root().'images/checklist/avatar/'.$user->id.'/'.$user->avatar_field;
			} else {
				$original_path = $avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
			}
		} else {
			$original_path = $avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
		}
	?>
	  <li class="media chk-author-block">
	    <a class="pull-left fancybox" href="<?php echo $original_path;?>">
	      <img class="media-object chk-author-avatar" src="<?php echo $avatar_path; ?>" alt="">
	    </a>
	    <div class="media-body">
	    	<h4 class="media-heading chk-heading"><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=profile&userid='.$user->id.$itemid); ?>"><?php echo $user->name;?></a></h4>
		    <div class="clearfix chk-social-buttons">
		    	<?php if($user->website_field != ''){?>
		        	<span class="chk-authorsite"><i class="chk-icon-earth"></i><a href="<?php echo $user->website_field;?>" target="_blank" rel="nofollow" class="chk-usersite-link"><?php echo $user->website_field;?></a></span>
		        <?php } ?>
		        <?php if($user->twitter_field != ''){?>
					<span class="chk-authortwitter"><i class="chk-icon-tweet"></i><a href="<?php echo $user->twitter_field;?>" target="_blank" rel="nofollow" class="chk-twitter-link"><?php echo $user->twitter_field;?></a></span>
				<?php } ?>
				<?php if($user->facebook_field != ''){?>
					<span class="chk-authorfacebook"><i class="chk-icon-facebook"></i><a href="<?php echo $user->facebook_field;?>" target="_blank" rel="nofollow" class="chk-facebook-link">Facebook</a></span>
				<?php } ?>
	        </div>
	        <div class="chk-user-description">
	    		<?php echo $user->description_field ?>
	    	</div>
	    	<?php if(isset($this->lists[$user->id]) && count($this->lists[$user->id])){?>
	    	<div class="chk-view-all">
				<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=lists&userid='.$user->id);?>"><?php echo JText::_('COM_CHECKLIST_VIEW_ALL_LISTS')?>(<?php echo count($this->lists[$user->id])?>)</a>
	    	</div>
	    	<?php } ?>
	    </div>
	  </li>
	<?php } ?>
</ul>
<form id="adminForm" name="adminForm" class="form-horizontal" role="form" method="post"
      action="<?php echo /*JRoute::_('index.php?option=com_checklist&view=users'.$itemid);*/ htmlspecialchars(JUri::getInstance()->toString());?>">
	<div class="checklist-pagination">
		<?php echo $this->pagination->getListFooter(); ?>
	</div>
</form>
<?php } ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox();
	});
</script>



