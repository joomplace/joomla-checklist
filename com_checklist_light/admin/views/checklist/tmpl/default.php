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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base()."components/com_lightchecklist/assets/css/style.css");
$document->addStyleSheet(JURI::root()."components/com_lightchecklist/assets/css/zebra_dialog.css");
$document->addScript(JURI::root()."components/com_lightchecklist/assets/js/jquery-ui-1.9.2.custom.min.js");
$document->addScript(JURI::root()."components/com_lightchecklist/assets/js/zebra_dialog.js");
$document->addScript(JURI::root()."components/com_lightchecklist/assets/js/modernizr-2.6.2.js");
$document->addScript(JURI::root()."components/com_lightchecklist/assets/js/joomplace_style/script.js");

?>

<style>
	
	.li-handle label:hover{
		cursor:move;
	}

	.checklist-section-header:hover{
		cursor: move;
	}

	.chk-tools {
		float:none;
	}
	
	.form-horizontal .form-group input[type="text"]
	{
		width:90%;
		margin:5px !important;
	}

	.form-horizontal .form-group textarea
	{
		width:90%;
		margin:5px !important;
	}

	.checklist-group-tools div
	{
		margin-top:5px;
		margin-bottom: 5px;
	}

</style>
<?php echo $this->loadTemplate('menu');?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>

<div style="margin-top: 10px;" id="j-main-container">

	<link rel="stylesheet" href="<?php echo JURI::root();?>components/com_lightchecklist/assets/css/wmd-buttons.css">
	<script src="<?php echo JURI::root();?>components/com_lightchecklist/assets/js/Markdown.Converter.js" type="text/javascript"></script>
	<script src="<?php echo JURI::root();?>components/com_lightchecklist/assets/js/Markdown.Sanitizer.js" type="text/javascript"></script>
	<script src="<?php echo JURI::root();?>components/com_lightchecklist/assets/js/Markdown.Editor.js" type="text/javascript"></script>
	<script src="<?php echo JURI::root();?>components/com_lightchecklist/assets/js/jquery.markdown.js" type="text/javascript"></script>


	<div style="display:block;float:right;width:82%;" id="checklist-root">

		<div class="alert alert-success"></div>
		<div class="alert alert-danger"></div>

		<div class="chk-toolbar">
			<button type="button" class="btn btn-primary btn-lg" onclick="Checklist.openAddGroupForm();"><?php echo JText::_('COM_LIGHTCHECKLIST_ADD_GROUP')?></button>
		</div>
		<div class="chk-add-group">
			<form class="form-horizontal" role="form" style="margin-top:15px;">
				<div class="form-group-checklist">
					<label for="inputGroup" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_GROUP_NAME')?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="inputGroup" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_GROUP_NAME_PLACEHOLDER')?>" style="margin-right:10px;">
						<button type="button" class="btn btn-default" onclick="Checklist.ajaxSaveGroup();"><?php echo JText::_('COM_LIGHTCHECKLIST_SAVE')?></button>
						<button type="button" class="btn btn-default" onclick="Checklist.closeAddGroupForm();"><?php echo JText::_('COM_LIGHTCHECKLIST_CANCEL')?></button>
						<img src="<?php echo JURI::root()?>components/com_lightchecklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader"/>
					</div>
				</div>
				
			</form>
		</div>
		<hr/>

		<?php if($this->checklist):?>
		<div id="checklist">
			<header itemscope="" itemtype="http://schema.org/WebApplication">
				<h1 itemprop="name"><?php echo $this->checklist->title?></h1>
			</header>
			<div class="progress-container">
			    <progressbar class="checklist-progress">
			      <img id="keep-going" style="opacity:1;" src="<?php echo JURI::root(); ?>components/com_lightchecklist/assets/images/keep-going_small.png"/>
			      <progress value="0" max="37"><span id="fallback" style="width: 0%;">&nbsp;</span></progress>
			      <img id="well-done" style="opacity:0;" src="<?php echo JURI::root(); ?>components/com_lightchecklist/assets/images/well-done_small.png"/>
			      <p id="bonus" style="display:none;"><mark aria-relevant="text" aria-live="polite"></mark></p>
			      <a href="javascript:void(0);" id="reset"><?php echo JText::_('COM_LIGHTCHECKLIST_RESET')?></a>
			    </progressbar>
			</div>
			
			<p><?php echo $this->checklist->description_before;?></p>
			
			<div id="chk-main" class="checklist-container">
				<?php if(count($this->groups)):?>
				<?php foreach($this->groups as $group):?>
				<section class="checklist-section" id="<?php echo $group->section_id;?>" groupid="<?php echo $group->id;?>">
					<h2 class="checklist-section-header">
						<span id="group-name<?php echo $group->id;?>"><?php echo $group->title;?></span>
						<span class="chk-add-item">
							<img src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/pencil2.png" style="cursor:pointer;" groupid="<?php echo $group->id;?>" onclick="Checklist.openEditGroupForm(this);">&nbsp;
							<img src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/minus.png" style="cursor:pointer;" class="chk-ajax-remove-group">&nbsp;<img src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/list_add.png" style="cursor:pointer;" class="chk-open-item-form">
						</span>
					</h2>
					
					<div class="chk-edit-group-form" id="edit-group-name_<?php echo $group->id;?>">
                                            <form class="form-horizontal" style="margin-top:15px;">
                                                <div class="form-group-checklist">
                                                    <label for="inputEditGroup" class="col-sm-2 control-label"><?php echo JText::_('COM_LIGHTCHECKLIST_EDIT_GROUP_NAME')?></label>
                                                    <div class="col-sm-10">                                                        
                                                        <input type="text" class="form-control" id="inputEditGroup<?php echo $group->id;?>" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_EDIT_GROUP_NAME_PLACEHOLDER')?>" value="<?php echo $group->title;?>" style="margin-right:10px;">
                                                        <button type="button" class="btn btn-default" groupid="<?php echo $group->id;?>" onclick="Checklist.ajaxEditGroup(this);"><?php echo JText::_('COM_LIGHTCHECKLIST_CONFIRM')?></button>
                                                        <button type="button" class="btn btn-default" groupid="<?php echo $group->id;?>" onclick="Checklist.closeEditGroupForm(this);"><?php echo JText::_('COM_LIGHTCHECKLIST_CANCEL')?></button>                                                        
                                                    </div>
                                                </div>				
                                            </form>
                                        </div>
										
					<div class="chk-add-item-form">
						<form class="form-horizontal">
							<div class="form-group">
								<div class="col-sm-12">
									<input type="text" class="form-control" id="inputItem<?php echo $group->id;?>" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_TASK_NAME')?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">							
									<textarea class="form-control" rows="3" id="inputTips<?php echo $group->id;?>"></textarea>
								</div>
							</div>
							<div style="margin:5px;" class="checklist-group-tools">
								<div>
									<a href="javascript: void(0);" gid="<?php echo $group->id?>" class="chr-dialog-window"><?php echo JText::_('COM_LIGHTCHECKLIST_EDIT_WITH_MARKDOWN')?></a>&nbsp;<img src="<?php echo JURI::root()?>components/com_lightchecklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader-editor<?php echo $group->id;?>"/>
								</div>
								<div class="form-group">
									<div class="col-sm-12">
										<label for="inputOptional<?php echo $group->id;?>">
											<input class="chk-optional" type="checkbox" value="1" id="inputOptional<?php echo $group->id;?>"><?php echo JText::_('COM_LIGHTCHECKLIST_OPTIONAL')?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-12">
										<button groupid="<?php echo $group->id;?>" type="button" class="btn btn-sm chk-save-item"><?php echo JText::_('COM_LIGHTCHECKLIST_SAVE')?></button>
										<button type="button" class="btn btn-sm chk-close-item-form"><?php echo JText::_('COM_LIGHTCHECKLIST_CANCEL')?></button>
										<img src="<?php echo JURI::root()?>components/com_lightchecklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader<?php echo $group->id;?>"/>
									</div>
								</div>
							</div>
						</form>
					</div>

					<ul class="checklist-section-list">
						<?php if(count($group->items)):?>
						<?php foreach($group->items as $i => $item):?>
						<li <?php if($item->optional){?> class="li-handle optional" <?php } else {?> class="li-handle" <?php }?> itemid="<?php echo $item->id;?>">
							<input type="checkbox" id="<?php echo $item->input_id;?>" tabindex="<?php echo ($i+1);?>" class="chk-checkbox">
							<label for="<?php echo $item->label_for;?>"><?php echo $item->task;?>
							
							<img src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/trash_recyclebin_empty_closed_w.png" class="chk-tools" onclick="Checklist.ajaxRemoveItem(this, event);"/><img src="<?php echo JURI::root();?>components/com_lightchecklist/assets/images/pencil2.png" class="chk-tools pencil" onclick="Checklist.editItem(this, event);"/>

							</label>
							<em id="details-<?php echo $item->input_id;?>" class="checklist-info-icon"></em>
							<ul class="checklist-section-details" style="max-height: 500px;">
								<?php echo $item->tips;?>
							</ul>
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
	</div>

	<input type="hidden" name="checklist_id" id="checklist_id" value="<?php echo $this->checklist->id;?>" />
	<?php endif;?>

	<div style="clear: both;"><br/><br/></div>

</div>

<script type="text/javascript">
			
	var chk_base_URL = '<?php echo JURI::base();?>';
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
		
		openEditGroupForm: function(element){
                        var groupid = jQuery(element).attr("groupid");
			jQuery("#edit-group-name_" + groupid).slideDown(300);
			return true;
		},
                
                closeEditGroupForm: function(element){
			Checklist.resetErrorMsg();
                        var groupid = jQuery(element).attr("groupid");
			jQuery("#edit-group-name_" + groupid).slideUp(300);
			return true;
		},
                
                ajaxEditGroup: function(element){                        
			var checklist_id = jQuery("#checklist_id").val();			
			var groupid = jQuery(element).attr("groupid");
			var title = jQuery("#inputEditGroup" + groupid).val();                        
			Checklist.doAjax('checklist.ajaxupdategroup', {id: checklist_id, groupid: groupid, title: title}, Checklist.showEditedGroup, element);			
		},
                
                showEditedGroup: function(json, element){			
			var data = JSON.parse(json);
                        var groupid = data['groupid'];
                        var title = data['title'];                        
                        jQuery("#group-name" + groupid).html(title);			
			jQuery("#edit-group-name_" + groupid).slideUp(300);			
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_LIGHTCHECKLIST_GROUP_NAME_WAS_SUCCESSFULLY_EDITED')?>");
			return true;
			
		},
		
		ajaxSaveGroup: function(){
			Checklist.resetErrorMsg();
			var inputGroup = jQuery("#inputGroup").val();
			var checklist_id = jQuery("#checklist_id").val();
			
			if(inputGroup == '') {
				Checklist.setErrorMsg("danger", "<?php echo JText::_('COM_LIGHTCHECKLIST_INPUT_NAME_OF_NEW_GROUP')?>");
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
			
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_LIGHTCHECKLIST_GROUP_WAS_SUCCESSFULLY_ADDED')?>");
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
				Checklist.setErrorMsg("danger", "<?php echo JText::_('COM_LIGHTCHECKLIST_INPUT_NAME_OF_NEW_ITEM')?>");
				return false;
			}
			
			jQuery("#ajax-loader" + groupid).show();
			Checklist.doAjax('checklist.additem', {title: title, tips: tips, id: checklist_id, groupid: groupid, optional: optional}, Checklist.addNewItem, object);
			
		},
		
		addNewItem: function(html, object){
			
			jQuery(object).next().next().css("display", "none");
			var parent_div = jQuery(object).parent().parent().parent().parent().parent();
			parent_div.find(".form-control").val("");
			parent_div.find("input[type='checkbox']").prop("checked", "");
			parent_div.slideUp(300);
			
			var ul = parent_div.next();
			ul.append(html);
			
			Checklist.bindDialogLinks();
			Checklist.bindShowsItemTools();
			Checklist.initItemsSortable();
			setTimeout(Checklist.bindClickToTips, 300);
		},
		
		bindClickToTips: function(){
			
			findCheckboxes();
			initialize();
			calculateProgress();
			reset();
			
			if (localStorage.length === 0) details[0].click();
			
		},
		
		ajaxRemoveGroup: function(object, groupid){
		
			var checklist_id = jQuery("#checklist_id").val();
			Checklist.resetErrorMsg();
			
			jQuery.Zebra_Dialog('<?php echo JText::_('COM_LIGHTCHECKLIST_ARE_YOU_SURE_TO_WANT_TO_REMOVE_GROUP')?>', {
				'type':     'question',
				'title':    'Confirm window',
				'buttons':  ['Confirm', 'Cancel'],
				'onClose':  function(caption) {
					if(caption == 'Confirm'){
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
			setTimeout(Checklist.bindClickToTips, 500);
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_LIGHTCHECKLIST_GROUP_WAS_SUCCESSFULLY_REMOVED')?>");
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
			Checklist.bindClickToTips();
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_LIGHTCHECKLIST_ITEM_WAS_SUCCESSFULLY_REMOVED')?>");
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
					jQuery(edit_form).find(".chk-arrow").removeClass('chk-arrow').addClass('chk-arrow-right');
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
			
			changed_li.find("label").html(data['title'] + '<img onclick="Checklist.ajaxRemoveItem(this, event);" class="chk-tools" src="' + chk_base_URL + 'components/com_lightchecklist/assets/images/trash_recyclebin_empty_closed_w.png" style="display: none;"><img onclick="Checklist.editItem(this, event);" class="chk-tools pencil" src="' + chk_base_URL + 'components/com_lightchecklist/assets/images/pencil2.png" style="display: none;">');
			
			changed_li.find('.checklist-section-details').html(data['tips']);
			jQuery('#chk-edititem-form-' + data['itemid']).remove();
			
			Checklist.setErrorMsg("success", "<?php echo JText::_('COM_LIGHTCHECKLIST_ITEM_WAS_SUCCESSFULLY_CHANGED')?>");
			return true;
			
		},
		
		doAjax: function(task, params, feedback, object){
			
			var object = (typeof(object) != 'undefined') ? object : null;
			jQuery.ajax({
				url: chk_base_URL + "index.php?option=com_lightchecklist&task=" + task + '&tmpl=component',
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
			return true;
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
			var items = jQuery(element).parent().find("li");
			
			for(var i=0; i < items.length; i++){
				var itemid = jQuery(items[i]).attr('itemid');
				sections_array.push(itemid);
			}
			var JsonOrdering = JSON.stringify(sections_array);
			Checklist.doAjax('checklist.reorderitems', {id: checklist_id, groupid: groupid, itemids: JsonOrdering}, Checklist.successFunction);			
			
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
			var checked = (element.previousSibling.previousSibling.checked == true) ? 0 : 1;
			
			Checklist.doAjax('checklist.checkeditem', {id: checklist_id, groupid: groupid, item: itemid, checked: checked}, Checklist.successFunction);
			
		},
		
		bindDialogLinks: function(){
			
			jQuery('.chr-dialog-window').bind("click", function(){
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
				'title':    '<?php echo JText::_('COM_LIGHTCHECKLIST_MARKDOWN_EDITOR')?>',
				'buttons':  [
                    {caption: '<?php echo JText::_('COM_LIGHTCHECKLIST_INSERT')?>', callback: function() { 
						markdown_text = jQuery("#tips_" + gid).find(".markdown-editor").val();
						
						jQuery(element).parent().parent().prev().find(".form-control").val(markdown_text);
					}},
                    {caption: '<?php echo JText::_('COM_LIGHTCHECKLIST_CANCEL')?>', callback: function() { return true; }}
                ]
			});
			
			jQuery(".markdown-editor").markdown();
		},
		
		bindItemHeaderTools: function(){
			
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
		
		Checklist.bindShowsItemTools();
		Checklist.bindShowsGroupTools();
		Checklist.bindItemHeaderTools();
		Checklist.initGroupsSortables();
		Checklist.initItemsSortable();
		Checklist.focusInputs();
		Checklist.bindDialogLinks();
		
		<?php if($this->authorized){?>
			Checklist.bindChecked();
		<?php }?>
			Checklist.bindClickToTips();
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
		}	
	}
	
	Checklist.initApplication();

</script>