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
class ChecklistTableList extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__checklist_lists', 'id', $db);
        $this->_trackAssets = true;
    }

    function store($updateNulls = false){

        $db = JFactory::getDBO();

        $jinput = JFactory::getApplication()->input;
        $jform = $jinput->get('jform', array(), 'ARRAY');
        $this->id = $jinput->getInt('id', 0);
        $task = $jinput->get('task', '');

        //==================================================
        // Access rules.
        //==================================================
        if (isset($jform['rules']))
        {
            $rulesArray = $jform['rules'];
            // Removing 'Inherited' permissons. Otherwise they will be converted to 'Denied'.
            foreach ($rulesArray as $actionName => $permissions)
            {
                foreach ($permissions as $userGroupId => $permisson)
                {
                    if ($permisson == '')
                    {
                        unset($rulesArray[$actionName][$userGroupId]);
                    }
                }
            }

            $rules = new JAccessRules($rulesArray);
            $this->setRules($rules);
        }

        if ($task == 'list.save2copy'){
            $this->id = NULL;
            $this->_db->insertObject($this->_tbl, $this, $this->_tbl_keys[0]);
        }

        $res = parent::store($updateNulls);

        $alias = ($this->alias != '') ? $this->alias : $this->title;
        $alias = str_replace(" ", "-", mb_strtolower($alias));

        $db->setQuery("UPDATE `#__checklist_lists` SET `alias` = '".$alias."' WHERE `id` = '".$this->id."'");
        $db->execute();

        $cm_names = $jinput->get('cm_names', array(), 'ARRAY');
        $cm_values = $jinput->get('cm_values', array(), 'ARRAY');

        $metatags = '';
        $custom_metatags = array();
        if(count($cm_names)){
            foreach ($cm_names as $ii => $name) {
                $custom_metatags[$name] = $cm_values[$ii];
            }

            $metatags = json_encode($custom_metatags);
            $db->setQuery("UPDATE `#__checklist_lists` SET `custom_metatags` = '".$metatags."' WHERE `id` = '".$this->id."'");
            $db->execute();
        }

        $tags = $jinput->get('tags', '');
        $tags_array = array();
        if($tags != ''){
            $tags_array = explode(",", $tags);
        }

        $db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `checklist_id` = '".$this->id."'");
        $db->execute();

        if(!empty($tags_array) && $this->id){
            foreach ($tags_array as $tag) {
                $tag = trim($tag);
                $db->setQuery("SELECT `id` FROM `#__checklist_tags` WHERE `name` = '".$tag."'");
                $tag_id = (int)$db->loadResult();

                if(!$tag_id){
                    $tag_obj = new stdClass;
                    $tag_obj->id = '';
                    $tag_obj->name = $tag;
                    $tag_obj->default = 0;
                    $tag_obj->slug = '';
                    $db->insertObject('#__checklist_tags', $tag_obj, 'id');
                    $tag_id = $db->insertid();
                }

                $object = new stdClass;
                $object->id = '';
                $object->checklist_id = $this->id;
                $object->tag_id = $tag_id;
                $object->isnew = 1;
                $db->insertObject('#__checklist_list_tags', $object, 'id');
            }
        }

        $db->setQuery("UPDATE `#__checklist_lists` SET `user_id` = '".(int)$jform['author_id']."' WHERE `id` = '".$this->id."'");
        $db->execute();

        return $res;
    }

    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        return 'com_checklist.list.'.(int) $this->$k;
    }

    protected function _getAssetTitle()
    {
        return $this->title;
    }

    protected function _getAssetParentId(Joomla\CMS\Table\Table $table = NULL, $id = NULL)
    {
        $assetsTable = JTable::getInstance('Asset', 'JTable');
        $assetsTable->loadByName('com_checklist');
        return $assetsTable->id;
    }
}