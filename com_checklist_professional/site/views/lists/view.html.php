<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

class ChecklistViewLists extends JViewLegacy
{
	protected $pagination;
    protected $state;

	function display($tpl = null) 
	{
        $this->state = $this->get('State');
        $model = $this->getModel();

		$allow_edit = false;
        $user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		$this->itemid = $app->input->get('Itemid');

		$userid = JFactory::getApplication()->input->get('userid', 0);
		$this->userid = $userid;

		$this->uname = '';
		if($userid){
			$this->uname = $this->get('Uname');
		}

		if(!$user->id && !$userid){	
			$app->redirect(JRoute::_('index.php?option=com_users&view=login', 'This area is for registered users only. Please log in to access it.'));
		} else if($user->id || $userid){
			
			if($userid && $userid == $user->id) $allow_edit = true;
			if(!$userid && $user->id) $allow_edit = true;
			
			$this->allow_edit = $allow_edit;
            $this->checklists = $this->get('Checklists');
			$this->pagination = $this->get('Pagination');
		}

		$uid = ($userid) ? $userid : $user->id;
		$this->uid = $uid;
		
		$this->user_data = $model->getUser($uid);
		$this->tags_array = $model->getTags($this->checklists);

		parent::display($tpl);
	}
}