<?php
/**
* Joomlaquiz component for Joomla 3.0
* @package Joomlaquiz
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');
?>
<?php echo $this->loadTemplate('menu');?>

<div id="pgm_dashboard">
    <?php
    foreach($this->dashboardItems as $ditem) { ?>
        <div onclick="window.location ='<?php echo $ditem->url; ?>'" class="pgm-dashboard_button">
            <?php if ($ditem->icon) { ?>
                <img src="<?php echo JUri::root().$ditem->icon ?>" class="pmg-dashboard_item_icon"/>
            <?php } ?>
            <?php echo '<div class="pgm-dashboard_button_text">'.$ditem->title.'</div>'?>
        </div>
    <?php } ?>
    <div id="dashboard_items" >
        <a href="index.php?option=com_checklist&view=dashboard_items">
            <?php echo JText::_('COM_CHECKLIST_MANAGE_DASHBOARD_ITEMS');?>
        </a>
    </div>
</div>

<div id="j-main-container" class="span6 form-horizontal checklist_control_panel_container well" style="margin-right: 0px;">

    <div class="accordion checklist-dashboard-accordion" id="accordion2">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a style="text-decoration: underline !important;" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                    About CheckList Joomla! Component
                </a>
            </div>
            <div id="collapseOne" class="accordion-body collapse in">
                <div class="accordion-inner">

                    <table class="table">
                        <tr>
                            <th colspan="100%" class="checklist_control_panel_title">
                                <?php echo JText::_('COM_CHECKLIST'); ?>&nbsp;<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_COMPONENT_DESC') .
                                    " 3.0+. " . JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_DEVELOPED_BY'); ?> <a href="http://www.joomplace.com/" target="_blank">JoomPlace</a>.
                            </th>
                        </tr>
                        <tr>
                            <td width="120"><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_INSTALLED_VERSION') . ':'; ?></td>
                            <td class="checklist_control_panel_current_version"><?php echo $this->config->component_version; ?></td>
                        </tr>
                         <tr>
                            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_ABOUT') . ':'; ?></td>
                            <td>
                                <?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_ABOUT_DESC'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_HELPDESK') . ':'; ?></td>
                            <td>
                                <a target="_blank" href="https://www.joomplace.com/support/helpdesk/post-purchase-questions/ticket/create.html" >create ticket</a>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="accordion-group">
            <div class="accordion-heading">
                <a style="text-decoration: underline !important" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                    <?php echo JText::_("COM_CHECKLIST_ABOUT_SAYTHANKSTITLE"); ?>
                </a>
            </div>
            <div id="collapseTwo" class="accordion-body collapse">
                <div class="accordion-inner">
                    <div class="thank_fdiv" style="font-size:12px;margin-left: 4px;">
                        <?php echo JText::_("COM_CHECKLIST_ABOUT_SAYTHANKS1"); ?>
                        <a href="https://extensions.joomla.org/extensions/extension/living/personal-life/checklist" target="_blank">http://extensions.joomla.org/</a>
                        <?php echo JText::_("COM_CHECKLIST_ABOUT_SAYTHANKS2"); ?>
                    </div>
                    <div style="float:right; margin:3px 5px 5px 5px;">
                        <a href="https://extensions.joomla.org/extensions/extension/living/personal-life/checklist" target="_blank">
                            <img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>