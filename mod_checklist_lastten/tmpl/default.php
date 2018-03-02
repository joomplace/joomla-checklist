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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."modules/mod_checklist_lastten/tmpl/style.css");

?>
<div class="moduletable checklist_container cmodule">
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
		
		echo "<div class='checklist-row row".$sec_tbl."'><div class='checklist-title'><a href='".JRoute::_('index.php?option=com_checklist&view=checklist&id='.$one_checklist->checklist_id)."'>".$one_checklist->title."</a></div><div class='checklist-author'><span>".JText::_('MOD_CHECKLIST_CHECKLIST_AUTHOR')."</span>".$usr_d."</div></div><div style='clear:both;'></div>";
		if ($sec_tbl == 1) $sec_tbl = 0;
		else $sec_tbl = 1;
	}
	if (count($checklists) == $v_content_count) {
		echo "<div class='checklist-row'>" . JText::_('MOD_CHECKLIST_MOD_SOON') . "</div>";
	}
	
}

?>
</div>