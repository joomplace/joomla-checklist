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
$document->addStyleSheet(JURI::root()."components/com_lightchecklist/assets/css/bootstrap.min.css");
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

	");
?>

<?php if(count($this->checklists)):?>
	
	<ul class="media-list">
	<li class="media">
		<a class="pull-left" href="javascript:void(0);">
		    <img class="media-object" src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/tag_white.png">
		</a>
		<div class="media-body">
			<h3 class="media-heading"><?php echo $this->tag_name;?> <small><?php echo $this->total." ".JText::_('COM_LIGHTCHECKLIST_TOTAL');?></small></h3>
			
		</div>
	</li>
	<?php foreach ($this->checklists as $checklist) {?>
	  <li class="media">
		  <div class="media-body">
		    <h4 class="media-heading"><a href="<?php echo JRoute::_('index.php?option=com_lightchecklist&view=checklist&id='.$checklist->id.'&user_id='.$checklist->user_id); ?>"><?php echo $checklist->title;?></a></h4>
		    <?php echo substr(strip_tags($checklist->description_before), 0, 500);?>
		  </div>
		  <div>
		  	  <div style="float:left;">
		  	  	
		  	  </div>
			  <div style="float:right;">
			  	<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <?php echo JText::_('COM_LIGHTCHECKLIST_ACTION')?> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" >
				    <li><a href="<?php echo JRoute::_('index.php?option=com_lightchecklist&view=checklist&id='.$checklist->id.'&user_id='.$checklist->user_id); ?>"><?php echo JText::_('COM_LIGHTCHECKLIST_SHOW_CHECKLIST')?></a></li>
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