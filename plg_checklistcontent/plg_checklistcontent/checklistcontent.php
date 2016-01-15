<?php
/**
* Checklist plugin for Joomla
* @version $Id: checklistcontent.php 2014-06-03 17:30:15
* @package Checklist
* @subpackage checklistcontent.php
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* <b>Usage:</b>
* <code>{checklist id=6}</code>
*/

class plgContentChecklistcontent extends JPlugin {

	var $checklist = null;

	function onContentPrepare( $context, &$article, &$params, $page = 0 ) {
	
		JFactory::getLanguage()->load('com_checklist', JPATH_SITE, 'en-GB', true);
		
		// simple performance check to determine whether bot should process further
		if ( strpos( $article->text, 'checklist' ) === false ) {
			return true;
		}
	
		// define the regular expression for the bot
		$regex = '/{checklist\s*.*?}/i';	

		// perform the replacement
		$article->text = preg_replace_callback( $regex, array(&$this, 'checklistCode_replacer'), $article->text, 1 );
	
		$article->text = preg_replace( $regex, '', $article->text );
		return true;
	}
	
	/**
	* Replaces the matched tags an image
	* @param array An array of matches (see preg_match_all)
	* @return string
	*/
	
	protected function checklistCode_replacer( &$matches ) {
		
		$db = JFactory::getDBO();
		$text = $matches[0];	
		$rres[1] = $matches[0];
		$rres[1] = str_replace('{checklist','', $rres[1]);
		$rres[1] = str_replace('}','', $rres[1]);
		$checklist_id = (int)str_replace('id=','', $rres[1]);

		if(intval($checklist_id)) { 
			
			JLoader::register('ChecklistHelper', JPATH_SITE . '/components/com_checklist/helpers/checklist.php');


			$app = JFactory::getApplication();
			$app->input->set('id', $checklist_id);

			require_once JPATH_SITE.'/components/com_checklist/models/checklist.php';
			$model = JModelLegacy::getInstance('Checklist', 'ChecklistModel');

			$this->viewClass($model);
		
			if(!empty($this->checklist)){
				$db->setQuery("SELECT `name` FROM `#__checklist_templates` WHERE `id` = '".$this->checklist->template."'");
				$template = $db->loadResult();

				$template = str_replace(" ", "_", strtolower(trim($template)));
			} else {
				$template = 'unavailable';
			}

			@ob_start();
			require_once JPATH_SITE.'/components/com_checklist/views/checklist/tmpl/'.$template.'.php';
			$text = @ob_get_contents();			
			@ob_end_clean();
		}


		return $text;
	}

	public function getConfig()
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT * FROM `#__checklist_config`" .
			" ORDER BY `setting_name`";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$config = (object) array();
		
		if (isset($rows))
		{
			foreach ($rows as $row)
			{
				$config->{$row->setting_name} = $row->setting_value;
			}
		}
		
		return $config;
	}

	public function viewClass($model){

		$doc = JFactory::getDocument();
		$this->config = $this->getConfig();

		$allow_edit = false;
        $user = JFactory::getUser();
		$app = JFactory::getApplication();
		$userid = JFactory::getApplication()->input->get('userid', 0);
		$this->userid = $userid;

		$this->checklist = $model->getChecklist();

		if(!empty($this->checklist)){

			$allowGroups = ChecklistHelper::getAllowGroups();
			$this->allow_comment = (in_array($this->checklist->comment_access, $allowGroups)) ? true : false;

			$this->allow_edit = $model->checkAllowEdit($userid, $this->checklist->id);

			$this->edit_mode = false;
			if($this->allow_edit){
				if(isset($_SESSION['edit_mode'][$this->checklist->id]) && $_SESSION['edit_mode'][$this->checklist->id]){
					$this->edit_mode = true;
				}
			}			
		
			$groups = $model->getGroups();
			$items  = $model->getItems();
		
			if(count($groups)){
				foreach($groups as &$group){
					$group->items = array();
					foreach($items as $item){
						if($item->group_id == $group->id){
							$group->items[] = $item;
						}
					}
					
				}
			}
			
			$this->groups = $groups;
					
			$this->authorized = false;
			if($user->id){
				$this->authorized = true;
				$this->checkedList = $model->getCheckedList($this->checklist->id);
			}
			
			$this->tags = $model->getTags($this->checklist->id);
			//looking for old metadata
			$metadata = new stdClass ();
			$metadata->metakey = $this->checklist->meta_keywords;
			$metadata->metadesc = $this->checklist->meta_description;

			$keywords = '' . $metadata->metakey;
			if ( empty($keywords) && count($this->tags)) {
				foreach ($this->tags as $tag) {
					if ( !empty ($tag->name) ) {
						if ( empty($keywords) ) {
							$keywords .= $tag->name;
						} else $keywords .= ', ' . $tag->name;
					}
				}
			}

			$metadescription = $doc->getDescription().' '.$metadata->metadesc;
			if ( $metadescription != '')
				$doc->setDescription($metadescription);
			if ( !empty($row->metakey) ) {
				$keywords .= ' ' . $row->metakey;
			}

			$doc->setMetaData('keywords', $doc->getMetaData('keywords') . ' ' . $keywords);
			$doc->setTitle($doc->getTitle().'-'.$this->checklist->title);

			//fb Open Graph Metatags
			$e = array();

			$uri_userid = ($userid) ? '&userid='.$userid : '';
			
			$e['permalink'] = JRoute::_('index.php?option=com_checklist&view=publication&id='.$this->checklist->id.$uri_userid, false, -1);
			$e['title'] = $this->checklist->title;
			$e['description'] = $this->checklist->meta_description;
			$e['tags'] = $this->tags;
			$e['publish_date'] = $this->checklist->publish_date;

			if($user->id){
				$e['author'] = $user->name;
			} else {
				$author = JFactory::getUser($userid);
				$e['author'] = $author->id;
			}

			$e['custom_metatags'] = array();
			if($this->checklist->custom_metatags != ''){
				$e['custom_metatags'] = json_decode($this->checklist->custom_metatags);
			}

			$this->fbOpengraphMeta($e);
			
		}

	}

	private function fbOpengraphMeta($e)
	{
		$doc = JFactory::getDocument();

		$fbadmin = $this->config->fbadmin;
		$fbappid = $this->config->fbappid;

		$permalink = $e['permalink'];
		$meta_title = $e['title'];
		$meta_description = $e['description'];
		
		$meta_tags = $e['tags'];		
		$meta_publish_time = $e['publish_date'];
		$meta_author = $e['author'];

		$doc->addCustomTag($this->getMetatags($permalink, $meta_title, $meta_description, $meta_publish_time, $fbadmin, $fbappid, $meta_tags, $meta_author, $e));
	}

	private function getMetatags($permalink, $meta_title, $meta_description, $meta_publish_time, $fbadmin, $fbappid, $meta_tags, $meta_author, $e = array())
	{
		$metatags = '';
		if ( $fbadmin !== '' ) $metatags = "<meta property=\"fb:admins\" content=\"$fbadmin\" />\n";
		if ( $fbappid !== '' ) $metatags .= "<meta property=\"fb:app_id\" content=\"$fbappid\" />\n";
		$metatags .= "<meta property=\"og:url\" content=\"$permalink\" />\n";
		$metatags .= "<meta property=\"og:title\" content=\"$meta_title\" />\n";
		$metatags .= "<meta property=\"og:description\" content=\"$meta_description\" />\n";
		$metatags .= "<meta property=\"og:type\" content=\"article\" />\n";
		$metatags .= "<meta property=\"article:author\" content=\"$meta_author\" />\n";
		$metatags .= "<meta property=\"article:published_time\" content=\"$meta_publish_time\" />\n";
		if ( !empty($meta_tags) ) {
			for ($i = 0; $i < count($meta_tags); $i++) {
				$metatags .= "<meta property=\"article:tag\" content=\"$meta_tags[$i]\" />\n";
			}
		}

		if ( !empty($e['custom_metatags']) )
		{
			foreach ($e['custom_metatags'] as $custom_tag_name => $custom_tag_value)
			{
				$metatags.='<meta name="'.$custom_tag_name.'" content="'.$custom_tag_value.'" />'."\n";
				$metatags.='<meta property="'.$custom_tag_name.'" content="'.$custom_tag_value.'" />'."\n";
			}
		}

		return $metatags;
	}
}
?>
