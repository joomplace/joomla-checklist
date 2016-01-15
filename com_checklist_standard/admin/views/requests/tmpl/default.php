<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$extension = 'com_checklist';

$sortFields = $this->getSortFields();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/zebra_dialog.css");
$document->addScript(JURI::root()."components/com_checklist/assets/js/zebra_dialog.js");

$editor = JFactory::getEditor();

?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_checklist&view=lists'); ?>" method="post" name="adminForm" id="adminForm">
	<div style="margin-top: 10px;" id="j-main-container">
		<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_CHECKLIST_FILTER_SEARCH_DESC'); ?></label>
				<input type="text" name="filter_search" placeholder="<?php echo JText::_('COM_CHECKLIST_FILTER_SEARCH_DESC'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CHECKLIST_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button class="btn tip hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
			</div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
            </div>
            <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
                            <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
                            <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
                    </select>
            </div>
			 <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
                            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                    </select>
            </div>
        <div class="clearfix"> </div>
        <table class="table table-striped" id="Requests">
            <thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						#
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="30%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_TITLE', 'title', $listDirn, $listOrder); ?> 
					</th>
					<th width="10%">
						<?php echo JText::_('COM_CHECKLIST_VIEWLIST'); ?> 
					</th>
					
					<th width="60%">
						<?php echo JText::_('COM_CHECKLIST_ACTION') ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php if(sizeof($this->items)){foreach ($this->items as $i => $item) :?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
					<td class="order nowrap center">
						<?php echo ($i+1);?>
					    <input type="text" style="display:none" name="id" size="5"
							value="<?php echo $item->id;?>" class="width-20 text-area-order " />
					</td>
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
                        <div class="pull-left">
                            <a title="<?php echo $this->escape(strip_tags($item->title)); ?>" href="index.php?option=com_checklist&task=list.edit&id=<?php echo $item->checklist_id;?>"><?php echo $this->escape($item->title); ?></a>
                        </div>
					</td>
					<td>
                        <div>
                            <a href="index.php?option=com_checklist&view=checklist&id=<?php echo $item->checklist_id;?>" target="_blank"><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/list_bullets.png" title="<?php echo JText::_('COM_CHECKLIST_EDITLIST'); ?>"/></a>
                        </div>
					</td>
					<td>
                        <div class="pull-left">
							<button type="button" class="btn btn-success" onclick="confirmRequest(this, <?php echo $item->id;?>)"><?php echo JText::_('COM_CHECKLIST_CONFIRM')?></button>
							<button type="button" class="btn btn-danger" onclick="rejectRequest(this, <?php echo $item->id;?>)"><?php echo JText::_('COM_CHECKLIST_REJECT')?></button>
							<img src="<?php echo JURI::root()?>components/com_checklist/assets/images/ajax-loader.gif" style="display:none;margin-left" id="ajax-loader-<?php echo $item->id;?>"/>
                        </div>
					</td>
				</tr>
				<?php endforeach; ?>
                <?php } else { ?>
                        <tr>
                            <td colspan="5" align="center" >
                                <?php echo JText::sprintf('COM_CHECKLIST_NOREQUESTS'); ?>
                            </td>
                        </tr>
                <?php }?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
        
    </div>
</form>

<script type="text/javascript">

	function showZebraDialog(element, request_id, type){
		
		switch(type){
			case 'confirm':
				title = '<?php echo JText::_('COM_CHECKLIST_CONFIRM_MESSAGE_EDITOR')?>';
				message = '<?php echo JText::_('COM_CHECKLIST_USER_CONFIRM_NOTIFICATION_EMAIL_BODY');?>';
			break;
			case 'reject':
				title = '<?php echo JText::_('COM_CHECKLIST_REJECT_MESSAGE_EDITOR')?>';
				message = '<?php echo JText::_('COM_CHECKLIST_USER_REJECT_NOTIFICATION_EMAIL_BODY');?>';
			break;
		}

		var ajaxAction = '';
		var html = '<div class="editor"><textarea name="message" id="message" cols="50" rows="50" style="width: 90%; height: 150px;" class="mce_editable">' + message + '</textarea></div><div><small style=\'font-size:10px;color:#888888;\'><?php echo JText::_('COM_CHECKLIST_USE_S_TO_INSERT_USERNAME');?></small></div>';

		jQuery.Zebra_Dialog(html, {
			'type':     '',
			'title':    title,
			'buttons':  [
                {caption: '<?php echo JText::_('COM_CHECKLIST_SEND')?>', callback: function() {

                		var message = jQuery("#message").val();
                		
                		switch(type){
            				case 'confirm':
            					ajaxAction = 'requests.ajaxconfirm';
            					var feedback = showConfirmMessage;
            				break;
            				case 'reject':
            					ajaxAction = 'requests.ajaxreject';
            					var feedback = showRejectMessage;
            				break;
            			}

						jQuery("#ajax-loader-" + request_id).show();
						doAjax(ajaxAction, {request_id: request_id, message: message}, feedback, element);
						return true;
					}
				},
                {caption: '<?php echo JText::_('COM_CHECKLIST_CANCEL')?>', callback: function() {
                		return true; 
                	}
            	}
            ]
		});
		
	}

	function doAjax(task, params, feedback, object){
			
		var object = (typeof(object) != 'undefined') ? object : null;
		jQuery.ajax({
			url: "index.php?option=com_checklist&task=" + task + '&tmpl=component',
			type: "POST",
			data: params,
			dataType: "html",
			success: function(html){
				feedback(html, object);
			}
		});
		
	}

	function confirmRequest(element, request_id){

		showZebraDialog(element, request_id, 'confirm');

	}

	function showConfirmMessage(response, element)
	{
		if(response == 'success'){
			var td = jQuery(element).parent().parent();

			jQuery(element).parent().remove();

			var accept_content = '<span class="label label-success"><?php echo JText::_('COM_CHECKLIST_CONFIRMED')?></span>';
			jQuery(td).append(accept_content);

			return true;
		}
	}

	function rejectRequest(element, request_id){

		showZebraDialog(element, request_id, 'reject');

	}

	function showRejectMessage(response, element)
	{
		if(response == 'success'){
			var td = jQuery(element).parent().parent();

			jQuery(element).parent().remove();

			var decline_content = '<span class="label label-danger"><?php echo JText::_('COM_CHECKLIST_REJECTED')?></span>';
			jQuery(td).append(decline_content);

			return true;
		}
	}

</script>