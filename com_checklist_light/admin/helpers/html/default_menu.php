<?php
/**
* Lightchecklist for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );
$app = JFactory::getApplication();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root().'administrator/components/com_lightchecklist/assets/css/checklist.css');

?>
<div id="jp-navbar" class="navbar navbar-static navbar-inverse">
    <div class="navbar-inner">
        <div class="container" style="width: auto;">
            <a class="brand" href="<?php JRoute::_('index.php?option=com_lightchecklist') ?>"><img class="jp-panel-logo" src="<?php echo JURI::root() ?>administrator/components/com_lightchecklist/assets/images/joomplace-logo.png" /> <?php echo JText::_('COM_LIGHTCHECKLIST_JOOMPLACE')?></a>
            <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a id="control-panel" href="index.php?option=com_lightchecklist&view=dashboard" role="button" class="dropdown-toggle"><?php echo JText::_('COM_LIGHTCHECKLIST_CONTROL_PANEL')?></a>
                </li>
            </ul>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-joomlaquiz">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse-joomlaquiz nav-collapse collapse">
                <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a href="#" id="drop-checklist-manage" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo  JText::_('COM_LIGHTCHECKLIST_MENU_MANAGEMENT') ?><b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="drop-checklist-manage">
                        <li role="presentation" class="nav-header"><?php echo JText::_('COM_LIGHTCHECKLIST_MENUHEADER_TAGS');?></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_lightchecklist&view=tags"><?php echo JText::_('COM_LIGHTCHECKLIST_SUBMENU_TAGS');?></a></li>

                        <li role="presentation" class="nav-header"><?php echo JText::_('COM_LIGHTCHECKLIST_MENUHEADER_LISTS');?></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_lightchecklist&view=lists"><?php echo JText::_('COM_LIGHTCHECKLIST_SUBMENU_UNAVAILABLE_LISTS');?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_lightchecklist&view=lists&defaultlist=1"><?php echo JText::_('COM_LIGHTCHECKLIST_SUBMENU_DEFAULT_LIST');?></a></li>
                    
                        <li role="presentation" class="nav-header"><?php echo JText::_('COM_LIGHTCHECKLIST_MENUHEADER_USERS');?></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_lightchecklist&view=users"><?php echo JText::_('COM_LIGHTCHECKLIST_SUBMENU_USER_LIST');?></a></li>
                    </ul>
                </li>
                </ul>
                <ul class="nav" role="navigation">
                    <li>
                        <a href="index.php?option=com_lightchecklist&view=configuration" id="checklist-config" role="button"><?php echo  JText::_('COM_LIGHTCHECKLIST_MENU_CONFIGURATION') ?></a>
                    </li>
                </ul>
                <ul class="nav" role="navigation">
                    <li>
                        <a href="index.php?option=com_lightchecklist&view=sampledata" id="checklist-config" role="button"><?php echo  JText::_('COM_LIGHTCHECKLIST_MENU_SAMPLE_DATA') ?></a>
                    </li>
                </ul>
                <ul class="nav pull-right">
                <li id="fat-menu" class="dropdown">
                    <a href="#" id="help" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_LIGHTCHECKLIST_SUBMENU_HELP') ?><b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="help">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="https://www.joomplace.com/forum/joomla-components/checklist.html" target="_blank"><?php echo JText::_('COM_LIGHTCHECKLIST_ADMINISTRATION_SUPPORT_FORUM') ?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/support/helpdesk/" target="_blank"><?php echo JText::_('COM_LIGHTCHECKLIST_ADMINISTRATION_SUPPORT_DESC') ?></a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/support/helpdesk/post-purchase-questions/ticket/create" target="_blank"><?php echo JText::_('COM_LIGHTCHECKLIST_ADMINISTRATION_SUPPORT_REQUEST') ?></a></li>
                    </ul>
                </li>
            </ul>
            </div>
        </div>
    </div>
</div>