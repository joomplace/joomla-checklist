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

class ChecklistControllerRatings extends JControllerAdmin
{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Rating', $prefix = 'ChecklistModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function delete(){

		$db = JFactory::getDBO();
		$option = "com_checklist";

		$post = JRequest::get('post');
		$cid = $post['cid'];
		
		if (count( $cid )) {
			$cids = implode( ',', $cid );
			$query = "DELETE FROM `#__checklist_rating`"
			. "\n WHERE id IN ( $cids )"
			;
			$db->setQuery( $query );
			if (!$db->execute()) {
				echo "<script> alert('".$db->getErrorMsg()."'); window.history.go(-1); </script>\n";
				exit();
			}
		}
		
		$app = JFactory::getApplication();
		$app->redirect('index.php?option=com_checklist&view=ratings');

		return true;
	}

}
