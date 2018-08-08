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
<script type="text/javascript">
    
    jQuery(document).ready(function ()
    {
        getLatestNews();
    });
    
    function onBtnCheckLatestVersionClick(sender, event)
    {
        var resultDiv = document.getElementById('checklistLatestVersion');
        
        resultDiv.innerHTML = '<img src="<?php echo JURI::base().'components/com_checklist/assets/images/ajax_loader_16x11.gif'; ?>" />';
        
        var url = '<?php echo JURI::root().'administrator/index.php?option=com_checklist&task=general.get_latest_component_version'; ?>';
        var xmlData = "";
        var syncObject = {};
        var timeout = 5000;
        var dataCallback = function(request, syncObject, responseText) { onGetLatestVersionData(request, syncObject, responseText); };
        var timeoutCallback = function(request, syncObject) { onGetLatestVersionTimeout(request, syncObject); };
        
        MyAjax.makeRequest(url, xmlData, syncObject, timeout, dataCallback, timeoutCallback);
    }
    
    function onGetLatestVersionData(request, syncObject, responseText)
    {
        var resultDiv = document.getElementById('checklistLatestVersion');
        
        // Handling XML.
        
        var xmlDoc = MethodsForXml.getXmlDocFromString(responseText);
        var rootNode = xmlDoc.documentElement;
        
        var error = MethodsForXml.getNodeValue(rootNode.childNodes[0]);
        var status = MethodsForXml.getNodeValue(rootNode.childNodes[1]);
        var version = MethodsForXml.getNodeValue(rootNode.childNodes[2]);
        var info = MethodsForXml.getNodeValue(rootNode.childNodes[3]);
        
        // Handling data.
        
        if (error == "" && status == 200)
        {
            if (version == "<?php echo $this->config->component_version; ?>")
            {
                resultDiv.innerHTML = '<font color="green">' + version + '</font>' + info;
            }
            else
            {
                resultDiv.innerHTML = '<font color="red">' + version + '</font>&nbsp;<a href="http://www.joomplace.com/members-area.html" target="_blank">' +
                    '<?php echo '(' . JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_UPGRADE') . ')'; ?>' + '</a>';
            }
        }
        else
        {
            resultDiv.innerHTML = '<font color="red">' + '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CONNECTION_FAILED'); ?>: ' + error + (error == '' ? '' : ', ') +
                (status == -100 ? '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_TIMEOUT'); ?>' : status) + '</font>';
        }
    }
    
    function onGetLatestVersionTimeout(request, syncObject)
    {
        var resultDiv = document.getElementById('checklistLatestVersion');
        
        resultDiv.innerHTML = '<font color="red">' + '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CONNECTION_FAILED'); ?>: ' +
            '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_TIMEOUT'); ?>' + '</font>';
    }
    
    function getLatestNews()
    {
        var url = '<?php echo JURI::root().'administrator/index.php?option=com_checklist&task=general.get_latest_news'; ?>';
        var xmlData = "";
        var syncObject = {};
        var timeout = 5000;
        var dataCallback = function(request, syncObject, responseText) { onGetLatestNewsData(request, syncObject, responseText); };
        var timeoutCallback = function(request, syncObject) { onGetLatestNewsTimeout(request, syncObject); };
        
        MyAjax.makeRequest(url, xmlData, syncObject, timeout, dataCallback, timeoutCallback);
    }
    
    function onGetLatestNewsData(request, syncObject, responseText)
    {
        var resultDiv = document.getElementById('checklistLatestNews');
        
        // Handling XML.
        
        var xmlDoc = MethodsForXml.getXmlDocFromString(responseText);
        var rootNode = xmlDoc.documentElement;
        
        var error = MethodsForXml.getNodeValue(rootNode.childNodes[0]);
        var status = MethodsForXml.getNodeValue(rootNode.childNodes[1]);
        var content = MethodsForXml.getNodeValue(rootNode.childNodes[2]);
        
        // Handling data.
        
        if (error == "" && status == 200)
        {
            resultDiv.innerHTML = content;
        }
        else
        {
            resultDiv.innerHTML = '<font color="red">' + '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CONNECTION_FAILED'); ?>: ' +
                '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_TIMEOUT'); ?>' + '</font>';
        }
    }
    
    function onGetLatestNewsTimeout(request, syncObject)
    {
        var resultDiv = document.getElementById('checklistLatestNews');
        
        resultDiv.innerHTML = '<font color="red">' + '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CONNECTION_FAILED'); ?>: ' +
            '<?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_TIMEOUT'); ?>' + '</font>';
    }
    
    function onBtnShowChangelogClick(sender, event)
    {
        var link = '<?php echo 'index.php?option=com_checklist&task=general.show_changelog&tmpl=component'; ?>';
        var width = 620;
        var height = 620;
        
        var linkElement = document.createElement('a');
        linkElement.href = link;
        
        SqueezeBox.fromElement(linkElement, { handler: 'iframe', size: { x: width, y: height }, url: link });
    }
    
</script>

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
<div id="dashboard_items" ><a href="index.php?option=com_checklist&view=dashboard_items"><?php echo JText::_('COM_CHECKLIST_MANAGE_DASHBOARD_ITEMS');?></a></div>
</div>

<div id="j-main-container" class="span6 form-horizontal checklist_control_panel_container well" style="margin-right: 0px;">
    
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
            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_LATEST_VERSION') . ':'; ?></td>
            <td>
                <div id="checklistLatestVersion">
                    <button class="btn btn-small" onclick="onBtnCheckLatestVersionClick(this, event);">
                        <i class="icon-health"></i>
                        <?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CHECK_NOW'); ?>
                    </button>
                </div>
            </td>
         </tr>
         <tr>
            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_ABOUT') . ':'; ?></td>
            <td>
                <?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_ABOUT_DESC'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_FORUM') . ':'; ?></td>
            <td>
                <a target="_blank" href="http://www.joomplace.com/forum"
                    >http://www.joomplace.com/forum</a>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CHANGELOG') . ':'; ?></td>
            <td>
                <div class="button2-left"><div class="blank">
                    <button class="btn btn-small" onclick="onBtnShowChangelogClick(this, event);">
                        <i class="icon-file"></i>
                        <?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_CHANGELOG_VIEW'); ?>
                    </button>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table cellpadding="5" class="checklist_control_panel_news_table">
                    <tr>
                        <td section="">
                            <img src="<?php echo JURI::base().'components/com_checklist/assets/images/tick.png'; ?>"><?php echo JText::_('COM_CHECKLIST_BE_CONTROL_PANEL_NEWS_TITLE'); ?>
                        </td>
                    </tr>
                    <tr>
						<td colspan="2">
							<p>Do you know that you can extend your CheckList functionality?<br/>CheckList Standard version you are currently using offer a limited number of features. While a Professional one allows to fully extend your website functionality by a range of new options!<br/>Interested?<br/>Upgrade any time from your Member’s Area and get a variety of new features that will make your website stand out of the crowd!(<a href="http://www.joomplace.com/members-area.html" target="_blank">Member's Area</a>)</p>
						</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="checklist_control_panel_news_cell" style="background-image: linear-gradient(to bottom, #FFFFFF, #EEEEEE);">
                            <div id="checklistLatestNews" class="checklist_control_panel_news">
                                <img src="<?php echo JURI::base().'components/com_checklist/assets/images/ajax_loader_16x11.gif'; ?>" />
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
</div>