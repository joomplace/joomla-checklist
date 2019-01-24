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

    function store($updateNulls = false)
    {
        $db = JFactory::getDBO();
        $jinput = JFactory::getApplication()->input;
        $jform = $jinput->get('jform', array(), 'ARRAY');

        $row = new stdClass;
        $row->user_id = $jform['user_id'];
        $row->website_field = $jform['website_field'];
        $row->twitter_field = $jform['twitter_field'];
        $row->facebook_field = $jform['facebook_field'];
        $row->google_field = $jform['google_field'];
        $row->description_field = $jform['description_field'];
        $db->updateObject('#__checklist_users', $row, 'user_id');

        $item = new stdClass;
        $item->id = $jform['user_id'];
        $item->name = $jform['name'];
        $item->username = $jform['username'];
        $item->email = $jform['email'];
        $db->updateObject('#__users', $item, 'id');

        return true;
    }

}