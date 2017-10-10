<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
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
$document->addScriptDeclaration(
	'window.addEvent(\'domready\', function() {Calendar.setup({
	// Id of the input field
	inputField: "publish_date",
	// Format of the input field
	ifFormat: "%Y-%m-%d",
	// Trigger for the calendar (button ID)
	button: "publish_date_img",
	// Alignment (defaults to "Bl")
	align: "B1",
	singleClick: true,
	firstDay: 0
	});});'
);
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/frontend.css");
$document->addStyleDeclaration("	
	.checklist-tagslinks
	{
		background: url(".JURI::root()."components/com_checklist/assets/images/icon_tags.gif) no-repeat scroll 0 0 rgba(0, 0, 0, 0);
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

<form id="searchForm" name="searchForm" class="form-horizontal" role="form" method="post" action="<?php echo JURI::root();?>index.php?option=com_checklist&view=frontend">
<div class="checklist-search-form">
	<input type="text" placeholder="<?php echo JText::_('COM_CHECKLIST_SEARCH_PLACEHOLDER')?>" id="title-search" name="title_search"/>
	<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.resetSearchForm();"><?php echo JText::_('COM_CHECKLIST_RESET')?></button>
	<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.openSearchForm();"><?php echo JText::_('COM_CHECKLIST_EXTENDED_SEARCH')?></button>	
</div>
<div style="clear:both;"><br/></div>
<div class="checklist-extended-search">
	  <div class="form-group">
	    <label for="tag_search"><?php echo JText::_('COM_CHECKLIST_TAGNAME')?></label>
	    <input type="text" class="form-control" id="tag_search" name="tag_search" />
	  </div>
	  <div class="form-group">
	    <label for="name_search"><?php echo JText::_('COM_CHECKLIST_USERNAME')?></label>
	    <input type="text" class="form-control" id="name_search" name="name_search" />
	  </div>
	  <div class="form-group">
	    <label for="name_search"><?php echo JText::_('COM_CHECKLIST_PUBLISH_DATE')?></label>
	    <br/>
	   	<input type="text" class="inputbox-small" value="" id="publish_date" name="publish_date" title="" />
		<button id="publish_date_img" class="btn" type="button">
			<i class="glyphicon glyphicon-calendar"></i>
		</button>
	  </div>
	  <div class="form-group">
		<label for="description_search"><?php echo JText::_('COM_CHECKLIST_DESCRIPTION')?></label>
		<textarea class="form-control" rows="3" id="description_search" name="description_search"></textarea>
	  </div>
	  <div class="form-group">
	    <button type="submit" class="btn btn-default"><?php echo JText::_('COM_CHECKLIST_SEARCH')?></button>
	    <button type="button" class="btn btn-default" onclick="Checklist.closeSearchForm();"><?php echo JText::_('COM_CHECKLIST_CLOSE')?></button>
	  </div>
</div>
<div class="checklist-separator"></div>
<table cellspacing="0" cellpadding="0" border="0" id="checklist_order_header">
    <tbody>
    	<tr>
        	<td valign="center" align="left" style="vertical-align:middle;">
	            <a href="<?php echo JURI::root();?>index.php?option=com_checklist&view=frontend&liststyle=grid">
	                <?php echo $this->available_checklists[0]->lists['grid_img']; ?>
	            </a>
	            <a href="<?php echo JURI::root();?>index.php?option=com_checklist&view=frontend&liststyle=list">
	                <?php echo $this->available_checklists[0]->lists['list_img']; ?>
	            </a>

	            &nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_CHECKLIST_SORT_BY');?>&nbsp;
	            <?php echo $this->available_checklists[0]->lists['sort_by']; ?>
				&nbsp;
				<?php echo $this->available_checklists[0]->lists['order_dir'];?>
	        </td>
	    </tr>
	    <tr>
	        <td style="height: 1px;">&nbsp;</td>
	    </tr>
	</tbody>
</table>
<div style="clear:both"><br/></div>
</form>
<form id="adminForm" name="adminForm" class="form-horizontal" role="form" method="post" action="/<?php echo JURI::root(true);?>index.php?option=com_checklist&view=frontend">
<?php if(count($this->available_checklists)):?>
	
	<?php if($this->available_checklists[0]->lists['liststyle'] == 'list'){?>

		<ul class="media-list">
		<?php foreach ($this->available_checklists as $checklist) {?>

		<?php
			$userid = (isset($this->user_data[$checklist->id]->user_id) && $this->user_data[$checklist->id]->user_id) ? '&userid='.$this->user_data[$checklist->id]->user_id : '';
		?>

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

	<?php } elseif($this->available_checklists[0]->lists['liststyle'] == 'grid'){?>

		<?php $count_row = (count($this->available_checklists)) ? round(count($this->available_checklists)/2) : 0; 
			$checklists = $this->available_checklists;
		?>

		<table class="checklist_list_container" width="100%" align="center" cellspacing="0" cellpadding="0">
			<?php $j = 0; ?>
			<?php for ($i = 0; $i < $count_row; $i++) {?>
			<tr>
				<?php for($n =0; $n < 2; $n++){?>
					<?php if(isset($checklists[$j])){?>
						<?php

							$userid = (isset($this->user_data[$checklists[$j]->id]->user_id) && $this->user_data[$checklists[$j]->id]->user_id) ? '&userid='.$this->user_data[$checklists[$j]->id]->user_id : '';

						?>
						<td class="checklist_grid_row" width="50%">
							<div class="cell_container">
								<h3 class="media-heading"><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklists[$j]->id.'&user_id='.$checklists[$j]->user_id)?>"><?php echo $checklists[$j]->title;?></a></h3>

								<?php if($this->config->rating_option){?>
					    		<div class="rating hasTooltip" data-average="<?php echo (($checklists[$j]->average_rating) ? $checklists[$j]->average_rating : 0);?>" data-id="<?php echo $checklists[$j]->id?>" title="<strong><?php echo JText::_('COM_CHECKLIST_RATING')?>&nbsp;&nbsp;</strong><span style='color:green; font-size: 20px;''><?php echo (($checklists[$j]->average_rating) ? number_format($checklists[$j]->average_rating, 2, ".", ",") : "0.00");?></span><?php echo JText::_('COM_CHECKLIST_OUT_OF')?><span style='font-size: 17px; font-weight:bold;'>5.00</span>"></div>
					    		<div style="clear:both;"><br/></div>
								<?php }?>

					    		<div class="checklist-description">
									<?php echo substr(strip_tags($checklists[$j]->description_before), 0, 300)."...";?>
					    		</div>

					    		<div class="checklist-info">
							  	  	<?php if(count($this->tags_data[$checklists[$j]->id])){
										
										$Itemid = JFactory::getApplication()->input->get('Itemid');
										$tags_arr = array();
										foreach($this->tags_data[$checklists[$j]->id] as $tags){
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
									<?php
										if(isset($this->user_data[$checklists[$j]->id]->avatar_field)){
											if($this->user_data[$checklists[$j]->id]->avatar_field != ''){
												if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$this->user_data[$checklists[$j]->id]->user_id.'/thm_'.$this->user_data[$checklists[$j]->id]->avatar_field)){
													$avatar_path = JURI::root().'images/checklist/avatar/'.$this->user_data[$checklists[$j]->id]->user_id.'/thm_'.$this->user_data[$checklists[$j]->id]->avatar_field;
													
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
										if(isset($this->user_data[$checklists[$j]->id]->name)){
											$uname = $this->user_data[$checklists[$j]->id]->name;
										} else {
											$uname = 'No author';
										}

										if(isset($this->user_data[$checklists[$j]->id]->user_id)){
											$profile_href = '<a href="'.JRoute::_('index.php?option=com_checklist&view=profile&userid='.$this->user_data[$checklists[$j]->id]->user_id).'" class="checklist-authorhref">'.$uname.'</a>';
										} else {
											$profile_href = '<span class="checklist-authorhref">'.$uname.'</span>';
										}
									?>
									<div class="checklist-authoricon">
					                	<img width="16" height="16" border="0" alt="<?php echo $uname; ?>" src="<?php echo $avatar_path;?>" class="checklist-avatar"><?php echo $profile_href;?>
					            	</div>
									
							  	</div>
							  	<div class="checklist-tools">

									<div class="btn-group">
										  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
										    <?php echo JText::_('COM_CHECKLIST_ACTION')?> <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" >
										    <li><a href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&id='.$checklists[$j]->id.$userid);?>"><?php echo JText::_('COM_CHECKLIST_SHOW_CHECKLIST')?></a></li>
											<?php $disabled = (!$this->_allowCopy[$checklists[$j]->id]) ? "class='disabled'" : "";
												$href = (!$this->_allowCopy[$checklists[$j]->id]) ? "javascript:void(0);" : JRoute::_('index.php?option=com_checklist&task=frontend.clone_checklist&id='.$checklists[$j]->id);
											?>
										    <li <?php echo $disabled;?>>
										    	<a href="<?php echo $href;?>"><?php echo JText::_('COM_CHECKLIST_COPY_TO_MY_CHECKLIST')?></a>
										    </li>

										  </ul>
									</div>
										
								</div>
							</div>
						</td>
					<?php } else {?>
						<td class="checklist_grid_row" width="50%"></td>
					<?php }?>

				<?php $j++; ?>
				<?php }?>
			</tr>
			<?php }?>
		</table>

	<?php }?>

	<div class="checklist-pagination">
		<?php echo $this->pagination->getListFooter(); ?>
	</div>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->available_checklists[0]->lists['filter_order_Dir'];?>" />
	<input type="hidden" name="liststyle" value="<?php echo $this->available_checklists[0]->lists['liststyle'];?>" />
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






