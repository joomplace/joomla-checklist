<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$document = JFactory::getDocument();

$document->addStyleSheet(JURI::root()."components/com_lightchecklist/assets/tagmanager-master/tagmanager.css");
$document->addScript(JURI::root()."components/com_lightchecklist/assets/tagmanager-master/tagmanager.js");

?>
<script type="text/javascript">

Joomla.submitbutton = function(task)
	{
		if (task == 'list.cancel' || document.formvalidator.isValid(document.id('list-form'))) {
			<?php echo $this->form->getField('description_before')->save(); ?>
			<?php echo $this->form->getField('description_after')->save(); ?>
			Joomla.submitform(task, document.getElementById('list-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

</script>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_lightchecklist&layout=edit&id='.(int) $this->item->id); ?>" enctype="multipart/form-data" method="post" name="adminForm" id="list-form" class="form-validate">
	<div id="j-main-container" class="span10 form-horizontal">
	<ul class="nav nav-tabs" id="listTabs">
	    <li class="active"><a href="#list-details" data-toggle="tab"><?php echo  JText::_('COM_LIGHTCHECKLIST_LIST_DETAILS');?></a></li>
	    <li><a href="#list-publication" data-toggle="tab"><?php echo  JText::_('COM_LIGHTCHECKLIST_LIST_PUBLICATION');?></a></li>
	    <li><a href="#list-permissions" data-toggle="tab"><?php echo  JText::_('COM_LIGHTCHECKLIST_LIST_PERMISSIONS');?></a></li>
		<li><a href="#list-metadata" data-toggle="tab"><?php echo  JText::_('COM_LIGHTCHECKLIST_LIST_METADATA');?></a></li>
		<li><a href="#global-permissions" data-toggle="tab"><?php echo  JText::_('COM_LIGHTCHECKLIST_GLOBAL_PERMISSIONS');?></a></li>
	</ul>
	<div class="tab-content">
	    <div class="tab-pane active" id="list-details">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('title'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('title'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('alias'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('alias'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('template'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('template'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('description_before'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('description_before'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('description_after'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('description_after'); ?>
					</div>
				</div>

			</fieldset>
		</div>
		<div class="tab-pane" id="list-publication">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('default'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('default'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('publish_date'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('publish_date'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
		            	<label for="tm-tags" class="col-sm-2 control-label">
		               		<?php echo JText::_('COM_LIGHTCHECKLIST_FORM_TAGS'); ?>:
		            	</label>
		            </div>
		            <div class="controls">
		                <input type="text" name="tags" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_FORM_TAG');?>" class="tm-input inputbox"/>
		            </div>
		        </div>
		        <div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('author'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->author; ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('language'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('language'); ?>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="tab-pane" id="list-permissions">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('list_access'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('list_access'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('comment_access'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('comment_access'); ?>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="tab-pane" id="list-metadata">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('meta_keywords'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('meta_keywords'); ?>
					</div>
				</div>
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('meta_description'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('meta_description'); ?>
					</div>
				</div>
				<h3><?php echo JText::_('COM_LIGHTCHECKLIST_CUSTOM_META_TAGS');?></h3>
				<div class="form-group custom_metatags">
					<table border="0" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th width="45%"><?php echo JText::_('COM_LIGHTCHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?></th>
                                <th width="45%"><?php echo JText::_('COM_LIGHTCHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?></th>
                                <th width="10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ( empty($this->metadata) ) {
                                    echo '<tr id="ct_notags"><td colspan="3">'.JText::_('COM_LIGHTCHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NOTAGS').'</td>';
                                } else {
                                    foreach ( $this->metadata as $ctag_name => $ctag_value ) {
                                ?>
                            <tr>
                                <td><?php echo $ctag_name;?></td>
                                <td>
                                    <input type="text" name="cm_values[]" value="<?php echo $ctag_value;?>" class="wellinput" style="width: 100%"/>
                                </td>
                                <td>
                                    <span class="btn-small btn btn-danger" onclick="cmtRemove(this);"> X </span>
                                    <input type="hidden" name="cm_names[]" value="<?php echo $ctag_name;?>" />
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
                                <input type="text" style="width:100%" class="inputbox" value="" id="jcustom_name" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_NAME'); ?>">
                            </td>
                            <td width="40%" style="padding-right: 25px;">
                                <input type="text" style="width: 100%" class="inputbox" value="" id="jcustom_value" placeholder="<?php echo JText::_('COM_LIGHTCHECKLIST_BE_PUBLICATIONS_METADATA_CUSTOM_TAGS_CONTENT'); ?>">
                            </td>
                            <td width="10%">
                                <span class="btn btn-success" onclick="cmtAdd();"> + </span>
                            </td>
                        </tr>
                    </table>
				</div>
			</fieldset>
		</div>
		<div class="tab-pane" id="global-permissions">
			<fieldset class="adminform">
				<div class="control-group">
	                <div class="control-label">
					    <?php echo $this->form->getLabel('rules'); ?>
	                </div>
					<div class="controls">
						<?php echo $this->form->getInput('rules'); ?>
					</div>
				</div>
			</fieldset>
		</div>	    
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->item->id;?>" />
	<input type="hidden" name="defaultlist" value="<?php echo $this->defaultlist;?>" />
	<?php echo $this->form->getLabel('asset_id');?>
	<?php echo JHtml::_('form.token'); ?>	
	</div>
</form>
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

	jQuery(document).ready(function() {
        jQuery(".tm-input").tagsManager({
            <?php if(count($this->tags)): ?>
                prefilled: [<?php echo('"'.implode('","', $this->tags).'"');?>]
            <?php endif; ?>
        });
    });

</script>
