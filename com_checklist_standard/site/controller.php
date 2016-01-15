<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

/**
 * Checklist Component Controller
 */
class ChecklistController extends JControllerLegacy
{
        /**
         * display task
         *
         * @return void
         */
        function display($cachable = false, $urlparams = array())
        {
        	$view = JFactory::getApplication()->input->getCmd('view', 'Main');
            JFactory::getApplication()->input->set('view', $view);
            parent::display($cachable);
        }
}