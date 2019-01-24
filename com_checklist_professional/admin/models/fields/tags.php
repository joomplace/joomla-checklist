<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Checklist.
 *
 */

class JFormFieldTags extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Tags';

	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 */
	protected function getOptions()
	{
		// Initialise variables.
		$options = array();

		$db	= JFactory::getDbo();
		$query	= "(SELECT '- Select tag -' AS `text`, '0' AS `value` FROM `#__users` LIMIT 0, 1) UNION (SELECT `name` AS `text`, `id` AS `value` FROM `#__checklist_tags`)";
		$db->setQuery($query);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
            JFactory::getApplication()->enqueueMessage($db->getErrorMsg(), 'error');
		}
		
		return $options; 
	} 
}