<?php defined('_JEXEC') or die('Restricted access');
/*
* Checklist Component
* @package Checklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

class ChecklistControllerSampledata extends JControllerAdmin
{
	public function getModel($name = 'Sampledata', $prefix = 'ChecklistModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}

    public function install()
    {
        $app = JFactory::getApplication();
        $post = $app->input->post;
        $this->getModel()->installSampledata($post);
        $app->redirect('index.php?option=com_checklist&view=sampledata', JText::_('COM_CHECKLIST_INSTALL_SAMPLEDATA_SUCCESS'), 'message');
    }

	public function cancel() 
	{
		JFactory::getApplication()->redirect('index.php?option=com_checklist');
	}
}