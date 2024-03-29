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
$today = time();

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
<form action="<?php echo JRoute::_('index.php?option=com_checklist&view=users'); ?>" method="post" name="adminForm" id="adminForm">
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
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_UNAME', 'name', $listDirn, $listOrder); ?> 
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_USERNAME', 'username', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_USER_EMAIL', 'email', $listDirn, $listOrder); ?>
					</th>
					<th width="40%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_USER_LASTVISIT_DATE', 'lastvisitDate', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_USER_REGISTER_DATE', 'registerDate', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php if(sizeof($this->items)){foreach ($this->items as $i => $item) :
			
				$canEdit	= $user->authorise('core.edit',	$extension.'.user.'.$item->id);
                $canChange	= $user->authorise('core.edit.state', $extension.'.user.'.$item->id);
			?>
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
                            <?php
                            
                            if ($canEdit) : ?>
								<a title="<?php echo $this->escape(strip_tags($item->name)); ?>" href="<?php echo JRoute::_('index.php?option=com_checklist&view=user&layout=edit&user_id='.$item->id);?>">
									<?php echo $this->escape($item->name); ?>
								</a>
                            <?php else : ?>
                                <a title="<?php echo $this->escape(strip_tags($item->name)); ?>"><?php echo $this->escape($item->name); ?></a>
                            <?php endif; ?>
                        </div>
					</td>
					<td>
                        <div>
                            <?php echo $item->username;?>
                        </div>
					</td>
					<td>
                        <div>
                        	<?php echo $item->email;?>
                        </div>
					</td>
					<td>
						<div class="pull-center">
							<?php echo $item->lastvisitDate;?>
						</div>
					</td>
					<td>
                        <div class="pull-center">
							<?php echo $item->registerDate;?>
                        </div>
                    </td>
				</tr>
				<?php endforeach; ?>
                <?php } else { ?>
                        <tr>
                            <td colspan="5" align="center" >
                                <?php echo JText::sprintf('COM_CHECKLIST_NOCHECKLISTCREATED'); ?>
                                
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