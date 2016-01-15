<?php
/**
* Checklist Tags Module for Joomla
* @version $Id: helper.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage helper.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class modChecklistTagsHelper
{
	function getList(&$params)
	{
	    $db = JFactory::getDBO();
        $mainframe =& JFactory::getApplication();

        $db->setQuery("SELECT COUNT(lt.`checklist_id`) as list_count, t.`name`, t.`id` FROM `#__checklist_tags` as t LEFT JOIN `#__checklist_list_tags` as lt ON lt.`tag_id` = t.`id` GROUP BY lt.`tag_id`");
        $tagCloud	= $db->loadObjectList();
    	
        if (count($tagCloud))
          return $tagCloud;
    	
    	return array();
    }
}
