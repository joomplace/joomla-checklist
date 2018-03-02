<?php
/**
 * Staticcontent component for Joomla 3.0
 * @package Staticcontent
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ChecklistModelFrontend extends JModelList
{

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function getTotal()
    {
        if (empty($this->_total)) {
            $this->_total = count($this->getChecklists(1));
        }

        return $this->_total;
    }

    public function getPagination()
    {

        if (empty($this->_pagination)) {
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('filter.limitstart'), $this->getState('filter.limit'));
        }

        return $this->_pagination;
    }

    public function getChecklists($isTotal = 0)
    {
        $user = JFactory::getUser();
        $db = JFactory::getDBO();

        $app = JFactory::getApplication();
        $limit = $app->getUserStateFromRequest('frontend.filter.limit', 'limit', 5);
        $this->setState('filter.limit', $limit);

        $limitstart = $app->getUserStateFromRequest('frontend.filter.limitstart', 'limitstart', 0);
        $this->setState('filter.limitstart', $limitstart);

        $title_search = $app->getUserStateFromRequest('frontend.filter.title_search', 'title_search');
        $this->setState('filter.title_search', $title_search);

        $tag_search = $app->getUserStateFromRequest('frontend.filter.tag_search', 'tag_search');
        $this->setState('filter.tag_search', $tag_search);

        $name_search = $app->getUserStateFromRequest('frontend.filter.name_search', 'name_search');
        $this->setState('filter.name_search', $name_search);

        $publish_search = $app->getUserStateFromRequest('frontend.filter.publish_date', 'publish_date');
        $this->setState('filter.publish_date    ', $publish_search);

        $description_search = $app->getUserStateFromRequest('frontend.filter.description_search', 'description_search');
        $this->setState('filter.description_search', $description_search);

        $filter_order = $app->getUserStateFromRequest('frontend.filter.filter_order', 'filter_order', 'title');
        $this->setState('filter.filter_order', $filter_order);

        $filter_order_Dir = $app->getUserStateFromRequest('frontend.filter.filter_order_Dir', 'filter_order_Dir', 'ASC');
        $this->setState('filter.filter_order_Dir', $filter_order_Dir);

        $liststyle = $app->getUserStateFromRequest('frontend.filter.liststyle', 'liststyle', 'list');
        $this->setState('filter.liststyle', $liststyle);

        $where = array();

        if ($title_search) {
            $where[] = "LOWER(l.`title`) LIKE '%" . $db->escape(strtolower($title_search), true) . "%'";
        }

        if ($tag_search) {
            $where[] = "LOWER(t.`name`) LIKE '%" . $db->escape(strtolower($tag_search)) . "%'";
        }

        if ($name_search) {
            $where[] = "LOWER(u.`name`) LIKE '%" . $db->escape(strtolower($name_search)) . "%'";
        }

        if ($publish_search) {
            $date = JFactory::getDate($publish_search);
            $where[] = "l.`publish_date` = " . $db->quote($date->toSql(), false);
        }

        if ($description_search) {
            $where[] = "(LOWER(l.`description_before`) LIKE '%" . $db->escape(strtolower($description_search)) . "%' OR LOWER(l.`description_after`) LIKE '%" . $db->escape(strtolower($description_search)) . "%')";
        }

        $where_string = '';
        if (count($where)) {
            $where_string = " AND " . implode(" AND ", $where);
        }

        if ($limit) {
            $limit_string = (!$isTotal) ? " LIMIT {$limitstart}, {$limit}" : "";
        } elseif (!$limit) {
            $limit_string = "";
        }

        $now_date = time();
        $groups = ChecklistHelper::getAllowGroups();

        //Language
        $lang_code = JFactory::getLanguage()->getTag();

        $order_by = "ORDER BY l.`title`, l.`id`";
        if ($filter_order != 'rating') {
            $order_by = "ORDER BY l." . $db->quoteName($filter_order) . " " . $db->escape($filter_order_Dir);
        }

        $list_access = '';
        if (count($groups)) {
            $list_access = " AND l.`list_access` IN (" . implode(",", $groups) . ")";
        }

        $db->setQuery("SELECT DISTINCT l.* FROM `#__checklist_lists` as l LEFT JOIN `#__users` as u ON u.`id` = l.`user_id` LEFT JOIN `#__checklist_list_tags` as lt ON lt.`checklist_id` = l.`id` LEFT JOIN `#__checklist_tags` as t ON t.`id` = lt.`tag_id` WHERE (l.`language` = '" . $lang_code . "' OR l.`language` = '*' OR l.`language` = '') AND l.`default` = '1' AND UNIX_TIMESTAMP(l.`publish_date`) <= '" . $now_date . "'" . $list_access . $where_string . " GROUP BY l.`id` " . $order_by . $limit_string);
        $available_checklists = $db->loadObjectList();

        $lists = array();
        $options = array();
        $options[] = JHTML::_('select.option', 'publish_date', JText::_('COM_CHECKLIST_PUBLISH_DATE_FILTER'));
        $options[] = JHTML::_('select.option', 'title', JText::_('COM_CHECKLIST_TITLE_FILTER'));

        $javascript = 'onchange="document.searchForm.submit();"';
        $lists['sort_by'] = JHTML::_('select.genericlist', $options, 'filter_order', 'class="text_area" style="max-width: 300px;" size = "1" ' . $javascript, 'value', 'text', $filter_order);

        if ($filter_order_Dir == 'DESC') {
            $order_dir = '<a href="javascript:document.searchForm.filter_order_Dir.value=\'ASC\';document.searchForm.submit();"><img alt="DESC" src="' . JURI::root() . 'components/com_checklist/assets/images/sort_desc.png"></a>';
        } elseif ($filter_order_Dir == 'ASC') {
            $order_dir = '<a href="javascript:document.searchForm.filter_order_Dir.value=\'DESC\';document.searchForm.submit();"><img alt="ASC" src="' . JURI::root() . 'components/com_checklist/assets/images/sort_asc.png"></a>';
        }

        $lists['order_dir'] = $order_dir;
        $lists['filter_order_Dir'] = $filter_order_Dir;

        if ($liststyle == 'list') {
            $lists['grid_img'] = '<img border="0" title="' . JText::_('COM_CHECKLIST_DISPLAY_AS_A_GRID') . '" src="' . JURI::root() . 'components/com_checklist/assets/images/grid_list_off.gif" class="checklist_liststyle">';
            $lists['list_img'] = '<img border="0" title="' . JText::_('COM_CHECKLIST_SIMPLE_LISTING') . '" src="' . JURI::root() . 'components/com_checklist/assets/images/list_simple.gif" class="auction_liststyle">';
        } elseif ($liststyle == 'grid') {
            $lists['grid_img'] = '<img border="0" title="' . JText::_('COM_CHECKLIST_DISPLAY_AS_A_GRID') . '" src="' . JURI::root() . 'components/com_checklist/assets/images/grid_list.gif" class="checklist_liststyle">';
            $lists['list_img'] = '<img border="0" title="' . JText::_('COM_CHECKLIST_SIMPLE_LISTING') . '" src="' . JURI::root() . 'components/com_checklist/assets/images/list_simple_off.gif" class="auction_liststyle">';
        }

        $lists['liststyle'] = $liststyle;

        $available_checklists[0]->lists = $lists;
        return $available_checklists;
    }

    public function getUser($uid)
    {

        $app = JFactory::getApplication();
        $db = JFactory::getDBO();

        $db->setQuery("SELECT u.`id` as `user_id`, u.`name`, chk_u.* FROM `#__checklist_users` AS `chk_u` LEFT JOIN `#__users` AS u ON u.`id` = chk_u.`user_id` WHERE u.`id` = '" . $uid . "'");
        $data = $db->loadObject();

        return $data;
    }

    public function getTags($checklist_id)
    {
        $db = JFactory::getDBO();
        $tags_array = array();

        $db->setQuery("SELECT t.`name`, t.`id` FROM `#__checklist_tags` as t LEFT JOIN `#__checklist_list_tags` as l ON l.`tag_id` = t.`id` WHERE l.`checklist_id` = '" . $checklist_id . "'");
        $tags_array[$checklist_id] = $db->loadAssocList();

        return $tags_array;
    }

    public function checkAllowCopy($userid, $checklist_id)
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        if (!$user->id) {
            return 0;
        }

        if ($user->id == $userid) {
            $db->setQuery("SELECT COUNT(`id`) FROM `#__checklist_lists` WHERE `id` = '" . $checklist_id . "' AND `user_id` = '" . $user->id . "' AND `default` = '1'");
            $my = $db->loadResult();

            if ($my) {
                return 0;
            }
        }

        return 1;
    }

    public function getClone()
    {

        $db = JFactory::getDBO();
        $user = JFactory::getUser();

        $app = JFactory::getApplication();
        $id = $app->input->get('id');

        $db->setQuery("SELECT * FROM `#__checklist_lists` WHERE `id` = '" . $id . "' AND `default` = '1'");
        $checklist = $db->loadObject();

        $checklist->id = '';
        $checklist->user_id = $user->id;
        $checklist->default = 0;

        $db->insertObject("#__checklist_lists", $checklist, "id");
        $checklist_id = $db->insertid();

        if ($checklist_id) {
            $db->setQuery("SELECT `alias` FROM `#__checklist_lists` WHERE `id` = '" . $checklist_id . "'");
            $alias = $db->loadResult();

            if ($alias) {
                $alias = $alias . "-" . $checklist_id;
            } else {
                $db->setQuery("SELECT `title` FROM `#__checklist_lists` WHERE `id` = '" . $checklist_id . "'");
                $title = $db->loadResult();

                $alias = str_replace(" ", "-", strtolower($title)) . "-" . $checklist_id;
            }

            $db->setQuery("UPDATE `#__checklist_lists` SET `alias` = '" . $alias . "' WHERE `id` = '" . $checklist_id . "'");
            $db->execute();
        }

        $db->setQuery("SELECT * FROM `#__checklist_groups` WHERE `checklist_id` = '" . $id . "'");
        $groups = $db->loadObjectList();

        $values = array();
        if (count($groups)) {
            foreach ($groups as $group) {
                $values[] = "('', '" . $checklist_id . "', " . $db->quote($group->title) . ", '" . $group->ordering . "')";
            }
        }

        if (count($values)) {
            $db->setQuery("INSERT INTO `#__checklist_groups` (`id`, `checklist_id`, `title`, `ordering`) VALUES " . implode(',', $values));
            $db->query();
        }

        $db->setQuery("SELECT `id` FROM `#__checklist_groups` WHERE `checklist_id` = '" . $checklist_id . "' ORDER BY `ordering`");
        $new_groups = $db->loadColumn();

        $db->setQuery("SELECT `id` FROM `#__checklist_groups` WHERE `checklist_id` = '" . $id . "' ORDER BY `ordering`");
        $old_groups = $db->loadColumn();

        $db->setQuery("SELECT * FROM `#__checklist_items` WHERE `checklist_id` = '" . $id . "'");
        $items = $db->loadObjectList();

        $values = array();
        if (count($old_groups) && count($items)) {
            foreach ($old_groups as $ii => $old_group) {
                foreach ($items as $item) {
                    if ($item->group_id == $old_group) {
                        $values[] = "('', '" . $checklist_id . "', '" . $new_groups[$ii] . "', " . $db->quote($item->task) . ", " . $db->quote($item->tips) . ", '" . $item->optional . "', '" . $item->ordering . "')";
                    }
                }
            }
        }

        if (count($values)) {
            $db->setQuery("INSERT INTO `#__checklist_items` (`id`, `checklist_id`, `group_id`, `task`, `tips`, `optional`, `ordering`) VALUES " . implode(',', $values));
            $db->query();
        }


        //Clone tags
        $db->setQuery("SELECT * FROM `#__checklist_list_tags` WHERE `checklist_id` = '" . $id . "'");
        $tags = $db->loadObjectList();

        if (count($tags)) {
            foreach ($tags as $tag) {
                $tag->id = '';
                $tag->checklist_id = $checklist_id;

                $db->insertObject('#__checklist_list_tags', $tag, 'id');
            }
        }

        return true;
    }
}
