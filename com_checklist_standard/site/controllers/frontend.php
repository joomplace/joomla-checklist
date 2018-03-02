<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');
 
/**
 * Frontend Controller
 */
class ChecklistControllerFrontend extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         * @since       1.6
         */
        public function getModel($name = 'Frontend', $prefix = 'ChecklistModel', $config = array('ignore_request' => true))
        {
                $model = parent::getModel($name, $prefix, $config);
                return $model;
        }

        public function clone_checklist()
        {
        	$itemid = JFactory::getApplication()->input->get('Itemid');
        	$itemid = ($itemid) ? "&Itemid=".$itemid : "" ;
        	$model = $this->getModel();

        	$model->getClone();
        	$this->setRedirect(JURI::root().'index.php?option=com_checklist&view=lists'.$itemid);
        }
}
