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
                $post = JRequest::get('post');

                $jinput = JFactory::getApplication()->input;
                $jform = $jinput->get('jform', array(), 'ARRAY');

                $this->id = $post['id'];
				$task = $post['task'];
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
                $alias = str_replace(" ", "-", strtolower($alias));

                $db->setQuery("UPDATE `#__checklist_lists` SET `alias` = '".$alias."' WHERE `id` = '".$this->id."'");
                $db->execute();

                $cm_names = $post['cm_names'];
                $cm_values = $post['cm_values'];

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

                $tags = $post['tags'];
                $tags_array = array();
                if($tags != ''){
                        $tags_array = explode(",", $tags);
                }

                if(count($tags_array) && $this->id){
                        foreach ($tags_array as $tag) {

                                $tag = trim($tag);
                                $db->setQuery("SELECT `id` FROM `#__checklist_tags` WHERE `name` = '".$tag."'");
                                $tag_id = $db->loadResult();

                                $tag_obj = new stdClass;
                                $tag_obj->id = ($tag_id) ? $tag_id : '';
                                $tag_obj->name = $tag;
                                $tag_obj->default = 0;
                                $tag_obj->slug = '';

                                if(!$tag_id){
                                        $db->insertObject('#__checklist_tags', $tag_obj, 'id');
                                        $tag_id = $db->insertid();
                                }

                                $db->setQuery("DELETE FROM `#__checklist_list_tags` WHERE `tag_id` = '".$tag_id."' AND `checklist_id` = '".$this->id."'");
                                $db->execute();

                                $object = new stdClass;
                                $object->id = '';
                                $object->checklist_id = $this->id;
                                $object->tag_id = $tag_id;
                                $object->isnew = 1;

                                $db->insertObject('#__checklist_list_tags', $object, 'id');
                        }
                }
                //post can be deleted in future
                if(empty($post['author_id'])) {
                    $author_id = $jform['author_id'];
                } else {
                    $author_id = $post['author_id'];
                }
                $db->setQuery("UPDATE `#__checklist_lists` SET `user_id` = '".$author_id."' WHERE `id` = '".$this->id."'");
                $db->execute();

                return $res;

	}
		
	//----------------------------------------------------------------------------------------------------
    protected function _getAssetName()
    {
        $k = $this->_tbl_key;
        
        return 'com_checklist.list.'.(int) $this->$k;
    }
    //----------------------------------------------------------------------------------------------------
    protected function _getAssetTitle()
    {
        return $this->title;
    }
    //----------------------------------------------------------------------------------------------------
    protected function _getAssetParentId($table = null, $id = null)
    {
        $assetsTable = JTable::getInstance('Asset', 'JTable');
        
        $assetsTable->loadByName('com_checklist');
        
        return $assetsTable->id;
    }
}