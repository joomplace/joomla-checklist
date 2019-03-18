<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip');

$print = JFactory::getApplication()->input->get('print', '0');
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/default_template/checklist.css");

if($print){
	JHtml::_('bootstrap.framework');
	$document->addStyleSheet('media'.DIRECTORY_SEPARATOR.'jui'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.css');
}

if($this->allow_edit && $this->edit_mode){
	$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/zebra_dialog.css");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/jquery-ui-1.9.2.custom.min.js");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/zebra_dialog.js");
}

$document->addScript(JURI::root()."components/com_checklist/assets/js/modernizr-2.6.2.js");
$document->addScript(JURI::root()."components/com_checklist/assets/js/default_template/script.js");
$document->addScript(JURI::root()."components/com_checklist/assets/js/jquery-scrolltofixed-min.js");

if($this->config->rating_option){

	$document->addStylesheet(JURI::root()."components/com_checklist/assets/js/jnotify-master/jNotify.jquery.css");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/jnotify-master/jNotify.jquery.min.js");
	$document->addStylesheet(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.css");
	$document->addScript(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.min.js");

?>
<script type="text/javascript">
	jQuery(document).ready(function(event){
		jQuery(".rating").jRating({

		  step: 1,
		  rateMax: 5,
		  <?php if($this->checklist->rated):?>
		  isDisabled : true,
		  <?php endif;?>
		  phpPath:'<?php echo JURI::root();?>index.php?option=com_checklist&task=checklist.saverate&tmpl=component',
		  onSuccess : function(){
			jSuccess('<?php echo JText::_("COM_CHECKLIST_YOUR_RATE_HAS_BEEN_SAVED")?>',{
			  HorizontalPosition:'center',
			  VerticalPosition:'top'
			});
		  },
		  onError : function(){
			jError('<?php echo JText::_("COM_CHECKLIST_ERROR_PLEASE_RETRY")?>');
		  }

		});
	});
</script>
<?php } ?>

<style>
	
	@font-face {
		font-family: 'halflings';
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.eot');
		src:url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.woff') format('woff'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
			url('<?php echo JURI::root();?>components/com_checklist/assets/css/default_template/fonts/glyphicons-halflings-regular.svg#halflings') format('svg');
		font-weight: normal;
		font-style: normal;
	}

	<?php if($this->allow_edit && $this->edit_mode){?>
	.li-handle label:hover{
		cursor:move;
	}

	.checklist-section-header:hover{
		cursor: move;
	}

	
	<?php } ?>
	
</style>

<?php if($this->allow_edit && $this->edit_mode){?>
<link rel="stylesheet" href="<?php echo JURI::root();?>components/com_checklist/assets/css/wmd-buttons.css">
<script src="<?php echo JURI::root();?>components/com_checklist/assets/js/Markdown.Converter.js" type="text/javascript"></script>
<script src="<?php echo JURI::root();?>components/com_checklist/assets/js/Markdown.Sanitizer.js" type="text/javascript"></script>
<script src="<?php echo JURI::root();?>components/com_checklist/assets/js/Markdown.Editor.js" type="text/javascript"></script>
<script src="<?php echo JURI::root();?>components/com_checklist/assets/js/jquery.markdown.js" type="text/javascript"></script>
<?php } ?>

<?php

$lang = JFactory::getLanguage();
$tag = $lang->getTag();
$tag = str_replace("-", "_", $tag);

?>

<?php if ($this->config->social_facebook_use == 1) {?>
<div id="fb-root"></div>
<script type="text/javascript">
	(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/<?php echo $tag;?>/all.js#xfbml=1";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
<?php } ?>

<div style="margin-left:5px;" id="checklist-root">

<div class="alert alert-success"></div>
<div class="alert alert-danger"></div>

<?php if($this->allow_edit && $this->edit_mode){?>

<div class="chk-toolbar">
	<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.openAddGroupForm();"><?php echo JText::_('COM_CHECKLIST_ADD_GROUP')?></button>
</div>
<div class="chk-add-group">
	<form class="form-horizontal" role="form">
		<div class="form-group">
			<label for="inputGroup" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_GROUP_NAME')?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="inputGroup" placeholder="<?php echo JText::_('COM_CHECKLIST_GROUP_NAME_PLACEHOLDER')?>">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="button" class="btn btn-default" onclick="Checklist.ajaxSaveGroup();"><?php echo JText::_('COM_CHECKLIST_SAVE')?></button>
				<button type="button" class="btn btn-default" onclick="Checklist.closeAddGroupForm();"><?php echo JText::_('COM_CHECKLIST_CANCEL')?></button>
				<img src="<?php echo JURI::root()?>components/com_checklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader"/>
			</div>
		</div>
	</form>
</div>
<hr/>
<?php }?>

<?php if($this->checklist):?>

<?php if($print):?>
<style type="text/css">

	.checklist-edit-mode, .chk-toolbar, .chk-add-group
	{
		display:none;
	}

	#checklist #chk-main .checklist-section-list > li > label:before {
		visibility: hidden;
	}

	#checklist #chk-main .checklist-section-list > li > input[type="checkbox"] {
		visibility: visible;
	}

	.progress
	{
		display: none;
	}

	.expand-collapse-all
	{
		float: right;
		margin: 10px;
	}

	@media print {
	
		.caret, .glyphicon-info-sign, .checklist-rating > strong, .rating
		{
			visibility: hidden;
		}
		
		.chk-checkbox
		{
			transform: scale(1.8, 1.8);
		}

		.expand-collapse-all
		{
			display:none;
		}
	}

</style>
<div class="btn hidden-print" id="pop-print">
	<a onclick="printBlank();" href="javascript:void(0);">
		<span class="icon-print"></span>&nbsp;<?php echo JText::_('COM_CHECKLIST_PRINT_BLANK');?>&nbsp;
	</a>
</div>
<div class="btn hidden-print" id="pop-print">
	<a onclick="printCurrent();" href="javascript:void(0);">
		<span class="icon-print"></span>&nbsp;<?php echo JText::_('COM_CHECKLIST_PRINT_CURRENT_STATE');?>&nbsp;
	</a>
</div>
<div style="clear:both;"><br/></div>
<script type="text/javascript">
	
	function printBlank(){

		jQuery(".chk-checkbox").prop('checked', false);
		
		window.print();
		return false;
	}

	function printCurrent(){
		
		window.print();
		return false;
	}

	function expandAllItems()
	{
		jQuery(".panel-collapse").css('display', 'block');
		return true;
	}

	function collapseAllItems()
	{
		jQuery(".panel-collapse").css('display', 'none');
		return true;
	}

</script>
<?php endif;?>

<?php
$checklistUserName = JFactory::getUser($this->checklist->user_id)->name;
$checklistUserName = !empty($checklistUserName) ? ' (' . $checklistUserName . ')' : '';
?>

<div id="checklist">
	<header itemscope="" itemtype="http://schema.org/WebApplication">
		<h1 itemprop="name"><?php echo $this->checklist->title . $checklistUserName; ?></h1>
	</header>
	
    <div class="progress progress-striped">
	      <span id="keep-going" class="label label-warning" style="opacity:1;"><?php echo JText::_('COM_JOOMLAQUIZ_KEEP_GOING');?></span>
		  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
			<span class="sr-only"></span>
		  </div>
		  <span id="well-done" class="label label-success"><?php echo JText::_('COM_JOOMLAQUIZ_WELL_DONE');?></span>
	      <a href="javascript:void(0);" id="reset"><span class="label label-default"><?php echo JText::_('COM_CHECKLIST_RESET')?></span></a>
    </div>
	
	<?php if($this->config->rating_option){?>
	<div class="checklist-rating">
		<?php if(!$this->allow_edit):?>
		<strong style="float:left;line-height:23px;"><?php echo JText::_('COM_CHECKLIST_RATE')?>&nbsp;&nbsp;</strong><div class="rating" data-average="<?php echo (($this->checklist->average_rating) ? $this->checklist->average_rating : 0);?>" data-id="<?php echo $this->checklist->id?>"></div>
		<?php endif;?>
		<div class="rating-result"><strong><?php echo JText::_('COM_CHECKLIST_RATING')?>&nbsp;&nbsp;</strong><span style="color:green; font-size: 20px;"><?php echo (($this->checklist->average_rating) ? number_format($this->checklist->average_rating, 2, ".", ",") : "0.00");?></span><?php echo JText::_('COM_CHECKLIST_OUT_OF')?><span style="font-size: 17px; font-weight:bold;">5.00</span><?php echo JText::_('COM_CHECKLIST_FROM')?><span><?php echo (($this->checklist->rated_users) ? $this->checklist->rated_users : 0);?></span><?php echo JText::_('COM_CHECKLIST_USERS')?></div>
	</div>
	<div style="clear:both"></div>
	<?php }?>

	<div class="checklist-edit-mode">
	<a class="hasTooltip" href="<?php echo JRoute::_('index.php?option=com_checklist&view=lists'); ?>" title="<?php echo JText::_('COM_CHECKLIST_GO_TO_MY_CHECKLIST');?>">
		<span class="glyphicon glyphicon-user"></span>
	</a>
	<?php if($this->config->print_option || $this->config->pdf_option):?>
		<?php if(!$print):?>
			<div class="btn-group chk-print-group">
				<a href="#" data-toggle="dropdown" class="btn btn-mini dropdown-toggle">
					<span class="glyphicon glyphicon-cogwheel"></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<?php if($this->config->print_option){?>
					<li>
						<a onclick="userAction('print'); return false;" href="javascript: void(0);">
							<?php echo JText::_('COM_CHECKLIST_PRINT');?>
						</a>
					</li>
					<?php } ?>
					<?php if($this->config->pdf_option){?>
					<li>
						<a onclick="userAction('pdf'); return false;" href="javascript: void(0);">
							<?php echo JText::_('COM_CHECKLIST_PDF');?>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		<?php endif;?>
	<?php endif;?>

	<?php if($this->allow_edit){?>
	
		<?php if($this->config->moderateChecklist && !$this->checklist->default && !$this->checklist->already_sent){ ?>
		<button type="button" id="checklist-send-request" class="btn btn-primary btn-lg" onclick="Checklist.sendRequest();"><?php echo JText::_('COM_CHECKLIST_SEND_REQUEST')?></button>
		<?php } ?>
		<?php if(!$this->edit_mode):?>
		<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.editModeOn();"><?php echo JText::_('COM_CHECKLIST_EDIT_MODE')?></button>
		<?php endif;?>
		<?php if($this->edit_mode):?>
		<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.viewModeOn();"><?php echo JText::_('COM_CHECKLIST_VIEW_MODE')?></button>
		<?php endif;?>
	
	<?php } ?>
	</div>
	<div style="clear:both;"></div>

	<p><?php echo $this->checklist->description_before;?></p>

	<?php if($print):?>
	<div class="expand-collapse-all">
		<a href="javascript:void(0);" onclick="expandAllItems();" class="btn btn-small"><?php echo JText::_('COM_CHECKLIST_EXPAND_ALL');?></a>
		<a href="javascript:void(0);" onclick="collapseAllItems();" class="btn btn-small"><?php echo JText::_('COM_CHECKLIST_COLLAPSE_ALL');?></a>
	</div>
	<div style="clear:both;"><br/></div>
	<?php endif;?>

	<div id="chk-main" class="checklist-container">
		<?php if(count($this->groups)):?>
		<?php foreach($this->groups as $group):?>
		<section class="checklist-section" id="<?php echo $group->section_id;?>" groupid="<?php echo $group->id;?>">
			<h2 class="checklist-section-header"><?php echo $group->title;?>
				<a class="accordion-toggle active" data-toggle="collapse" data-parent="#<?php echo $group->section_id;?>" href="#collapse<?php echo $group->id;?>">
				<span class="caret"></span></a>
				<?php if($this->allow_edit && $this->edit_mode){?>
				<span class="chk-add-item">
					<img src="<?php echo JURI::root();?>components/com_checklist/assets/images/minus.png" style="cursor:pointer;" class="chk-ajax-remove-group hasTooltip" title="<?php echo JText::_('COM_CHECKLIST_REMOVE_BLOCK');?>">&nbsp;<img src="<?php echo JURI::root();?>components/com_checklist/assets/images/list_add.png" style="cursor:pointer;" class="chk-open-item-form hasTooltip" title="<?php echo JText::_('COM_CHECKLIST_ADD_TASK');?>">
				</span>
				<?php } ?>
				
			</h2>

			<?php if($this->allow_edit && $this->edit_mode){?>
			<div class="chk-add-item-form">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-12">
							<input type="text" class="form-control" id="inputItem<?php echo $group->id;?>" placeholder="<?php echo JText::_('COM_CHECKLIST_TASK_NAME')?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">							
							<textarea class="form-control" rows="3" id="inputTips<?php echo $group->id;?>"></textarea>
						</div>
					</div>
					<div class="form-group">
						<a href="javascript: void(0);" gid="<?php echo $group->id?>" class="chr-dialog-window"><?php echo JText::_('COM_CHECKLIST_EDIT_WITH_MARKDOWN')?></a>&nbsp;<img src="<?php echo JURI::root()?>components/com_checklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader-editor<?php echo $group->id;?>"/>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<label for="inputOptional<?php echo $group->id;?>">
								<input class="chk-optional" type="checkbox" value="1" id="inputOptional<?php echo $group->id;?>"><?php echo JText::_('COM_CHECKLIST_OPTIONAL')?>
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-12">
							<button groupid="<?php echo $group->id;?>" type="button" class="btn btn-sm chk-save-item"><?php echo JText::_('COM_CHECKLIST_SAVE')?></button>
							<button type="button" class="btn btn-sm chk-close-item-form"><?php echo JText::_('COM_CHECKLIST_CANCEL')?></button>
							<img src="<?php echo JURI::root()?>components/com_checklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader<?php echo $group->id;?>"/>
						</div>
					</div>
				</form>
			</div>
			<?php } ?>
		
			<ul class="checklist-section-list accordion-body collapse in" id="collapse<?php echo $group->id;?>">
				<?php if(count($group->items)):?>
				<?php foreach($group->items as $i => $item):?>
				<li <?php if($item->optional){?> class="li-handle optional" <?php } else {?> class="li-handle" <?php }?> itemid="<?php echo $item->id;?>">
					<input type="checkbox" id="<?php echo $item->input_id;?>" tabindex="<?php echo ($i+1);?>" class="chk-checkbox">
					<label for="<?php echo $item->label_for;?>"><?php echo $item->task;?>
                        <?php if(trim(strip_tags($item->tips))){ ?>
                            <a data-toggle="collapse" data-parent="#chk-main" class="checklist-info-icon" id="details-<?php echo $item->input_id;?>"><span class="glyphicon glyphicon-info-sign"></span></a>
                            <?php if($this->allow_edit && $this->edit_mode){?>
                                <img src="<?php echo JURI::root();?>components/com_checklist/assets/images/trash_recyclebin_empty_closed_w.png" class="chk-tools" onclick="Checklist.ajaxRemoveItem(this, event);"/><img src="<?php echo JURI::root();?>components/com_checklist/assets/images/pencil2.png" class="chk-tools pencil" onclick="Checklist.editItem(this, event);"/>
                            <?php } ?>
                        <?php } ?>
                    </label>
                    <?php if(trim(strip_tags($item->tips))){ ?>
                        <ul class="panel-collapse collapse">
                            <?php echo $item->tips;?>
                        </ul>
                    <?php } ?>
                </li>
				<?php endforeach;?>
				<?php endif;?>
			</ul>
		</section>
		<?php endforeach;?>
		<?php endif;?>
	</div>
	<p><?php echo $this->checklist->description_after;?></p>
</div>

<input type="hidden" name="checklist_id" id="checklist_id" value="<?php echo $this->checklist->id;?>" />
<?php endif;?>

<div style="clear: both;"></div>

<?php

	$itemid = JFactory::getApplication()->input->get('Itemid', 0);
	$itemid = ($itemid) ? $itemid : '';

	$userid = JFactory::getApplication()->input->get('userid', 0);
	$userid = ($userid) ? $userid : $user->id;

    $custom_metatags = json_decode($this->checklist->custom_metatags);
?>

<?php if(!$print):?>
<div class="checklist-social">
<?php
	
	$pageLink = JRoute::_('index.php?option=com_checklist&view=checklist&id='.$this->checklist->id.'&userid='.$userid.'&Itemid='.$itemid, false, -1);

?>
	<div class="checklist-social-btn">

		<?php if($this->config->social_google_plus_use):?>
		<!-- Google plus -->
		<div class="checklist-social-btn">
			<div class="g-plusone" data-width="70" data-size="<?php echo $this->config->social_google_plus_size?>" data-annotation="<?php echo $this->config->social_google_plus_annotation ?>" href="<?php echo $pageLink ?>">
			</div>
		</div>
		<?php endif;?>

		<?php if($this->config->social_twitter_use):?>
		<!-- Twitter -->
            <div class="checklist-social-btn">
                <a href="https://twitter.com/intent/tweet" class="twitter-share-button"
                   data-text="<?php echo $custom_metatags->{'twitter:title'}; ?>"
                   data-url="<?php echo $pageLink ?>"
                   data-size="<?php echo $this->config->social_twitter_size ?>"
                   data-count="<?php echo $this->config->social_twitter_annotation?>"
                   data-lang="<?php echo $this->config->social_twitter_language ?>">Tweet</a>
            </div>
		<?php endif;?>

		<?php if($this->config->social_linkedin_use):?>
		<!-- LinkedIn -->
		<div class="checklist-social-btn">
			<script type="IN/Share" data-url="<?php echo $pageLink ?>" data-counter="<?php echo $this->config->social_linkedin_annotation ?>"></script>
		</div>
		<?php endif;?>

		<?php if($this->config->social_facebook_use):?>
		<!-- Facebook -->
		<div class="checklist-social-btn">
			<div class="fb-like" data-show-faces="false" data-width="50" data-colorscheme="light" data-share="false" data-action="<?php echo $this->config->social_facebook_verb ?>" data-layout="<?php echo $this->config->social_facebook_layout ?>" data-href="<?php echo $pageLink ?>">
			</div>
		</div>
		<?php endif;?>
	</div>
</div>
<div style="clear: both;"><br/><br/></div>
<?php endif;?>

<script type="text/javascript">
			
	var chk_base_URL = '<?php echo JURI::root();?>';
	var checkedArray = new Array();
	var chk_authorized = false;
	<?php if($this->authorized):?>
	chk_authorized = true;
	<?php endif;?>

	<?php if(count($this->groups) && $this->authorized):?>
	<?php foreach($this->groups as $group):?>
		
	<?php if(count($group->items) && count($this->checkedList)):?>
	<?php foreach($group->items as $i => $item):?>

	checkedArray.push(<?php echo ((isset($this->checkedList[$item->id]) && $this->checkedList[$item->id]) ? $this->checkedList[$item->id] : 0);?>);<?php echo "\n";?>

	<?php endforeach;?>
	<?php endif;?>
	<?php endforeach;?>
	<?php endif;?>

	var Checklist = {
		
		showItemTools: function(element){
			jQuery(element).find(".chk-add-item").show();
		},
		
		hideItemTools: function(element){
			jQuery(element).find(".chk-add-item").hide();
		},
		
		openAddGroupForm: function(){
			jQuery(".chk-add-group").slideDown(1000);
			return true;
		},
		
		closeAddGroupForm: function(){
			Checklist.resetErrorMsg();
			jQuery(".chk-add-group").slideUp(1000);
			return true;
		},
		
		ajaxSaveGroup: function(){
			Checklist.resetErrorMsg();
			var inputGroup = jQuery("#inputGroup").val();
			var checklist_id = jQuery("#checklist_id").val();
			
			if(inputGroup == '') {
				Checklist.setErrorMsg("danger", "<?php echo JText::_('COM_CHECKLIST_INPUT_NAME_OF_NEW_GROUP')?>");
				jQuery("#inputGroup").focus();
				return false;
			}
			
			jQuery("#ajax-loader").show();
			Checklist.doAjax('checklist.addgroup', {title: inputGroup, id: checklist_id}, Checklist.addNewGroup);
		},
		
		addNewGroup: function(html){
			
			jQuery("#ajax-loader").hide();
			Checklist.resetErrorMsg();
			jQuery("#inputGroup").val("");
			
			jQuery("#chk-main").append(html);
			
			Checklist.bindItemHeaderTools();
			Checklist.bindShowsGroupTools();
			Checklist.initGroupsSortables();
			Checklist.focusInputs();
			Checklist.bindDialogLinks();
			
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_CHECKLIST_GROUP_WAS_SUCCESSFULLY_ADDED')?>");
		},
		
		openItemForm: function(object){
			Checklist.resetErrorMsg();
			jQuery(object).parent().parent().parent().find(".chk-add-item-form").show();
		},
		
		closeItemForm: function(object){
			Checklist.resetErrorMsg();
			jQuery(object).parent().parent().parent().parent().parent().find(".chk-add-item-form").hide();
		},
		
		ajaxSaveItem: function(object, groupid){
			
			Checklist.resetErrorMsg();
			
			var checklist_id = jQuery("#checklist_id").val();
			var title = jQuery("#inputItem" + groupid).val();
			var tips = jQuery("#inputTips" + groupid).val();		
			var optional = (typeof(jQuery("#inputOptional" + groupid + ":checked").val()) !== 'undefined') ? 1 : 0;
			
			if(title == '' && tips == ''){
				Checklist.setErrorMsg("danger", "<?php echo JText::_('COM_CHECKLIST_INPUT_NAME_OF_NEW_ITEM')?>");
				return false;
			}
			
			jQuery("#ajax-loader" + groupid).show();
			Checklist.doAjax('checklist.additem', {title: title, tips: tips, id: checklist_id, groupid: groupid, optional: optional, template: 'default'}, Checklist.addNewItem, object);
			
		},
		
		addNewItem: function(html, object){
			
			jQuery(object).next().next().css("display", "none");
			var parent_div = jQuery(object).parent().parent().parent().parent();
			parent_div.find(".form-control").val("");
			parent_div.find("input[type='checkbox']").prop("checked", "");
			parent_div.slideUp(300);
			
			var ul = parent_div.next();
			ul.append(html);
			
			Checklist.bindDialogLinks();
			Checklist.bindShowsItemTools();
			Checklist.initItemsSortable();
			setTimeout(Checklist.bindClickToTips, 200);
			setTimeout(Checklist.bindChecked, 200);
			
		},
		
		bindClickToTips: function(){
			
			findCheckboxes();
			initialize();
			calculateProgress();
			reset();
			
			if (localStorage.length === 0) {

				if ( /Safari/i.test(navigator.userAgent)){
					
					var a = details[0];
					var evObj = document.createEvent("MouseEvents");
					evObj.initMouseEvent("click", true, true, window);
					
				} else{
					details[0].click();
				}
			}
		},
		
		ajaxRemoveGroup: function(object, groupid){
		
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.resetErrorMsg();
			
			jQuery.Zebra_Dialog('<?php echo JText::_('COM_CHECKLIST_ARE_YOU_SURE_TO_WANT_TO_REMOVE_GROUP')?>', {
				'type':     'question',
				'title':    'Confirm window',
				'buttons':  ['Confirm', 'Cancel'],
				'onClose':  function(caption) {
					if(caption == '<?php echo JText::_('COM_CHECKLIST_CONFIRM')?>'){
						jQuery('body').css("cursor", "wait");
						Checklist.doAjax('checklist.removegroup', {id: checklist_id, groupid: groupid}, Checklist.removeGroup, object);
					} else {
						return true;
					}
				}
			});
			
		},
		
		removeGroup: function(html, object){
			
			jQuery(object).parent().parent().parent().remove();
			jQuery('body').css("cursor", "default");
			setTimeout(Checklist.bindClickToTips, 200);
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_CHECKLIST_GROUP_WAS_SUCCESSFULLY_REMOVED')?>");
			return false;
			
		},
		
		ajaxRemoveItem: function(element, event){
			
			Checklist.PreventDefaultAction(event);
			
			var checklist_id = jQuery("#checklist_id").val();
			var itemid = jQuery(element).parent().parent().attr("itemid");
			var groupid = jQuery(element).parent().parent().parent().parent().attr("groupid");
			
			jQuery('body').css("cursor", "wait");
			Checklist.doAjax('checklist.removeitem', {id: checklist_id, groupid: groupid, item: itemid}, Checklist.removeItem, element);
		},
		
		removeItem: function(html, element){
			
			jQuery(element).parent().parent().remove();
			jQuery('body').css("cursor", "default");
			setTimeout(Checklist.bindClickToTips, 200);
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_CHECKLIST_ITEM_WAS_SUCCESSFULLY_REMOVED')?>");
			return true;
		},
		
		editItem: function(element, event){
			
			Checklist.PreventDefaultAction(event);
			
			var parent_li = jQuery(element).parent().parent();
			var parent_section = parent_li.parent().parent();
			
			var checklist_id = jQuery("#checklist_id").val();
			var itemid = parent_li.attr("itemid");
			var groupid = parent_section.attr("groupid");
			
			jQuery('body').css("cursor", "wait");
			Checklist.doAjax('checklist.getitem', {id: checklist_id, groupid: groupid, item: itemid}, Checklist.showEditItem, element);
			
		},
		
		showEditItem: function(html, element){
			
			var windowWidth = jQuery(window.document).width();
			var arrow_width = 15;
			var label_height = 25;
			var parent_li = jQuery(element).parent().parent();
			var itemid = parent_li.attr("itemid");
			var offset = parent_li.offset();
			var x = offset.left;
			var y = offset.top;
			var width = parent_li.width();
						
			jQuery('body').append(html);
			
			var edit_form = document.getElementById('chk-edititem-form-' + itemid);
			var formWidth = jQuery(edit_form).width();

			if((formWidth + 100) <= windowWidth){
				var xCoord = x + width;
				if(xCoord > (windowWidth - width)){
					edit_form.style.left = x + arrow_width + "px";
				} else {
					edit_form.style.left = (x + width) + arrow_width + "px";
				}
			} else {
				edit_form.style.left = (x + arrow_width) + "px";
			}

			edit_form.style.top = (y + parseInt(label_height/2)) + "px";
			
			jQuery(edit_form).animate({opacity: 1});
			jQuery('body').css("cursor", "default");
		},
		
		closeEditItemForm: function(itemid){
			jQuery("#chk-edititem-form-"+itemid).remove();
		},
		
		ajaxChangedItem: function(element){
		
			var checklist_id = jQuery("#checklist_id").val();
			var itemid = jQuery(element).attr("itemid");
			var groupid = jQuery(element).attr("groupid");
			var title = jQuery("#inputItem_" + itemid).val();
			var tips = jQuery("#inputTips_" + itemid).val();
			var optional = (typeof(jQuery("#inputOptional_" + itemid + ":checked").val()) !== 'undefined') ? 1 : 0;
			
			jQuery("#ajax_loader_" + itemid).show();
			Checklist.doAjax('checklist.ajaxsaveitem', {id: checklist_id, groupid: groupid, item: itemid, title: title, tips: tips, optional:optional}, Checklist.showSavedItem, element);
			
		},
		
		showSavedItem: function(json, element){
			
			var data = JSON.parse(json);
			jQuery("#ajax_loader_" + data['itemid']).hide();
			
			var changed_li = jQuery('section').filter(function(){
				return jQuery(this).attr("groupid") == data['groupid'];
			}).find('li').filter(function(){
				return jQuery(this).attr("itemid") == data['itemid'];
			});
			
			if(parseInt(data['optional'])) changed_li.addClass('optional');
			
			changed_li.find("label").html(data['title'] + '<a id="details-' + data['itemid'] + '" class="checklist-info-icon" data-parent="#chk-main" data-toggle="collapse"><span class="glyphicon glyphicon-info-sign"></span></a><img onclick="Checklist.ajaxRemoveItem(this, event);" class="chk-tools" src="' + chk_base_URL + 'components/com_checklist/assets/images/trash_recyclebin_empty_closed_w.png" style="display: none;"><img onclick="Checklist.editItem(this, event);" class="chk-tools pencil" src="' + chk_base_URL + 'components/com_checklist/assets/images/pencil2.png" style="display: none;">');
			
			changed_li.find('.panel-collapse').html(data['tips']);
			jQuery('#chk-edititem-form-' + data['itemid']).remove();
			setTimeout(Checklist.bindClickToTips, 200);

			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_CHECKLIST_ITEM_WAS_SUCCESSFULLY_CHANGED')?>");
			return true;
			
		},
		
		doAjax: function(task, params, feedback, object){
			
			var object = (typeof(object) != 'undefined') ? object : null;
			jQuery.ajax({
				url: chk_base_URL + "index.php?option=com_checklist&task=" + task + '&tmpl=component',
				type: "POST",
				data: params,
				dataType: "html",
				success: function(html){
					feedback(html, object);
				}
			});
			
		},
		
		resetErrorMsg: function (){
			jQuery(".alert").hide("slow");
		},
		
		setErrorMsg: function(type, msg){
			
			if(type == 'danger'){
				jQuery(".alert-danger").html(msg);
				jQuery(".alert-danger").slideDown("slow");
			}
			
			if(type == 'success'){
				jQuery(".alert-success").html(msg);
				jQuery(".alert-success").slideDown("slow");
			}
			
		},
		
		initGroupsSortables: function(){
			
			jQuery( "#chk-main" ).sortable({
				handle: 'h2.checklist-section-header',
				revert: 'true',
				tolerance: 'guess',
				placeholder: 'sortHelper',
				forcePlaceholderSize: true,
				stop: function(e,ui){
					Checklist.stopGroupDraggable(e,ui);
				} 
			});

			jQuery( "#chk-main" ).disableSelection();
		},
		
		stopGroupDraggable: function(e,ui){
			
			var checklist_id = jQuery("#checklist_id").val();
			var sections_array = new Array();
			var sections = jQuery("#chk-main section");
			
			for(var i=0; i < sections.length; i++){
				var groupid = jQuery(sections[i]).attr('groupid');
				sections_array.push(groupid);
			}
			
			var JsonOrdering = JSON.stringify(sections_array);
			Checklist.doAjax('checklist.reordergroups', {id: checklist_id, groupids: JsonOrdering}, Checklist.successFunction);
			
		},
		
		successFunction: function(){
			return false;
		},
		
		initItemsSortable: function(){
			jQuery( ".checklist-section-list" ).sortable({
				stop: function(e,ui){
					Checklist.stopItemDraggable(e,ui);
				}  
			});
			jQuery( ".checklist-section-list" ).disableSelection();
		},
		
		stopItemDraggable: function(e,ui){
			
			var checklist_id = jQuery("#checklist_id").val();
			var sections_array = new Array();
			var element = ui.item;
			var groupid = jQuery(element).parent().parent().attr("groupid");
			var items = jQuery(element).parent().find("li.li-handle");
			
			for(var i=0; i < items.length; i++){
				var itemid = jQuery(items[i]).attr('itemid');
				sections_array.push(itemid);
			}
			var JsonOrdering = JSON.stringify(sections_array);
			Checklist.doAjax('checklist.reorderitems', {id: checklist_id, groupid: groupid, itemids: JsonOrdering}, Checklist.successFunction);

			return false;
		},
		
		bindShowsGroupTools: function(){
			
			jQuery(".checklist-section-header").bind("mouseenter", function(){
				Checklist.showItemTools(this);
			});
			
			jQuery(".checklist-section-header").bind("mouseleave", function(){
				Checklist.hideItemTools(this);
			});						
		},
		
		bindShowsItemTools: function(){
			jQuery(".li-handle label").bind("mouseenter", function(){
				jQuery(this).find(".chk-tools").show();
			});
			
			jQuery(".li-handle label").bind("mouseleave", function(){
				jQuery(this).find(".chk-tools").hide();
			});
		},
		
		bindChecked: function(){
			jQuery(".li-handle label").bind("click", function(){
				Checklist.ajaxCheckedItem(this);
			});

			jQuery("#reset").bind("click", function(){
				Checklist.ajaxResetChecked();
			});
		},
		
		focusInputs: function(){
			jQuery("input.form-control, textarea.form-control").bind("click", function(){
				this.focus();
			})
		},
		
		ajaxResetChecked: function(){
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.doAjax('checklist.resetchecked', {id: checklist_id}, Checklist.successFunction);
		},

		ajaxCheckedItem: function(element){
			
			var checklist_id = jQuery("#checklist_id").val();
			var itemid = jQuery(element).parent().attr("itemid");
			var groupid = jQuery(element).parent().parent().parent().attr("groupid");
			var checked = (jQuery(element).prev().prop('checked') == true) ? 0 : 1;
			
			Checklist.doAjax('checklist.checkeditem', {id: checklist_id, groupid: groupid, item: itemid, checked: checked}, Checklist.successFunction);
			
		},
		
		bindDialogLinks: function(){
			
			jQuery('.chr-dialog-window').off('click').on("click", function(){
				var gid = jQuery(this).attr("gid");
				jQuery('#ajax-loader-editor' + gid).show();
				Checklist.doAjax('checklist.getEditor', {gid: gid}, Checklist.showInZebraDialog, this);
			});
		},
		
		showInZebraDialog: function(html, element){
		
			var gid = jQuery(element).attr("gid");
			jQuery('#ajax-loader-editor' + gid).hide();
				
			jQuery.Zebra_Dialog(html, {
				'type':     '',
				'title':    '<?php echo JText::_('COM_CHECKLIST_MARKDOWN_EDITOR')?>',
				'buttons':  [
                    {caption: '<?php echo JText::_('COM_CHECKLIST_INSERT')?>', callback: function() { 
						markdown_text = jQuery("#tips_" + gid).find(".markdown-editor").val();
						jQuery(element).parent().prev().find(".form-control").val(markdown_text);
					}},
                    {caption: '<?php echo JText::_('COM_CHECKLIST_CANCEL')?>', callback: function() { return true; }}
                ]
			});
			
			jQuery(".markdown-editor").markdown();
		},
		
		bindItemHeaderTools: function(){
			
			jQuery(".chk-ajax-remove-group, .chk-open-item-form, .chk-close-item-form, .chk-save-item").unbind("click");

			jQuery(".chk-ajax-remove-group").bind("click", function(){
				var groupid = jQuery(this).parent().parent().parent().attr("groupid");
				Checklist.ajaxRemoveGroup(this, groupid);
			});
			jQuery(".chk-open-item-form").bind("click", function(){
				Checklist.openItemForm(this);
			});
			jQuery(".chk-close-item-form").bind("click", function(){
				Checklist.closeItemForm(this);
			});
			jQuery(".chk-save-item").bind("click", function(){
				var groupid = jQuery(this).attr("groupid");
				Checklist.ajaxSaveItem(this, groupid);
			});
			
		},
				
		initApplication: function(){
		<?php if($this->allow_edit && $this->edit_mode){?>
			Checklist.bindShowsItemTools();
			Checklist.bindShowsGroupTools();
			Checklist.bindItemHeaderTools();
			Checklist.initGroupsSortables();
			Checklist.initItemsSortable();
			Checklist.focusInputs();
			Checklist.bindDialogLinks();
		<?php } ?>
		<?php if($this->authorized){?>
			Checklist.bindChecked();
		<?php }?>

			Checklist.bindClickToTips();
			jQuery('.progress').scrollToFixed({
				preFixed: function() { jQuery('.progress').css('marginTop', '0px'); },
        		postFixed: function() { jQuery('.progress').css('marginTop', '80px'); },
        		limit: jQuery('#chk-main').outerHeight() + 500
			});
		},
		
		PreventDefaultAction: function(event){
		
			event = event || window.event;
			
			if(event.preventDefault){
				event.preventDefault();
			} else {
				event.cancelBubble = true;
			}
			
			if(event.stopPropagation){
				event.stopPropagation();
			} else {
				event.returnValue = false;
			}
		},

		sendRequest: function()
		{
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.doAjax('checklist.sendRequest', {id: checklist_id}, Checklist.successSendRequest);
		},

		successSendRequest: function(response)
		{
			Checklist.resetErrorMsg();

			if(response == 'success'){

				jQuery('#checklist-send-request').remove();
				Checklist.setErrorMsg("success", "<?php echo JText::_('COM_CHECKLIST_REQUEST_WAS_SUCCESSFULLY_SENT')?>");
			} else {
				Checklist.setErrorMsg("danger", "<?php echo JText::_('COM_CHECKLIST_REQUEST_WAS_NOT_SENT')?>");
			}

			return true;
		},

		editModeOn: function()
		{
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.doAjax('checklist.switchEditMode', {id: checklist_id}, Checklist.successSwitchMode);
		},

		successSwitchMode: function(sefUrl)
		{
			if(sefUrl != ''){
				window.location.href = sefUrl;
				return false;
			}
		},

		viewModeOn: function(){
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.doAjax('checklist.switchViewMode', {id: checklist_id}, Checklist.successSwitchMode);
		}
	
	}
	
	Checklist.initApplication();

	function userAction(task){

		if(task == 'print'){

			var checklist_div = jQuery("#checklist");			
			var params = "status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=780,directories=no,location=no";
			var newWin = window.open('<?php echo JURI::root();?>index.php?option=com_checklist&view=checklist&id=<?php echo $this->checklist->id;?>&user_id=<?php echo $userid; ?>&tmpl=component&print=1', 'newWin', params);

		} else if(task == 'pdf'){

			var pdfURL = '<?php echo JURI::root();?>index.php?option=com_checklist&task=checklist.pdf&checklist_id=<?php echo $this->checklist->id;?>';
            var params = "menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes,width=400,height=300";
			var pdfWin = window.open(pdfURL, 'pdfWin', params);

		}

	}

    jQuery(function ($) {
        //fix Purity iii (demo site)
        if($('#t3-content').length){
            $('#checklist').find('.caret').each(function(){
                $(this).on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).closest('.checklist-section').find('.checklist-section-list').toggle();
                });
            });
        }
    });

</script>

<?php if(!$print):?>
<?php if ($this->config->social_google_plus_use == 1) { ?>
	
<script type="text/javascript">
window.___gcfg = {lang: '<?php echo $this->config->social_google_plus_language; ?>'};
(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
	
<?php } ?>

<?php if ($this->config->social_twitter_use == 1) { ?>
	
<script type="text/javascript">
(function() {
    var twitterScriptTag = document.createElement('script');
    twitterScriptTag.type = 'text/javascript';
    twitterScriptTag.async = true;
    twitterScriptTag.src = 'http://platform.twitter.com/widgets.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(twitterScriptTag, s);
    })();
</script>
	
<?php } ?>

<?php if ($this->config->social_linkedin_use == 1) { ?>
	
<script type="text/javascript" src="//platform.linkedin.com/in.js"></script>
	
<?php } ?>

<?php

	$disqusSubDomain	= trim($this->config->disqusSubDomain);
	$usedisqus	= $this->config->useDisqus;

	if($usedisqus && $this->allow_comment){
		// Perform some cleanups
		if($disqusSubDomain) $disqusSubDomain = str_replace(array('http://','.disqus.com/','.disqus.com'), array('','',''), $disqusSubDomain);

		require_once(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_checklist'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'jw_disqus'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'helper.php');

		// Output object
		$output = new JObject;

		// Post URLs (raw, browser, system)
		$itemURLraw = JURI::root().'index.php?option=com_checklist&view=checklist&id='.$this->checklist->id.'&userid='.$userid.'&Itemid='.$itemid;

		$websiteURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https://".$_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST'];
		$itemURLbrowser = $websiteURL.$_SERVER['REQUEST_URI'];
		$itemURLbrowser = explode("#",$itemURLbrowser);
		$itemURLbrowser = $itemURLbrowser[0];
		$itemURL = JRoute::_('index.php?option=com_checklist&view=checklist&id='.$this->checklist->id.'&userid='.$userid.'&Itemid='.$itemid);

		// Post URL assignments
		$output->itemURL 					= $websiteURL.$itemURL;
		$output->itemURLrelative 	= $itemURL;
		$output->itemURLbrowser		= $itemURLbrowser;
		$output->itemURLraw				= $itemURLraw;

		// Comments (post page)
		$output->comments = '
		<div id="disqus_thread"></div>
		<script type="text/javascript">
			//<![CDATA[
		';
		if($disqusSubDomain=='stemax'){
			$output->comments .= '
				var disqus_developer = "1";
			';
		}
		$output->comments .= '
				var disqus_url= "'.$output->itemURL.'";
				var disqus_identifier = "'.substr(md5($disqusSubDomain),0,10).'_id'.$this->checklist->id.'";
			//]]>
		</script>
		<script type="text/javascript" src="http://disqus.com/forums/'.$disqusSubDomain.'/embed.js"></script>
		<noscript>
			<a href="http://'.$disqusSubDomain.'.disqus.com/?url=ref">'.JText::_('COM_CHECKLIST_DISQUS_THREAD').'</a>
		</noscript>
		';

		$siteUrl = JURI::root();
		$dsqCSS = $siteUrl.'components/com_checklist/assets/jw_disqus/tmpl/css/disqus.css';
		$plgCSS = $siteUrl.'components/com_checklist/assets/jw_disqus/tmpl/css/template.css';
			
		$output->includes = "
		<script type=\"text/javascript\" src=\"{$siteUrl}/components/com_checklist/assets/jw_disqus/includes/js/mootools-core-1.4.5-full-compat.js\"></script>
		<script type=\"text/javascript\" src=\"{$siteUrl}/components/com_checklist/assets/jw_disqus/includes/js/mootools-more.js\"></script>
		<script type=\"text/javascript\" src=\"{$siteUrl}/components/com_checklist/assets/jw_disqus/includes/js/behaviour.js\"></script>
		<script type=\"text/javascript\">
		//<![CDATA[
			var disqusSubDomain = '{$disqusSubDomain}';
			var disqus_iframe_css = \"{$dsqCSS}\";
		//]]>
		</script>
		<style type=\"text/css\" media=\"all\">
			@import \"{$plgCSS}\";
		</style>
		";

		echo $output->includes;
		echo $output->comments;
	}
?>
<?php endif;?>

</div>