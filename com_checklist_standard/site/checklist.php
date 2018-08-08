<?php
/**
* Checklist Component for Joomla 3
* @package Checklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

JLoader::register('ChecklistHelper', JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_checklist'. DIRECTORY_SEPARATOR .'helpers' . DIRECTORY_SEPARATOR . 'checklist.php');

$controller = JControllerLegacy::getInstance('Checklist');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();