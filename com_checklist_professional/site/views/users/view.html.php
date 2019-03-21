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

class ChecklistViewUsers extends JViewLegacy
{
	protected $pagination;
	
	function display($tpl = null) 
	{
        $this->state = $this->get('State');
		$this->pagination = $this->get('Pagination');
		$this->users = $this->get('Users');
		$this->lists = $this->get('Lists');

		parent::display($tpl);
	}
}