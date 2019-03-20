<?php
/**
* Staticcontent component for Joomla 3.0
* @package Staticcontent
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip');

$itemid = ($this->itemid) ? "&Itemid=".$this->itemid : "";

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");
$document->addStyleDeclaration("
	
	.media
	{
		margin-right:15px;
		border-bottom: 1px solid #CCCCCC;
	    clear: both;
	    margin: 0 0 15px;
	    padding: 10px 10px 20px;
	}

	.media, .media-body {
	    overflow: visible !important;
	}

	.media {
		padding: 10px 10px 45px !important;
	}

	.checklist-authorhref
	{
		margin-left:5px;
	}

	");
	
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

<?php if(count($this->checklists)):?>
  <form name="adminForm" id="adminForm" action="<?php echo JRoute::_('index.php?option=com_checklist&view=tag'.$itemid);?>" method="post">
	<ul class="media-list">
	<li class="media">
		<a class="pull-left" href="javascript:void(0);">
		    <img class="media-object" src="<?php echo JURI::root();?>components/com_checklist/assets/images/tag_white.png">
		</a>
		<div class="media-body">
			<h3 class="media-heading"><?php echo $this->tag_name;?> <small><?php echo $this->total." ".JText::_('COM_CHECKLIST_TOTAL');?></small></h3>
			
		</div>
	</li>
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
				<?php
					if(isset($this->user_data[$checklist->id]->avatar_field)){
						if($this->user_data[$checklist->id]->avatar_field != ''){
							if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->user_data[$checklist->id]->user_id.'/thm_'.$this->user_data[$checklist->id]->avatar_field)){
								$avatar_path = JURI::root().'images/checklist/avatar/'.$this->user_data[$checklist->id]->user_id.'/thm_'.$this->user_data[$checklist->id]->avatar_field;
								
							} else {
								$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
							}
						} else {
							$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
						}
					} else {
						$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
					}

					$uname = '';
					if(isset($this->user_data[$checklist->id]->name)){
						$uname = $this->user_data[$checklist->id]->name;
					} else {
						$uname = 'No author';
					}

					if(isset($this->user_data[$checklist->id]->user_id)){
						$profile_href = '<a href="'.JRoute::_('index.php?option=com_checklist&view=profile&userid='.$this->user_data[$checklist->id]->user_id).'" class="checklist-authorhref">'.$uname.'</a>';
					} else {
						$profile_href = '<span class="checklist-authorhref">'.$uname.'</span>';
					}
				?>
				<div class="checklist-authoricon">
	            	<img width="16" height="16" border="0" alt="<?php echo $uname; ?>" src="<?php echo $avatar_path;?>" class="checklist-avatar"><?php echo $profile_href;?>
	        	</div>
		  	  </div>
			  <div style="float:right;">
			  	<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <?php echo JText::_('COM_CHECKLIST_ACTION')?> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" >
				    <li><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklist->id.$userid);?>"><?php echo JText::_('COM_CHECKLIST_SHOW_CHECKLIST')?></a></li>
					<?php $disabled = (!$this->_allowCopy[$checklist->id]) ? "class='disabled'" : "";
						$href = (!$this->_allowCopy[$checklist->id]) ? "javascript:void(0);" : JRoute::_('index.php?option=com_checklist&task=frontend.clone_checklist&id='.$checklist->id);
					?>
				    <li <?php echo $disabled;?>><a href="<?php echo $href;?>"><?php echo JText::_('COM_CHECKLIST_COPY_TO_MY_CHECKLIST')?></a></li>
				  </ul>
				</div>
				
			  </div>
		  </div>
	  </li>
	<?php }?>
	</ul>

	<div class="checklist-pagination">
		<?php echo $this->pagination->getListFooter(); ?>
	</div>
  </form>
<?php endif;?>