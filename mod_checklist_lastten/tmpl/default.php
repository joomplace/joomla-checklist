<?php
/**
* Checklist module for Joomla
* @version $Id: default.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage default.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );
$sec_tbl = 1;

?>
<div class="moduletable checklist_container cmodule">
<table width="95%" border="0" cellspacing="0" cellpadding="1" align="center" class="table table-striped">
<tr class="row<?php echo $sec_tbl;?>"><td><b><?php echo JText::_('MOD_CHECKLIST_MOD_TITLE');?></b></td><td width="30%"><b><?php echo JText::_('MOD_CHECKLIST_MOD_AUTHOR');?></b></td></tr>
<?php

if(count($checklists)){
	foreach ($checklists as $one_checklist) {

		if($one_checklist->name != '' AND $one_checklist->username != ''){
			if($user_profile){
				$usr_d = '<a href="'.JRoute::_('index.php?option=com_checklist&view=profile&userid='.$one_checklist->user_id).'">'.($m_user_display? $one_checklist->name: $one_checklist->username).'</a>';
			}else{
				$usr_d = ($m_user_display? $one_checklist->name: $one_checklist->username);
			}
		} else {
			$usr_d = JText::_('MOD_CHECKLIST_MOD_NO_AUTHOR');
		}
		
		echo "<tr><td class='row".$sec_tbl.$moduleclass_sfx."'><a href='".JRoute::_('index.php?option=com_checklist&view=checklist&id='.$one_checklist->checklist_id)."'>".$one_checklist->title."</a></td><td class='row".$sec_tbl.$moduleclass_sfx."'>".$usr_d."</td></tr>";
		if ($sec_tbl == 1) $sec_tbl = 0;
		else $sec_tbl = 1;
	}
	if (count($checklists) == $v_content_count) {
		echo "<tr><td class='row".$sec_tbl.$moduleclass_sfx."'>" . JText::_('MOD_CHECKLIST_MOD_SOON') . "</td><td class='row".$sec_tbl.$moduleclass_sfx."'>&nbsp;</td></tr>";
	}
	
}

?>
</table>
</div>