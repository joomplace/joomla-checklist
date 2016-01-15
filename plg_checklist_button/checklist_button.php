<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist plugin
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

jimport('joomla.plugin.plugin');

class plgButtonChecklist_button extends JPlugin
{
	protected $autoloadLanguage = true;

	//----------------------------------------------------------------------------------------------------
	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}
	//----------------------------------------------------------------------------------------------------
	function onDisplay($name, $asset, $author)
	{
		$js = "
			function checklistInsertTag(checklistId)
			{
				var tagContent = '';
				tagContent = '{checklist id=' + checklistId + '}';
				
				jInsertEditorText(tagContent, '".$name."');
				SqueezeBox.close();
			}";
		
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);
		
		JHTML::_('behavior.modal');

		$button = new JObject();
		$button->modal = true;
		$button->link = 'index.php?option=com_checklist&amp;view=insert_checklist_tag&amp;tmpl=component';
		$button->class = 'btn';
		$button->text = JText::_('PLG_CHECKLIST_BUTTON_BUTTON_TEXT');
		$button->name = 'tablet';
		$button->options =  "{handler: 'iframe', size: {x: 600, y: 220}}";

		return $button;
	}
}