<?php
/**
* Checklist component for Joomla 3.0
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

class ChecklistControllerLists extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Lists', $prefix = 'ChecklistModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	public function delete()
	{
        $cid = $this->input->get('cid', array(), 'array');

        $return = parent::delete();

        $cid = ArrayHelper::toInteger($cid);
        $this->deleteRating($cid);

		$app = JFactory::getApplication();
		$session = JFactory::getSession();
		$defaultlist = $session->get('com_checklist.defaultlist');
	
		if($defaultlist){
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=1');
		} else {
			$app->redirect('index.php?option=com_checklist&view=lists&defaultlist=0');
		}

		return $return;
	}

	private function deleteRating($cid=array())
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

	    foreach ($cid as $id)
	    {
	        $query->clear();
	        $conditions = array(
	            $db->qn('checklist_id') .'='. $db->q($id)
	        );
	        $query->delete($db->qn('#__checklist_rating'))
	            ->where($conditions);
	        $db->setQuery($query)
	            ->execute();
        }

	    return true;
    }

}