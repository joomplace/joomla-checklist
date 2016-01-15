<?php defined('_JEXEC') or die('Restricted access');
/**
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

require_once(JPATH_COMPONENT_ADMINISTRATOR.'/libs/HtmlHelper.php');

class LightchecklistViewInsert_Checklist_Tag extends JViewLegacy
{
	//----------------------------------------------------------------------------------------------------
	function display($tpl = null)
	{		
		JForm::addFieldPath(JPATH_COMPONENT.'/models/fields');
		
		$this->form = JForm::getInstance('select_checklist_modal', JPATH_COMPONENT.'/models/forms/'.'select_checklist_modal.xml');
		
		parent::display($tpl);
	}
}