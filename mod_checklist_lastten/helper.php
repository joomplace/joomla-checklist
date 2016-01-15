<?php
/**
* Checklist module for Joomla
* @version $Id: helper.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage helper.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modChecklistLasttenHelper
{
	/*
	 * @since  1.5
	 */
	public static function getChecklists(&$params)
	{
		$time = time();
		$database = JFactory::getDBO();
		$result = array();
		$v_content_count 	= intval( $params->get( 'checklist_count', 10 ) );
		$checklist_id		 	= trim( $params->get( 'checklistid' ) );
		
		if ($v_content_count == 0) {
			$v_content_count = 5;
		}
		
		$query = "SELECT l.`title`, l.`id` as checklist_id, u.`name`, u.`username`, u.`id` as user_id FROM `#__checklist_lists` as l LEFT JOIN `#__users` as u ON l.`user_id` = u.`id` WHERE l.`default` = '1' and UNIX_TIMESTAMP(l.`publish_date`) <= '".$time."'";
		if ($checklist_id) {
			$checklist_ids = explode( ',', $checklist_id );
			if(count($checklist_ids)){
				$query .= "\n AND ( l.`id` = " . implode( " OR l.`id` = ", $checklist_ids ) . " )";
			}
		}
		$query .= "\n ORDER BY l.`id` DESC LIMIT 0,".$v_content_count;
		$database->SetQuery($query);
		$checklists = $database->LoadObjectList();

		if (count($checklists) == 0) {
			$checklists = array(); 
		}
		
		return $checklists;
	}
}
