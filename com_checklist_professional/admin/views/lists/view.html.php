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
 
class ChecklistViewLists extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
   
    function display($tpl = null) 
	{
        $document = JFactory::getDocument();
        $default = JFactory::getApplication()->input->get('defaultlist', '');

        if($default == '1'){
            $submenu = 'default';
        } else {
            $submenu = 'lists';
        }

		ChecklistHelper::showTitle($submenu, 'stack');
        $this->addTemplatePath(JPATH_BASE.'/components/com_checklist/helpers/html');
		
        if($default == '1'){
            ChecklistHelper::addChecklistSubmenu('default_lists');
        } else {
		    ChecklistHelper::addChecklistSubmenu('user_lists');
        }

		
		$this->addToolBar();
        	        	 
        $items 		= $this->get('Items');
        $pagination = $this->get('Pagination');
        $state		= $this->get('State');
        
        if (count($errors = $this->get('Errors'))) 
        {
            JFactory::getApplication()->enqueueMessage(implode('<br />', $errors), 'error');
            return false;
        }

        $model = $this->getModel();

        if(count($items)){
            foreach ($items as &$item) {
                $item->request_sent = $model->getRequest($item);
            }
        }

        $this->items = $items;
        $this->pagination = $pagination;
		$this->state = $state;
        $this->tags = $model->getTags($items);
 		
        $app = JFactory::getApplication();
        $tagFields = JHTML::_('select.options', $this->get("TagList") ,'value', 'text', $app->getUserStateFromRequest('com_checklist.filter.tag_id', 'filter_tag_id'));
            
        JHtmlSidebar::addFilter(
            JText::_('COM_CHECKLIST_SELECT_TAG'),
            'filter_tag_id',
            $tagFields
        );

        $this->sidebar = JHtmlSidebar::render();

        parent::display($tpl);
    }
 
    /**
    * Setting the toolbar
    */
	protected function addToolBar() 
    {
        $canDo = JHelperContent::getActions('com_checklist', 'list');

        JToolBarHelper::addNew('list.add');
		JToolBarHelper::editList('list.edit');
		JToolBarHelper::deleteList('', 'lists.delete');

        if ($canDo->get('core.create') && $canDo->get('core.edit')) {
            JToolBarHelper::custom('lists.copy_lists', 'copy.png', 'copy_f2.png', 'COM_CHECKLIST_LISTS_COPY');
        }

		JToolBarHelper::divider();    
    }
	
	protected function getSortFields()
	{
		return array(
			'title' => JText::_('COM_CHECKLIST_TITLE'),
			'publish_date' => JText::_('COM_CHECKLIST_PUBLISH_DATE'),
            'default' => JText::_('COM_CHECKLIST_DEFAULT')
		);
	}
}
