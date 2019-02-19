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

        $user_id = (int)$jform['user_id'] ? (int)$jform['user_id'] : $jinput->getInt('user_id', 0);

        $item = new stdClass;
        $item->id = $user_id;
        $item->name = $jform['name'];
        $item->username = $jform['username'];
        $item->email = $jform['email'];
        $db->updateObject('#__users', $item, 'id');

        $row = new stdClass;
        $row->user_id = $user_id;
        $row->website_field = $jform['website_field'];
        $row->twitter_field = $jform['twitter_field'];
        $row->facebook_field = $jform['facebook_field'];
        $row->google_field = $jform['google_field'];
        $row->description_field = $jform['description_field'];
        if ($jform['user_id']) {
            $db->updateObject('#__checklist_users', $row, 'user_id');
		}
		else {
			$db->insertObject('#__checklist_users', $row);
		}

        $avatar_file = $jinput->files->get('avatar_file', array(), 'ARRAY');
        if (!empty($avatar_file['name'])) {
            $this->saveAvatar($user_id, $avatar_file);
        }

        return true;
    }

    public function saveAvatar($user_id=0, $avatar_file=array())
    {
        if(!$user_id || empty($avatar_file)){
            return false;
        }

        $message = '';

        $avatar_filename = $avatar_file['name'];
        $avatar_file_tmpname = $avatar_file['tmp_name'];
        $base_Dir = JPATH_SITE."/images/checklist/avatar/";

        if(!JFolder::exists($base_Dir.$user_id)){
            JFolder::create($base_Dir.$user_id, 755);
        }

        if (JFile::exists($base_Dir.$user_id.'/'.$avatar_filename)) {
            $message = JText::_('COM_CHECKLIST_IMAGE').$avatar_filename.JText::_('COM_CHECKLIST_ALREADY_EXISTS');
        }
        if ((strcasecmp(substr($avatar_filename,-4),".gif")) && (strcasecmp(substr($avatar_filename,-4),".jpg")) && (strcasecmp(substr($avatar_filename,-4),".png")) && (strcasecmp(substr($avatar_filename,-4),".bmp")) ) {
            $message = JText::_('COM_CHECKLIST_ACCEPTED_FILES');
        }

        if($message != '') {
            JFactory::getApplication()->enqueueMessage($message, 'warning');
            return false;
        }

        if (!JFile::move($avatar_file_tmpname, $base_Dir.$user_id.'/'.$avatar_filename) || !JPath::setPermissions($base_Dir.$user_id.'/'.$avatar_filename)) {
            $message = JText::_('COM_CHECKLIST_UPLOAD_OF') . $avatar_filename . JText::_('COM_CHECKLIST_FAILED');
            JFactory::getApplication()->enqueueMessage($message, 'error');
            return false;
        }

        $message = JText::_('COM_CHECKLIST_UPLOAD_OF')." ".$avatar_filename.JText::_('COM_CHECKLIST_SUCCESSFUL');
        JFactory::getApplication()->enqueueMessage($message, 'message');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->qn('avatar_field') . '=' . $db->q($avatar_filename)
        );
        $conditions = array(
            $db->qn('user_id') . '=' . $db->q($user_id)
        );
        $query->update($db->qn('#__checklist_users'))
            ->set($fields)
            ->where($conditions);
        $db->setQuery($query)
            ->execute();

        $original_filename = $base_Dir.$user_id.'/'.$avatar_filename;
        $image = new JImage( $original_filename );
        $image->cropResize( 100, 100, false );
        $croped_filename = $base_Dir.$user_id.'/thm_'.$avatar_filename;
        $image->toFile( $croped_filename );

        return true;
    }

}