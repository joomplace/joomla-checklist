<?php
/**
* Lightchecklist Component for Joomla 3
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

/**
 * Requests HTML View class for the Lightchecklist Deluxe Component
 */
 
class LightchecklistViewRequests extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
   
    function display($tpl = null) 
	{
        $submenu = 'requests';
		LightchecklistHelper::showTitle($submenu, 'stack');
        $this->addTemplatePath(JPATH_BASE.'/components/com_lightchecklist/helpers/html');
			
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

        parent::display($tpl);
    }
 
    /**
    * Setting the toolbar
    */
	protected function addToolBar() 
    {
          
    }
	
	protected function getSortFields()
	{
		return array(
			'title' => JText::_('COM_LIGHTCHECKLIST_TITLE')
		);
	}
}
