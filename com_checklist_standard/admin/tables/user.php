<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

/**
 * Checklist Table class
 */
class ChecklistTableUser extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db) 
    {
            parent::__construct('#__checklist_users', 'user_id', $db);
    }

	function store($updateNulls = false){

        $db = JFactory::getDBO();
        $post = JRequest::get('post');

        $row = new stdClass;

        $row->user_id = $post['jform']['user_id'];
        $row->website_field = $post['jform']['website_field'];
        $row->twitter_field = $post['jform']['twitter_field'];
        $row->facebook_field = $post['jform']['facebook_field'];
        $row->google_field = $post['jform']['google_field'];
        $row->description_field = $post['jform']['description_field'];
        $db->updateObject('#__checklist_users', $row, 'user_id');

        $item = new stdClass;

        $item->id = $post['jform']['user_id'];
        $item->name = $post['jform']['name'];
        $item->username = $post['jform']['username'];
        $item->email = $post['jform']['email'];
        $db->updateObject('#__users', $item, 'id');

        return true;

	}

}