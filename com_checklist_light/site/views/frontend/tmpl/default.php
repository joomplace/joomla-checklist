<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.modal');
JHtml::_('behavior.calendar');

JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_lightchecklist/assets/css/bootstrap.min.css");
$document->addStyleDeclaration("

	#searchForm, #searchForm:before, #searchForm:after {
		-webkit-box-sizing: border-box !important;
	    -moz-box-sizing: border-box !important;
	    box-sizing: border-box !important;
	}

	.media
	{
		margin-right:15px;
		border-bottom: 1px solid #CCCCCC;
	    clear: both;
	    margin: 0 0 15px;
	    padding: 10px 10px 20px;
	}

	.checklist-search-form
	{
	    clear: both;
	    margin: 0 20px 20px;
	    padding: 10px 10px 20px;
	}

	.checklist-extended-search
	{
		display:none;
	}

	#title-search
	{
		height:35px;
		width:50%;
	}

	#title-search-label
	{
		padding:0px;
	}

	.checklist-extended-search input.form-control
	{
		height:35px;
		width:90%;
	}

	#description_search
	{
		width:90%;
	}

	.checklist-tagslinks
	{
		background: url(".JURI::root()."components/com_lightchecklist/assets/images/icon_tags.gif) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    	display: inline-block;
    	font-size: 10px;
    	height: auto;
    	margin-top: 10px;
    	padding: 0 0 5px 18px;
	}

	.media, .media-body {
	    overflow: visible !important;
	}
	.media {
		padding: 10px 10px 45px !important;
	}

	#publish_date
	{
		height:30px;
	}

	.checklist-tagslinks, .checklist-authoricon
	{
		display:inline-block;
	}

	.checklist-authoricon
	{
		padding-left:20px;
	}

	.checklist-authorhref
	{
		margin-left:6px;
	}
	.checklist-avatar
	{
		width:16px;
		height:16px;
	}");

?>

<form id="searchForm" name="searchForm" class="form-horizontal" role="form" method="post" action="<?php echo JURI::root();?>index.php?option=com_lightchecklist&view=frontend">
<div class="checklist-search-form">
	<div class="col-sm-10">
		<input type="text" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_SEARCH_PLACEHOLDER')?>" id="title-search" name="title_search"/>
		<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.resetSearchForm();"><?php echo JText::_('COM_LIGHTCHECKLIST_RESET')?></button>
		<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.openSearchForm();"><?php echo JText::_('COM_LIGHTCHECKLIST_EXTENDED_SEARCH')?></button>

	</div>
</div>
<div style="clear:both;"><br/></div>
<div class="checklist-extended-search">
	  <div class="form-group">
	    <label for="tag_search" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_TAGNAME')?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="tag_search" name="tag_search">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="name_search" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_USERNAME')?></label>
	    <div class="col-sm-10">
	      <input type="text" class="form-control" id="name_search" name="name_search">
	    </div>
	  </div>
	  <div class="form-group">
	    <label for="name_search" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_PUBLISH_DATE')?></label>
	    <div class="col-sm-10">
	      <?php echo JHTML::calendar('', 'publish_date', 'publish_date', '%Y-%m-%d', 'class=inputbox-small"'); ?>
	      
	    </div>
	  </div>
	  <div class="form-group">
		<label for="description_search" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_DESCRIPTION')?></label>
	    <div class="col-sm-10">
			<textarea class="form-control" rows="3" id="description_search" name="description_search"></textarea>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default"><?php echo JText::_('COM_LIGHTCHECKLIST_SEARCH')?></button>
	      <button type="button" class="btn btn-default" onclick="Checklist.closeSearchForm();"><?php echo JText::_('COM_LIGHTCHECKLIST_CLOSE')?></button>
	    </div>
	  </div>
</div>

<?php if(count($this->available_checklists)):?>
	
	<ul class="media-list">
	<?php foreach ($this->available_checklists as $checklist) {?>

	<?php
		$userid = (isset($this->user_data[$checklist->id]->user_id) && $this->user_data[$checklist->id]->user_id) ? '&userid='.$this->user_data[$checklist->id]->user_id : '';
	?>

	  <li class="media">
		  <div class="media-body">
		    <h3 class="media-heading"><a href="<?php echo JRoute::_('index.php?option=com_lightchecklist&view=checklist&id='.$checklist->id.'&user_id='.$checklist->user_id)?>"><?php echo $checklist->title;?></a></h3>
		    <?php echo substr(strip_tags($checklist->description_before), 0, 500);?>
		  </div>
		  <div>
		  	  <div style="float:left;">
		  	  	<?php if(count($this->tags_data[$checklist->id])){
					
					$Itemid = JFactory::getApplication()->input->get('Itemid');
					$tags_arr = array();
					foreach($this->tags_data[$checklist->id] as $tags){
						foreach ($tags as $tag) {
							$tags_arr[] = '<a href="'.JRoute::_('index.php?option=com_lightchecklist&view=tag&id='.$tag['id'].'&Itemid='.$Itemid).'">'.$tag['name'].'</a>';
						}
						
					}

					if(count($tags_arr)){
					 	echo '<div class="checklist-tagslinks">'.implode(" , ", $tags_arr).'</div>';
					} else {
						echo '<div class="checklist-tagslinks">'.JText::_('COM_LIGHTCHECKLIST_UNTAGGED').'</div>';
					}

				} ?>
				<?php
					if(isset($this->user_data[$checklist->id]->avatar_field)){
						if($this->user_data[$checklist->id]->avatar_field != ''){
							if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->user_data[$checklist->id]->user_id.'/thm_'.$this->user_data[$checklist->id]->avatar_field)){
								$avatar_path = JURI::root().'images/checklist/avatar/'.$this->user_data[$checklist->id]->user_id.'/thm_'.$this->user_data[$checklist->id]->avatar_field;
								
							} else {
								$avatar_path = JURI::root().'components/com_lightchecklist/assets/images/no_image.jpg';
							}
						} else {
							$avatar_path = JURI::root().'components/com_lightchecklist/assets/images/no_image.jpg';
						}
					} else {
						$avatar_path = JURI::root().'components/com_lightchecklist/assets/images/no_image.jpg';
					}

					$uname = '';
					if(isset($this->user_data[$checklist->id]->name)){
						$uname = $this->user_data[$checklist->id]->name;
					} else {
						$uname = 'No author';
					}

					if(isset($this->user_data[$checklist->id]->user_id)){
						$profile = '<span class="checklist-authorhref">'.$uname.'</span>';
					} else {
						$profile = '<span class="checklist-authorhref">'.$uname.'</span>';
					}
				?>
				<div class="checklist-authoricon">
                	<img width="16" height="16" border="0" alt="<?php echo $uname; ?>" src="<?php echo $avatar_path;?>" class="checklist-avatar"><?php echo $profile;?>
            	</div>
		  	  </div>
			  <div style="float:right;">
			  	<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				    <?php echo JText::_('COM_LIGHTCHECKLIST_ACTION')?> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" >
				    <li><a href="<?php echo JRoute::_('index.php?option=com_lightchecklist&view=checklist&id='.$checklist->id.$userid);?>"><?php echo JText::_('COM_LIGHTCHECKLIST_SHOW_CHECKLIST')?></a></li>
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
<script type="text/javascript">
	
	var Checklist = {};

	Checklist.openSearchForm = function()
	{
		jQuery(".checklist-extended-search").slideDown(300);
		return false;
	}

	Checklist.closeSearchForm = function()
	{
		jQuery(".checklist-extended-search").slideUp(300);
		return false;
	}

	Checklist.resetSearchForm = function()
	{
		jQuery("#title-search").val("");
		jQuery("#tag_search").val("");
		jQuery("#name_search").val("");
		jQuery("#description_search").val("");

		var form = document.searchForm;
		form.submit();

	}

</script>






