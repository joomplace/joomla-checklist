<?php
/**
* Checklist component for Joomla 3.x
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

function ChecklistBuildRoute( &$query )
{
	$db	= JFactory::getDBO();
	$app = JFactory::getApplication();
	$segments = array();
	$db	= JFactory::getDBO();
	$views = array(
		'frontend',
		'checklist',
		'tag',
		'profile',
		'editprofile');

	$endWithSlash = array();
	if (isset($query['view'])) {
		
		if(isset($query['view']) AND ($query['view'] == 'frontend') ){
			$segments[] = $query['view'];
			unset($query['view']);
		}

		if(isset($query['view']) AND ($query['view'] == 'main') ){
			$segments[] = $query['view'];
			unset($query['view']);
		}

		if(isset($query['view']) AND ($query['view'] == 'checklist')){
			$segments[] = 'checklist';
			unset($query['view']);

			if(!isset($query['id'])){
				if($app->isAdmin()){
					$params = JComponentHelper::getParams('com_checklist');
				} else {
					$params = $app->getParams();
				}

				$query['id'] = $params->get('id', 0);
			}

			$sql = "SELECT `alias` FROM `#__checklist_lists` WHERE id = '".(int)$query['id']."' ";
			$db->setQuery( $sql );
			$checklist_alias= $db->loadResult();
			if(!$checklist_alias){
				$sql = "SELECT `title` FROM `#__checklist_lists` WHERE id = '".(int)$query['id']."' ";
				$db->setQuery( $sql );
				$checklist_title= $db->loadResult();

				$checklist_alias = str_replace(" ","-", strtolower($checklist_title));
			}

			$segments[] = $checklist_alias.".html";

			unset($query['id']);
			if(isset($query['user_id'])){
				unset($query['user_id']);
			}

			if(isset($query['userid'])){
				unset($query['userid']);
			}
			
		}

		if(isset($query['view']) AND ($query['view'] == 'tag')){

			$segments[] = 'checklist';
			$segments[] = 'tag';
			unset($query['view']);

			if(!isset($query['id'])){
				if($app->isAdmin()){
					$params = JComponentHelper::getParams('com_checklist');
				} else {
					$params = $app->getParams();
				}

				$query['id'] = $params->get('tag_id', 0);
			}

			$sql = "SELECT `name` FROM `#__checklist_tags` WHERE id = '".(int)$query['id']."' ";
			$db->setQuery( $sql );
			$tag_name = $db->loadResult();
			$tag_name = str_replace(" ", "-", strtolower($tag_name));
			$segments[] = $tag_name.".html";

			unset($query['id']);
		}

		if(isset($query['view']) AND ($query['view'] == 'profile')){

			$segments[] = 'checklist';
			$segments[] = 'profile';
			unset($query['view']);

			if(isset($query['userid'])){
				$sql = "SELECT `name` FROM `#__users` WHERE `id` = '".(int)$query['userid']."' ";
				$db->setQuery( $sql );
				$uname = $db->loadResult();

				$uname = str_replace(" ", "-", strtolower($uname));
				$segments[] = $uname.".html";

				unset($query['userid']);
			}
		}

		if(isset($query['view']) AND ($query['view'] == 'editprofile')){
			$segments[] = 'checklist';
			$segments[] = 'editprofile';
			unset($query['view']);
		}

		if(isset($query['view']) AND ($query['view'] == 'lists')){

			$segments[] = 'lists';
			unset($query['view']);

			if(isset($query['userid'])){
				$sql = "SELECT `name` FROM `#__users` WHERE `id` = '".(int)$query['userid']."' ";
				$db->setQuery( $sql );
				$uname = $db->loadResult();

				$uname = str_replace(" ", "-", strtolower($uname));
				$segments[] = $uname.".html";

				unset($query['userid']);
			}

		}

		if(isset($query['view']) AND ($query['view'] == 'edit_checklist')){
			
			$segments[] = 'edit_checklist';
			unset($query['view']);

			unset($query['tmpl']);
		}

		if(isset($query['view']) AND ($query['view'] == 'register')){
			$segments[] = 'register';
			unset($query['view']);
		}
		
	}

	if(isset($query['task'])){
		if($query['task'] == 'frontend.clone_checklist'){
			
			$segments[] = 'clone_checklist';
			unset($query['task']);

			if(isset($query['id'])){
				$sql = "SELECT `alias` FROM `#__checklist_lists` WHERE id = '".(int)$query['id']."' ";
				$db->setQuery( $sql );
				$checklist_alias= $db->loadResult();

				if(!$checklist_alias){
					$sql = "SELECT `title` FROM `#__checklist_lists` WHERE id = '".(int)$query['id']."' ";
					$db->setQuery( $sql );
					$checklist_title= $db->loadResult();

					$checklist_alias = str_replace(" ","-", strtolower($checklist_title));
				}

				$segments[] = $checklist_alias.".html";

				unset($query['id']);
			}
		}
	}

	return $segments;
}

function ChecklistParseRoute( $segments )
{
	$db	= JFactory::getDBO();
	$vars = array();
	$views = array(
		'frontend',
		'checklist'
	);
	 

	if(isset($segments[0])){		

		for($i = 0; $i < count($segments); $i++){
			if(strlen($segments[$i]) > 5 && substr($segments[$i], -5) == '.html'){
				$segments[$i] = substr($segments[$i], 0, -5);
			}
		}

		if($segments[0] == 'frontend'){
			$vars['view']	= $segments[0];
		}

		if($segments[0] == 'main'){
			$vars['view']	= $segments[0];
		}

		if(isset($segments[0]) && $segments[0] == 'checklist' && isset($segments[1])){

			$vars['view']	= $segments[0];

			$segments[1] = str_replace(":", "-", $segments[1]);

			$query = "SELECT `id` FROM `#__checklist_lists` WHERE `alias` = '{$segments[1]}' ";
			$db->setQuery( $query );
			$vars['id'] = $db->loadResult();

			$query = "SELECT `user_id` FROM `#__checklist_lists` WHERE `alias` = '{$segments[1]}' ";
			$db->setQuery( $query );
			$vars['user_id'] = $db->loadResult();
		}

		if(isset($segments[2]) && $segments[1] == 'tag' && isset($segments[2])){

			$vars['view']	= $segments[1];

			$segments[2] = str_replace(":", "-", $segments[2]);
			$segments[2] = str_replace("-", " ", $segments[2]);

			$query = "SELECT `id` FROM `#__checklist_tags` WHERE LOWER(`name`) = '{$segments[2]}' ";
			$db->setQuery( $query );
			$vars['id'] = $db->loadResult();

		}

		if(isset($segments[1]) && $segments[1] == 'profile' && isset($segments[2])){

			$vars['view']	= $segments[1];
			$segments[2] = str_replace(":", "-", $segments[2]);
			$segments[2] = str_replace("-", " ", $segments[2]);

			$query = "SELECT `id` FROM `#__users` WHERE LOWER(`name`) = '{$segments[2]}' ";
			$db->setQuery( $query );
			$vars['userid'] = $db->loadResult();
		}

		if(isset($segments[1]) && $segments[1] == 'editprofile'){

			$vars['view']	= $segments[1];

		}

		if(isset($segments[0]) && $segments[0] == 'lists' && isset($segments[1])){
			$vars['view']	= $segments[0];
			$segments[1] = str_replace(":", "-", $segments[1]);
			$segments[1] = str_replace("-", " ", $segments[1]);

			$query = "SELECT `id` FROM `#__users` WHERE LOWER(`name`) = '{$segments[1]}' ";
			$db->setQuery( $query );
			$vars['userid'] = $db->loadResult();
		}

		if(isset($segments[0]) && $segments[0] == 'clone_checklist' && isset($segments[1])){
			$vars['task'] = 'frontend.clone_checklist';

			$segments[1] = str_replace(":", "-", $segments[1]);

			$query = "SELECT `id` FROM `#__checklist_lists` WHERE `alias` = '{$segments[1]}' ";
			$db->setQuery( $query );
			$vars['id'] = $db->loadResult();

		}

		if(isset($segments[0]) && $segments[0] == 'edit_checklist'){
			$vars['view']	= $segments[0];
			$vars['tmpl']	= 'component';
		}

		if(isset($segments[0]) && $segments[0] == 'register'){
			$vars['view']	= $segments[0];
		}

	}

	return $vars;
}

