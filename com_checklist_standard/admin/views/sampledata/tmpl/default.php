<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');
?>
<?php echo $this->loadTemplate('menu');?>
<style type="text/css">
	
	.sample_data_div{
		padding:25px;
		display: inline-block;
		-webkit-transition: background 0.5s ease;
     	-moz-transition: background 0.5s ease;
     	-o-transition: background 0.5s ease;
     	transition: background 0.5s ease;
	}


	.sample_data_div:hover
	{
		background-color: #EDEDED;
		cursor:pointer;

	}
	
</style>
<script type="text/javascript">

Joomla.submitbutton = function(task)
 {
  if (!document.getElementById('sampledata').value) {
   alert('<?php echo $this->escape(JText::_('COM_CHECKLIST_CHECK_LAYOUT'));?>');
  }
  else {
   Joomla.submitform(task);
  }
 }
 
</script>
<form name="adminForm" id="adminForm" action="index.php" method="post" autocomplete="off" class="form-validate" onsubmit="return false;">
	<input type="hidden" name="option" value="<?php echo 'com_checklist'; ?>" />
	<input type="hidden" name="task" value="" />
	<input id="sampledata" type="hidden" name="sampledata" value="" />
	<?php echo JHtml::_('form.token'); ?>
	
	<div id="j-main-container" class="span12 form-horizontal">
		<div class="sample_data_div">
			<img src="<?php echo JURI::base();?>components/com_checklist/assets/images/install_sample_data1.png" class="sample_data" onclick="checkedInstallData(this, 'sample1')"/>
		</div>
		<div class="sample_data_div">
			<img src="<?php echo JURI::base();?>components/com_checklist/assets/images/install_sample_data2.png" class="sample_data" onclick="checkedInstallData(this, 'sample2')"/>
		</div>
		<div class="sample_data_div">
			<img src="<?php echo JURI::base();?>components/com_checklist/assets/images/install_sample_data3.png" class="sample_data" onclick="checkedInstallData(this, 'sample3')"/>
		</div>
	</div>
</form>
<script type="text/javascript">

	function checkedInstallData(element, sampleName){

		form = document.adminForm;
		form.sampledata.value = sampleName;

		jQuery(".sample_data").css('outline', 'none');
		jQuery(element).css('outline', '1px solid #17568C');
	}
</script>