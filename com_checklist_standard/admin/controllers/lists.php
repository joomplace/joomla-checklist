<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

class ChecklistControllerLists extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Lists', $prefix = 'ChecklistModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function delete()
	{
        $cid = $this->input->get('cid', array(), 'array');

        $return = parent::delete();

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$defaultlist = $session->get('com_checklist.defaultlist');
	
		if($defaultlist){
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=1');
		} else {
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=0');
		}

		return $return;
	}

    public function copy_lists()
    {
        $this->checkToken();

        $cid = $this->input->get('cid', array(), 'array');

        if($this->input->get('option') == 'com_checklist'
            && $this->input->get('layout') == 'edit'
            && $this->input->getInt('id')
        ) {
            $cid = array( $this->input->getInt('id', 0) );
        }

        if (!is_array($cid) || count($cid) < 1) {
            $this->setMessage(JText::_('COM_CHECKLIST_NO_ITEM_SELECTED'), 'warning');
            $this->setRedirect(JRoute::_('index.php?option=com_checklist&view=lists&defaultlist=0'));
        } else {
            $model = $this->getModel();
            $cid = ArrayHelper::toInteger($cid);

            if ($model->copy_lists($cid)) {
                $this->setMessage(JText::plural('COM_CHECKLIST_N_ITEMS_COPIED', count($cid)));
            } else {
                $this->setMessage($model->getError(), 'error');
            }
        }

        $this->setRedirect('index.php?option=com_checklist&view=lists&defaultlist=0');
    }

}