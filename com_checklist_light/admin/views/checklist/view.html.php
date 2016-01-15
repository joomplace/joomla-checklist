<?php
/**
* Lightchecklist component for Joomla 3.0
* @package Lightchecklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');


class LightchecklistViewChecklist extends JViewLegacy
{
	function display($tpl = null) 
	{
		$session = JFactory::getSession();
		$document = JFactory::getDocument();
		$default = $session->get('com_lightchecklist.defaultlist');
		$submenu = 'checklist';

		LightchecklistHelper::showTitle($submenu, 'pencil');
        $this->addTemplatePath(JPATH_BASE.'/components/com_lightchecklist/helpers/html');

        if($default == '1'){
            LightchecklistHelper::addChecklistSubmenu('default_lists');
        } else {
		    LightchecklistHelper::addChecklistSubmenu('user_lists');
        }

        $this->sidebar = JHtmlSidebar::render();

		$model = $this->getModel();

		require_once(JPATH_SITE.'/administrator/components/com_lightchecklist/models/configuration.php');
		$configurationModel = JModelLegacy::getInstance('Configuration', 'LightchecklistModel');
		$this->config = $configurationModel->GetConfig();

        $user = JFactory::getUser();
		$app = JFactory::getApplication();
					
		$this->checklist = $this->get('Checklist');	
	
		$groups = $this->get('Groups');
		$items  = $this->get('Items');
	
		if(count($groups)){
			foreach($groups as &$group){
				$group->items = array();
				foreach($items as $item){
					if($item->group_id == $group->id){
						$group->items[] = $item;
					}
				}
				
			}
		}
		
		$this->groups = $groups;
				
		$this->authorized = false;
		if($user->id){
			$this->authorized = true;
			$this->checkedList = $model->getCheckedList($this->checklist->id);
		}
		
		parent::display($tpl);
	}
}