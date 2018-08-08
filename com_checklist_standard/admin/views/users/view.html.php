<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

/**
 * User Checklists HTML View class for the Checklist Deluxe Component
 */
 
class ChecklistViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
   
    function display($tpl = null) 
	{
		$submenu = 'users';
		ChecklistHelper::showTitle($submenu, 'user');
        $this->addTemplatePath(JPATH_BASE.'/components/com_checklist/helpers/html');
		
        ChecklistHelper::addChecklistSubmenu('users');	
		$this->addToolBar();
        	        	 
        $items 		= $this->get('Items');
        $pagination = $this->get('Pagination');
        $state		= $this->get('State');
        
        if (count($errors = $this->get('Errors'))) 
        {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->items = $items;
        $this->pagination = $pagination;
		$this->state = $state;

        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }
 
    /**
    * Setting the toolbar
    */
	protected function addToolBar() 
    {
		JToolBarHelper::editList('user.edit');
		        	
		JToolBarHelper::divider();    
    }
	
	protected function getSortFields()
	{
		return array(
			'name' => JText::_('COM_CHECKLIST_UNAME'),
			'username' => JText::_('COM_CHECKLIST_USERNAME'),
            'email' => JText::_('COM_CHECKLIST_USER_EMAIL'),
            'lastvisitDate' => JText::_('COM_CHECKLIST_USER_LASTVISIT_DATE'),
            'registerDate' => JText::_('COM_CHECKLIST_USER_REGISTER_DATE')
		);
	}
}
