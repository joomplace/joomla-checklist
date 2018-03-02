<?php
/**
* Checklist module for Joomla
* @version $Id: mod_checklist_lastten.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage mod_checklist_lastten.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$user_profile		= intval( $params->get( 'user_profile', 1 ) );
$m_user_display  	= intval( $params->get( 'user_display', 0 ) );
$v_content_count 	= intval( $params->get( 'checklist_count', 10 ) );
$moduleclass_sfx 	= $params->get( 'moduleclass_sfx', '' );

$checklists = modChecklistLasttenHelper::getChecklists($params);

require JModuleHelper::getLayoutPath('mod_checklist_lastten', $params->get('layout', 'default'));

?>