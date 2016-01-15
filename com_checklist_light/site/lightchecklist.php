<?php
/**
* Lightchecklist Component for Joomla 3
* @package Lightchecklist Deluxe
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

JLoader::register('LightchecklistHelper', JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_lightchecklist'. DIRECTORY_SEPARATOR .'helpers' . DIRECTORY_SEPARATOR . 'lightchecklist.php');

$controller = JControllerLegacy::getInstance('Lightchecklist');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();