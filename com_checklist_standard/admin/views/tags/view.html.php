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
 * Tags HTML View class for the Checklist Deluxe Component
 */
 
class ChecklistViewTags extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
   
    function display($tpl = null) 
	{

        $layout = JFactory::getApplication()->input->get('layout', 'default');

        if($layout == 'default'){

    		$submenu = 'tags';
    		ChecklistHelper::showTitle($submenu, 'tag');
            $this->addTemplatePath(JPATH_BASE.'/components/com_checklist/helpers/html');
    		
            ChecklistHelper::addChecklistSubmenu('tags');	
    		$this->addToolBar();
            	        	 
            $items 		= $this->get('Items');
            $pagination = $this->get('Pagination');
            $state		= $this->get('State');
            
            if (count($errors = $this->get('Errors'))) 
            {
                JFactory::getApplication()->enqueueMessage(implode('<br />', $errors), 'error');
                return false;
            }

            $this->items = $items;
            $this->pagination = $pagination;
    		$this->state = $state;

            $this->sidebar = JHtmlSidebar::render();

        } elseif($layout == 'lists') {

            $this->lists = JHTML::_('select.genericlist', $this->get('AllList'), 'checklist_id', 'class="input" style="margin:15px;"', 'value', 'text', '');
            $item      = $this->get('Tag');
            $this->item = $item;
            $model = $this->getModel();
            $checklists = array();
            $checklists[$item->id] = $model->getChecklists($item);

            $this->checklists = $checklists;
        } elseif($layout == 'tag') {

            $item      = $this->get('Tag');
            $this->item = $item;

        }

        $this->setLayout = $layout;
        parent::display($tpl);
    }
 
    /**
    * Setting the toolbar
    */
	protected function addToolBar() 
    {
        $bar = JToolBar::getInstance('toolbar'); 
        $bar->appendButton( 'Custom', '<div id="toolbar-new" class="btn-group"><button class="btn btn-small btn-success" onclick="openModalWindow(1);" href="javascript:void(0);"><i class="icon-new icon-white"></i>'.JText::_('COM_CHECKLIST_NEW').'</button></div>');
        $bar->appendButton( 'Custom', '<div id="toolbar-edit" class="btn-wrapper"><button class="btn btn-small" onclick="if (document.adminForm.boxchecked.value==0){alert(\'Please first make a selection from the list\');return false;}else{ openModalWindow(0); }"><i class="icon-edit"></i>'.JText::_('COM_CHECKLIST_EDIT').'</button></div>');
		JToolBarHelper::deleteList('', 'tags.delete');
        	
		JToolBarHelper::divider();    
    }
	
	protected function getSortFields()
	{
		return array(
			'name' => JText::_('COM_CHECKLIST_TAG_NAME'),
			'id' => JText::_('COM_CHECKLIST_TAG_ID')
		);
	}
}
