<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );
JHtml::_('bootstrap.tooltip', '.hasTooltip', array('viewport'=>'body'));

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."components/com_checklist/assets/css/bootstrap.min.css");
?>
<style type="text/css">
	
	*, *:before, *:after {
	    -moz-box-sizing: inherit !important;
	    box-sizing: inherit !important;
	}

</style>
<div class="chk-image">
	<image class="chk-blog-checklist" src="<?php echo JURI::root()?>components/com_checklist/assets/images/blog-checklist.jpg" style="max-width:98%;">
</div>

<div class="jumbotron">
  <h1><?php echo JText::_('COM_CHECKLIST_DO_IT_NOW'); ?></h1>
  <p><?php echo JText::_('COM_CHECKLIST_WELCOME'); ?></p>
  <p><a class="btn btn-primary btn-lg" role="button" href="index.php?option=com_checklist&view=register"><?php echo JText::_('COM_CHECKLIST_CREATE_NOW'); ?></a>
  <a class="btn btn-primary btn-lg" role="button" href="<?php echo JURI::root();?>index.php?option=com_users&view=login"><?php echo JText::_('COM_CHECKLIST_SIGN_IN'); ?></a>
  </p>
</div>



