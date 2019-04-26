<?php
/**
* Checklist Tags Module for Joomla
* @version $Id: mod_checklist_tags.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage mod_jb_checklist.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).'/helper.php');

$mainframe = JFactory::getApplication();
$doc = JFactory::getDocument();
	
$file = JURI::root().'modules/mod_checklist_tags/tmpl/checklist_tags.css';
$doc->addStyleSheet($file);

$list = modChecklistTagsHelper::getList($params);
require(JModuleHelper::getLayoutPath('mod_checklist_tags'));