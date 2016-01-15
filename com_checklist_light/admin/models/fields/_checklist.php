<?php defined('_JEXEC') or die('Restricted Access');
/*
* Lightchecklist Component
* @package Lightchecklist
* @author JoomPlace Team
* @copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

JFormHelper::loadFieldClass('list');

class JFormField_Checklist extends JFormFieldList
{
	protected $type = '_checklist';
	//----------------------------------------------------------------------------------------------------
	public function __construct($form = null)
	{
		parent::__construct($form);
	}
	//----------------------------------------------------------------------------------------------------
	public function getLabel()
	{
		return parent::getLabel();
	}
	//----------------------------------------------------------------------------------------------------
	public function getInput()
	{
		$jinput = JFactory::getApplication()->input;
		
		$option = $jinput->get('option');
		
		$html = array();
		$attr = '';
		$this->multiple = false;
		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		
		if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
		{
			$attr .= ' disabled="disabled"';
		}
		
		$attr .= ($this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '');
		$attr .= ($this->multiple ? ' multiple="multiple"' : '');
		
		$options = (array) $this->getOptions();
		array_unshift($options, JText::_('COM_LIGHTCHECKLIST_BE_SELECT_CHECKLIST'));
		$selected = (array)$this->getSelected();
		
		if ((string) $this->element['readonly'] == 'true')
		{
			$html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
			$html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
		}
		else
		{
			$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $selected, $this->id);
		}
		
		return implode($html);
	}
	//----------------------------------------------------------------------------------------------------
	public function setProperty($name, $value)
	{
		$this->element[$name] = $value;
	}
	//----------------------------------------------------------------------------------------------------
	protected function getOptions()
	{
		$now = time();
		$db = JFactory::getDbo();
		
		$query = $db->getQuery(true);
		$query->select('l.`id` AS value, l.`title` AS text');
		$query->from('`#__checklist_lists` AS l');
		$query->where('l.`default` = 1 AND UNIX_TIMESTAMP(l.`publish_date`) <= '.$now);
		$query->order('l.`title` ASC');
		$db->setQuery($query);
		$options = $db->loadObjectList();
		
		return $options;
	}
	//----------------------------------------------------------------------------------------------------
	protected function getSelected()
	{
		$selected = array();
		
		if (sizeof($this->value) > 0)
		{
			$selected[] = $this->value;
		}
		else
		{
			$jinput = JFactory::getApplication()->input;
			
			$id = $jinput->get('id', 0);
			
			if ($id != 0)
			{
				$db = JFactory::getDbo();
				
				$query = $db->getQuery(true);
				$query->select('l.`id`');
				$query->from('`#__checklist_lists` AS l');
				$query->where('l.`id` = ' . (int) $id);
				$db->setQuery($query);
				$selected = $db->loadColumn(0);
			}
		}
		
		return $selected;
	}
	//----------------------------------------------------------------------------------------------------
	public function setValue($value)
	{
		$this->value = $value;
	}
}