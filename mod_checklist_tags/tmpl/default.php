<?php
/**
* Checklist Tags Module for Joomla
* @version $Id: default.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage default.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access'); 

?>
<div class="checklist-tagscloud<?php echo $params->get('moduleclass_sfx'); ?>">
    <div id="checklist-tags-mod">
    <?php if(count($list)):?>
		<?php foreach ($list as $tag) {
			$list_count = ($tag->list_count <= 9) ? ($tag->list_count - 1) : 9;
		?>
			<a href="<?php echo JRoute::_('index.php?option=com_checklist&view=tag&id='.$tag->id);?>" class="tag<?php echo $list_count;?>"><?php echo $tag->name;?></a>
		<?php }?>
    <?php endif;?>
    </div>
</div>
