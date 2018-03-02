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
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/tagmanager-master/tagmanager.css");

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
<style type="text/css">
	
	a span.tm-tag:hover
	{
		background-color: #D5ECF2;
	}
</style>
<?php echo $this->loadTemplate('menu');?>
<form action="<?php echo JRoute::_('index.php?option=com_checklist&view=lists'); ?>" method="post" name="adminForm" id="adminForm">
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
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_TITLE', 'title', $listDirn, $listOrder); ?> 
					</th>
					<th width="10%">
						<?php echo JText::_('COM_CHECKLIST_EDITLIST'); ?> 
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_AUTHOR', 'user_id', $listDirn, $listOrder); ?> 
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_PUBLISH_DATE', 'publish_date', $listDirn, $listOrder); ?>
					</th>
					<th width="40%">
						<?php echo JText::_('COM_CHECKLIST_FORM_TAGS'); ?> 
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_CHECKLIST_DEFAULT_CHECKLIST', 'default', $listDirn, $listOrder); ?>
					</th>
					<th>
						Id
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="9">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php if(sizeof($this->items)){foreach ($this->items as $i => $item) :
				
				if($item->avatar_field != ''){
					if(file_exists(JPATH_SITE.'/images/checklist/avatar/'.$item->user_id.'/thm_'.$item->avatar_field)){
						$avatar_path = JURI::root().'images/checklist/avatar/'.$item->user_id.'/thm_'.$item->avatar_field;
						
					} else {
						$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
					}
				} else {
					$avatar_path = JURI::root().'components/com_checklist/assets/images/no_image.jpg';
				}

				$canEdit	= $user->authorise('core.edit',	$extension.'.user_checklist.'.$item->id);
                $canChange	= $user->authorise('core.edit.state', $extension.'.user_checklist.'.$item->id);
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
                            $title = strip_tags($item->title);
                            $length =100;
                            $dots="...";
                            $spacebar= ' ';
                            if(strlen($title)>$length)
                            {
                                $part = substr($title, 0 , $length);
                                while(substr($part, -1)!=$spacebar)
                                {
                                    $part = substr($part, 0, strlen($part)-1);
                                }
                                $title = $part.$dots;
                            }
                            if ($canEdit) : ?>
								<a title="<?php echo $this->escape(strip_tags($item->title)); ?>" href="<?php echo JRoute::_('index.php?option=com_checklist&task=list.edit&id='.$item->id);?>">
									<?php echo $this->escape($title); ?>
								</a>
                            <?php else : ?>
                                <a title="<?php echo $this->escape(strip_tags($item->title)); ?>"><?php echo $this->escape($title); ?></a>
                            <?php endif; ?>
                            <?php if($item->request_sent):?>
							<a href="index.php?option=com_checklist&view=requests&checklist_id=<?php echo $item->id;?>">
								<span class="label label-info" style="margin-left:10px;"><?php echo JText::_('COM_CHECKLIST_REQUEST')?></span>
							</a>
                            <?php endif;?>
                        </div>
					</td>
					<td>
                        <div>
                            <a href="index.php?option=com_checklist&view=checklist&id=<?php echo $item->id;?>"><img src="<?php echo JURI::base()?>components/com_checklist/assets/images/list_bullets.png" title="<?php echo JText::_('COM_CHECKLIST_EDITLIST'); ?>"/></a>
                        </div>
					</td>
					<td>
                        <div>
                        	<span class="tip hasTooltip" title="<img src='<?php echo $avatar_path;?>'>">
								<?php echo $item->name;?>
							</span>
                        </div>
                    </td>
					<td>
                        <div>
                        	<?php 
                        		if(strtotime($item->publish_date) <= $today){
                        			$style = "style='color:green;'";
                        		} else {
                        			$style = "style='color:red;'";
                        		}
                        	?>
                            <span <?php echo $style;?>><?php echo $item->publish_date; ?></span>
                        </div>
					</td>
					<td>
						<div>
							<?php if(isset($this->tags[$item->id]) && count($this->tags[$item->id])){?>
								<?php foreach ($this->tags[$item->id] as $ii => $tag) {?>
									<span class="tm-tag"><?php echo $tag->name; ?></span>									
								<?php }?>
							<?php }?>
						</div>
					</td>
					<td>
                        <div class="pull-center">
							<?php if($item->default && strtotime($item->publish_date) <= $today){?>
							<img src="<?php echo JURI::base()?>components/com_checklist/assets/images/tick2.png" />
							<?php } else {?>
							<img src="<?php echo JURI::base()?>components/com_checklist/assets/images/publish_x.png" />
							<?php }?>
                        </div>
                    </td>
                    <td>
						<?php echo $item->id;?>
                    </td>
				</tr>
				<?php endforeach; ?>
                <?php } else { ?>
                        <tr>
                            <td colspan="8" align="center" >
                                <?php echo JText::sprintf('COM_CHECKLIST_NOCHECKLISTCREATED'); ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_checklist&task=list.add'); ?>" >
                                    <?php echo JText::_('COM_CHECKLIST_CREATEANEWONE'); ?>
                                </a>
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