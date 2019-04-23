<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('viewport'=>'body'));

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

	.checklist-user-profile {
	    padding: 0;
	}
	.checklist-userprof-item {
	    height: auto;
	    margin-bottom: 10px;
	}
	
	#user_info .author-register {
	    background: url('".JURI::root()."components/com_checklist/assets/images/register-date.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0);
	    padding-left: 20px !important;
	}

	#user_info .author-visit {
	    background: url('".JURI::root()."components/com_checklist/assets/images/visit-date.png') no-repeat scroll 0 0 rgba(0, 0, 0, 0);
	    padding-left: 20px !important;
	}

	.checklist-userprof-info {
	    float: left;
	    width: 80%;
	}

	.checklist-userprof-avatarcont, .checklist-userprof-titlecont {
	    float: left;
	    margin: 0 1% 0 0;
	    min-width: 70px;
	    width: auto;
	}
	.checklist-userprof-titlecont {
	    color: #777777;
	    font-weight: bold;
	    padding: 0;
	}
	#user_info
	{
		margin-left:10px;
	}
	.checklist-tagslinks, .checklist-authoricon
	{
		display:inline-block;
	}
	.checklist-tagslinks
	{
		display: inline-block;
		font-size: 10px;
		height: auto;
		margin-top: 10px;
		padding: 0 0 5px 18px;
	}
	.checklist-tagslinks
	{
		background: url(".JURI::root()."components/com_checklist/assets/images/icon_tags.gif) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
	}
	.chk-heading
	{
		display:inline-block;
	}";

$document->addStyleDeclaration($css);

if($this->config->rating_option){
	$document->addStylesheet(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.css");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.min.js");
?>
<script type="text/javascript">
	jQuery(document).ready(function(event){
		jQuery(".rating").jRating({
		  step: 1,
		  rateMax: 5,
		  isDisabled : true,
		  phpPath:'<?php echo JURI::root();?>index.php?option=com_checklist&task=checklist.saverate&tmpl=component'
		});
	});
</script>
<?php }?>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo JURI::root();?>components/com_checklist/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<?php

if($this->user->avatar_field != ''){
	if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->user->id.'/thm_'.$this->user->avatar_field)){
		$avatar_path = JURI::root().'images/checklist/avatar/'.$this->user->id.'/thm_'.$this->user->avatar_field;
		$original_path = JURI::root().'images/checklist/avatar/'.$this->user->id.'/'.$this->user->avatar_field;
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
	    	<h3 class="media-heading chk-heading"><?php echo $this->user->name;?></h3>
	    	<?php if($user->id == $this->user->id):?>
	    	<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=editprofile');?>" alt="<?php echo JText::_('COM_CHECKLIST_EDIT_PROFILE')?>" style="display:inline-block;">
	    		<span class="glyphicon glyphicon-pencil"></span>
	    	</a>
	    	<?php endif;?>
		    <div class="clearfix chk-social-buttons">
		    	<?php if($this->user->website_field != ''){?>
		        	<span class="chk-authorsite"><i class="chk-icon-earth"></i><a href="<?php echo $this->user->website_field;?>" target="_blank" rel="nofollow" class="chk-usersite-link"><?php echo $this->user->website_field;?></a></span>
		        <?php } ?>
		        <?php if($this->user->twitter_field != ''){?>
					<span class="chk-authortwitter"><i class="chk-icon-tweet"></i><a href="<?php echo $this->user->twitter_field;?>" target="_blank" rel="nofollow" class="chk-twitter-link"><?php echo $this->user->twitter_field;?></a></span>
				<?php } ?>
				<?php if($this->user->facebook_field != ''){?>
					<span class="chk-authorfacebook"><i class="chk-icon-facebook"></i><a href="<?php echo $this->user->facebook_field;?>" target="_blank" rel="nofollow" class="chk-facebook-link">Facebook</a></span>
				<?php } ?>
				<?php if($this->user->google_field != ''){?>
					<span class="chk-authorgoogleplus"><i class="chk-icon-google-plus-2"></i><a href="<?php echo $this->user->google_field;?>" target="_blank" rel="nofollow" class="chk-googleplus-link">Google+</a></span>
				<?php } ?>
	        </div>
	        <div class="chk-user-description">
	    		<?php echo $this->user->description_field ?>
	    	</div>
	    	<div class="tab-pane" id="user_info">
			  	<div class="checklist-userprof-item clearfix">
			        <div class="checklist-userprof-titlecont author-register">
			            <?php echo JText::_('COM_CHECKLIST_REGISTERED');?>
			        </div>
			        <div class="checklist-userprof-info">
			            <div>
			                <?php echo date('d F Y, H:i', strtotime($this->user->registerDate));?>   
			            </div>
			        </div>
			    </div>
			    <div class="checklist-userprof-item clearfix">
			        <div class="checklist-userprof-titlecont author-visit">
			            <?php echo JText::_('COM_CHECKLIST_LAST_VISIT');?>
			        </div>

			        <div class="checklist-userprof-info">
			            <div>
			                <?php echo date('d F Y, H:i', strtotime($this->user->lastvisitDate));?> 
			            </div>
			        </div>
			    </div>
			</div>
	    </div>
	  </li>
</ul>

<div class="checklist-checklists"> 	
  	<?php if(count($this->checklists)){?>
	<ul class="media-list">
		<?php foreach ($this->checklists as $checklist) {?>
		  <li class="media">
			  <div class="media-body">
			    <h3 class="media-heading"><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist->id.'&user_id='.$checklist->user_id)?>"><?php echo $checklist->title;?></a></h3>

			    <?php if($this->config->rating_option){?>
			    <div class="rating hasTooltip" data-average="<?php echo (($checklist->average_rating) ? $checklist->average_rating : 0);?>" data-id="<?php echo $checklist->id?>" title="<strong><?php echo JText::_('COM_CHECKLIST_RATING')?>&nbsp;&nbsp;</strong><span style='color:green; font-size: 20px;''><?php echo (($checklist->average_rating) ? number_format($checklist->average_rating, 2, ".", ",") : "0.00");?></span><?php echo JText::_('COM_CHECKLIST_OUT_OF')?><span style='font-size: 17px; font-weight:bold;'>5.00</span>"></div>
			    <div style="clear:both;"><br/></div>
			    <?php }?>

			    <?php echo substr(strip_tags($checklist->description_before), 0, 500);?>
			  </div>
			  <div style="clear:both;"><br/></div>
			  <div>
			  	  <div style="float:left;">
			  	  	<?php if(count($this->tags_data[$checklist->id])){
						
						$Itemid = JFactory::getApplication()->input->get('Itemid');
						$tags_arr = array();
						foreach($this->tags_data[$checklist->id] as $tags){
							foreach ($tags as $tag) {
								$tags_arr[] = '<a href="'.JRoute::_('index.php?option=com_checklist&view=tag&id='.$tag['id'].'&Itemid='.$Itemid).'">'.$tag['name'].'</a>';
							}
							
						}

						if(count($tags_arr)){
						 	echo '<div class="checklist-tagslinks">'.implode(" , ", $tags_arr).'</div>';
						} else {
							echo '<div class="checklist-tagslinks">'.JText::_('COM_CHECKLIST_UNTAGGED').'</div>';
						}

					} ?>
					
			  	  </div>
			  </div>
		  </li>
		<?php }?>
	</ul>
	<?php } else {?>
	<span><?php echo JText::_('COM_CHECKLIST_NO_ITEMS_YET');?></span>
	<?php }?>
</div>
<div style="clear:both;"><br/></div>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox();
	});
</script>


