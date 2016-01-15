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
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");

?>
<style type="text/css">
	*, *:before, *:after {
	    -moz-box-sizing: inherit !important;
	    box-sizing: inherit !important;
	}
</style>
<div style="margin-left:5px;" id="checklist-root">

	<div class="alert alert-danger" style="display:block;"><?php echo JText::_('COM_CHECKLIST_UNAVAILABLE');?></div>

</div>