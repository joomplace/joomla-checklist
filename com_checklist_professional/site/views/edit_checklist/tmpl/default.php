<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');

JHTML::_('behavior.calendar');
JHtml::_('bootstrap.tooltip');

$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");
$document->addScript(JURI::root()."components/com_checklist/assets/js/lists.js");

$document->addStyleSheet(JURI::root()."components/com_checklist/assets/tagmanager-master/tagmanager.css");
$document->addScript(JURI::root()."components/com_checklist/assets/tagmanager-master/tagmanager.js");

$editor = JFactory::getEditor();

$access = false;

if(!empty($this->item->id) && $user->authorise('core.edit', 'com_checklist')) {
    $access = true;
} elseif(empty($this->item->id) && $user->authorise('core.create', 'com_checklist')) {
    $access = true;
} else {
    echo '<div class="alert alert-danger" style="display:block;">'.JText::_('COM_CHECKLIST_YOU_NOT_AUTHORIZED').'</div>';
}
if($access) {
?>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.css" />

<script src="http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js"></script>
<style type="text/css">
	
	#publish_date_img
	{
		background-image: url('<?php echo JURI::root();?>components/com_checklist/assets/images/date.png');
		background-repeat: no-repeat;
		border: none;
		box-shadow: none;
		background-color: transparent;
		left: 93%;
    	position: relative;
    	top: -39px;
	}

	#chk-ajax-save
	{
		font-size: 100%;
	}

	.alert
	{
		display: none;
	}

	#listForm *, #listForm *:before, #listForm *:after {
		-webkit-box-sizing: border-box !important;
	    -moz-box-sizing: border-box !important;
	    box-sizing: border-box !important;
	}

</style>
<div class="alert alert-success"></div>
<div class="alert alert-danger"></div>

<div class="chk-add-checklist">
	<form class="form-horizontal" role="form" name="listForm" id="listForm">
		<div data-role="collapsibleset" data-theme="a" data-content-theme="a">
			<div data-role="collapsible" data-content-theme="false" data-collapsed="false">
				<h4><?php echo JText::_('COM_CHECKLIST_FORM_BASIC')?></h4>
				<div class="form-group">
					<label for="inputTitle" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_CHECKLIST_NAME')?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="inputTitle" placeholder="<?php echo JText::_('COM_CHECKLIST_NAME_PLACEHOLDER')?>" value="<?php echo $this->item->title;?>">
					</div>
				</div>
				<div class="form-group">
					<label for="inputAlias" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_CHECKLIST_ALIAS')?></label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="inputAlias" placeholder="<?php echo JText::_('COM_CHECKLIST_ALIAS_PLACEHOLDER')?>" value="<?php echo $this->item->alias;?>">
					</div>
				</div>
				<div class="form-group">
					<label for="template" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_TEMPLATE')?></label>
					<div class="col-sm-10">
						<?php echo $this->templates;?>
					</div>
				</div>
				<div class="form-group">
					<label for="inputDescription_before" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_CHECKLIST_DESCRIPTION_BEFORE')?></label>
					<div class="col-sm-10">
						<?php echo $editor->display( 'description_before', $this->item->description_before, '100%', '30', '10', '10',
                            array('pagebreak', 'readmore', 'image', 'article',
                                'checklist_button', 'quiz', 'comparisonchart', 'testimonial', 'goals',
                                'habits', 'plans', 'surveyforce', 'html5flippingbook_button') )?>
						
					</div>
				</div>
				<div class="form-group">
					<label for="inputDescription_after" class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_CHECKLIST_DESCRIPTION_AFTER')?></label>
					<div class="col-sm-10">
						<?php echo $editor->display( 'description_after', $this->item->description_after, '100%', '50', '50', '50',
                            array('pagebreak', 'readmore', 'image', 'article',
                                'checklist_button', 'quiz', 'comparisonchart', 'testimonial', 'goals',
                                'habits', 'plans', 'surveyforce', 'html5flippingbook_button') )?>
					</div>
				</div>
			</div>

			<div data-role="collapsible" data-content-theme="false">
				<h4><?php echo JText::_('COM_CHECKLIST_FORM_PUBLICATION')?></h4>
		                
		        <div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_PUBLISH_DATE')?></label>
					<div class="col-sm-10">
						<?php echo JHTML::calendar(date('Y-m-d H:i', strtotime($this->item->publish_date)), 'publish_date', 'publish_date', '%Y-%m-%d %H:%M', 'class=inputbox-small"'); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_LANGUAGE')?></label>
					<div class="col-sm-10">
						<?php echo $this->langs; ?>
					</div>
				</div>
				<div class="form-group">
		            <label for="tm-tags" class="col-sm-2 control-label">
		                <?php echo JText::_('COM_CHECKLIST_FORM_TAGS'); ?>:
		            </label>
		            <div class="col-sm-10">
		                <input type="text" name="tags" placeholder="<?php echo JText::_('COM_CHECKLIST_FORM_TAG');?>" class="tm-input inputbox"/>
		            </div>
		        </div>
		                 
			</div>
			<div data-role="collapsible" data-content-theme="false">
				<h4><?php echo JText::_('COM_CHECKLIST_FORM_PERMISSIONS')?></h4>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_WILL_BE_AVAILABLE_FOR')?></label>
					<div class="col-sm-10">	
						<?php echo $this->access_list;?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_COMMENTS_WILL_BE_AVAILABLE_FOR')?></label>
					<div class="col-sm-10">	
						<?php echo $this->access_comment;?>
					</div>
				</div>
			</div>
			<div data-role="collapsible" data-content-theme="false">
				<h4><?php echo JText::_('COM_CHECKLIST_FORM_META_DATA')?></h4>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_META_KEYWORDS')?></label>
					<div class="col-sm-10">	
						<textarea name="meta_keywords" id="meta_keywords" style="height:100px;" placeholder="<?php echo JText::_('COM_CHECKLIST_META_KEYWORDS_PLACEHOLDER')?>"><?php echo $this->item->meta_keywords?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php echo JText::_('COM_CHECKLIST_META_DESCRIPTION')?></label>
					<div class="col-sm-10">
						<textarea name="meta_description" id="meta_description" style="height:100px;" placeholder="<?php echo JText::_('COM_CHECKLIST_META_DESCRIPTION_PLACEHOLDER')?>"><?php echo $this->item->meta_description?></textarea>
					</div>
				</div>
				<hr/>
				<h3><?php echo JText::_('COM_CHECKLIST_CUSTOM_META_TAGS');?></h3>
				<div class="form-group custom_metatags">
					<table border="0" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="45%"><?php echo JText::_('COM_CHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?></th>
                                <th width="45%"><?php echo JText::_('COM_CHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?></th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ( empty($this->metadata) ) {
                                    echo '<tr id="ct_notags"><td colspan="3">'.JText::_('COM_CHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NOTAGS').'</td>';
                                } else {
                                    foreach ( $this->metadata as $ctag_name => $ctag_value ) {
                                ?>
                            <tr>
                                <td><?php echo $ctag_name;?></td>
                                <td>
                                    <input type="text" name="cm_values" value="<?php echo $ctag_value;?>" class="wellinput" style="width: 100%"/>
                                </td>
                                <td>
                                    <span class="btn-small btn btn-danger" onclick="cmtRemove(this);"> X </span>
                                    <input type="hidden" name="cm_names" value="<?php echo $ctag_name;?>" />
                                </td>
                            </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
				</div>
				<div class="form-group">
					<table border="0" width="100%">
                        <tr>
                            <td width="50%" style="padding-right: 25px;">
                                <input type="text" style="width:100%" class="inputbox" value="" id="jcustom_name" placeholder="<?php echo JText::_('COM_CHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?>">
                            </td>
                            <td width="40%" style="padding-right: 25px;">
                                <input type="text" style="width: 100%" class="inputbox" value="" id="jcustom_value" placeholder="<?php echo JText::_('COM_CHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?>">
                            </td>
                            <td width="10%">
                                <span class="btn btn-success" onclick="cmtAdd();"> + </span>
                            </td>
                        </tr>
                    </table>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div style="float:right;">
				<img src="<?php echo JURI::root()?>components/com_checklist/assets/images/ajax-loader.gif" style="display:none;" id="ajax-loader"/>&nbsp;
				<button type="button" class="ui-btn ui-btn-inline" id="chk-ajax-save"><?php echo JText::_('COM_CHECKLIST_SAVE')?></button>
				
			</div>
		</div>
		<input type="hidden" id="checklist_id" name="checklist_id" value="<?php echo $this->item->id;?>"/>
		<input type="hidden" id="chk_default" name="chk_default" value="<?php echo $this->item->default;?>"/>
	</form>
</div>
<script type="text/javascript">

	function cmtRemove(element) {

		var oldNodesCount = jQuery('.custom_metatags > table > tbody').children().length;
		element.parentNode.parentNode.parentNode.removeChild(element.parentNode.parentNode);
		if ( oldNodesCount == 1 )
			jQuery('.custom_metatags > table > tbody').append(
			'<tr id="ct_notags"><td colspan="3">No tags</td>'
			);
	}

	function cmtAdd() {

		document.getElementById('jcustom_name').value = document.getElementById('jcustom_name').value.replace(/\"/g, '&quote;');
		document.getElementById('jcustom_value').value = document.getElementById('jcustom_value').value.replace(/\"/g, '&quote;');

		if ( document.getElementById('jcustom_name').value != '' && document.getElementById('jcustom_value').value != '') {
			if ( document.getElementById('ct_notags') )
				document.getElementById('ct_notags').parentNode.removeChild( document.getElementById('ct_notags') );

			jQuery('.custom_metatags > table > tbody').append(
			'<tr><td>'+document.getElementById('jcustom_name').value+'</td>'
			+'<td>'+document.getElementById('jcustom_value').value+'</td>'
			+'<td><span class="btn-small btn btn-danger" onclick="cmtRemove(this);"> X </span>'
			+'<input type="hidden" name="cm_names" value="'+document.getElementById('jcustom_name').value+'" />'
			+'<input type="hidden" name="cm_values" value="'+document.getElementById('jcustom_value').value+'" />'
			+'</td>'
			+'</tr>'
			);

			document.getElementById('jcustom_name').value = '';
			document.getElementById('jcustom_value').value = '';
		}
	}

	function get_content() {
		
		<?php 	
			echo "inputDescription_before = ".$editor->getContent('description_before')."\n\t";
			echo "inputDescription_after = ".$editor->getContent('description_after');		
		?>	
	}

	jQuery(document).ready(function() {
        jQuery(".tm-input").tagsManager({
            <?php if(count($this->tags)): ?>
                prefilled: [<?php echo('"'.implode('","', $this->tags).'"');?>]
            <?php endif; ?>
        });
    });

	Checklist.onInitApplication();
</script>
<?php } //access ?>