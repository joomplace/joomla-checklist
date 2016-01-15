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

$document = JFactory::getDocument();
$document->addStylesheet(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.css");
$document->addScript(JURI::root()."components/com_checklist/assets/js/jrating-master/jRating.jquery.min.js");

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$extension = 'com_checklist';

$sortFields = $this->getSortFields();

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
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_checklist&view=ratings'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div style="margin-top: 10px;" id="j-main-container" class="span10">
	<?php else : ?>
	<div style="margin-top: 10px;" id="j-main-container">
	<?php endif;?>
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
        <table class="table table-striped" id="Checklists">
            <thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						#
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="30%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_CHECKLIST_NAME', 'l.`title`', $listDirn, $listOrder); ?> 
					</th>
					<th width="30%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_USERNAME', 'u.`name`', $listDirn, $listOrder); ?>
					</th>
					<th width="30%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_RATING', 'r.`rate`', $listDirn, $listOrder); ?>
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
							<a title="<?php echo $this->escape(strip_tags($item->title)); ?>" href="<?php echo JRoute::_('index.php?option=com_checklist&view=checklist&layout=edit&id='.$item->checklist_id);?>">
								<?php echo $this->escape($item->title); ?>
							</a>
                        </div>
					</td>
					<td>
                        <div>
                            <?php echo $item->name;?>
                        </div>
					</td>
					<td>
                        <div class="rating hasTooltip" data-average="<?php echo (($item->rate) ? $item->rate : 0);?>" data-id="<?php echo $item->checklist_id?>" title="<strong><?php echo JText::_('COM_CHECKLIST_RATING')?>&nbsp;&nbsp;</strong><span style='color:green; font-size: 20px;''><?php echo (($item->rate) ? number_format($item->rate, 2, ".", ",") : "0.00");?></span><?php echo JText::_('COM_CHECKLIST_OUT_OF')?><span style='font-size: 17px; font-weight:bold;'>5.00</span>"></div>
					</td>
				</tr>
				<?php endforeach; ?>
                <?php } else { ?>
                        <tr>
                            <td colspan="5" align="center" >
                                <?php echo JText::sprintf('COM_CHECKLIST_NOCHECKLISTRATED'); ?>
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