<?php
/**
* Checklist Component for Joomla 3
* @package Checklist
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted Access');


abstract class PublicationDisplayMode
{
	const DirectLink = 0;			// Direct link.
	const PopupWindow = 1;			// Popup window.
	const DirectLinkNoTmpl = 2;		// Direct link without template.
	const ModalWindow = 3;			// Modal window.
}

abstract class PublicationFullscreenMode
{
	const Original = 0;				// Show original size.
	const FitToScreen = 1;			// Fit to screen.
}

abstract class PublicationZoomMode
{
	const Original = 0;					// Show original size.
	const AlwaysFitToScreen = 1;		// Always fit to screen.
	const FitToScreenIfLarger = 2;		// Fit to screen only if larger.
}

class PublicationTemplateFont
{
	static function FontsList( $key = false ) {
		$fonts = array(
			'"Times New Roman", Times, serif',
			'Georgia, serif',
			'"Palatino Linotype", "Book Antiqua", Palatino, serif',
			'Arial, Helvetica, sans-serif',
			'"Arial Black", Gadget, sans-serif',
			'"Comic Sans MS", cursive, sans-serif',
			'Impact, Charcoal, sans-serif',
			'"Lucida Sans Unicode", "Lucida Grande", sans-serif',
			'Tahoma, Geneva, sans-serif',
			'"Trebuchet MS", Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif',
			'"Courier New", Courier, monospace',
			'"Lucida Console", Monaco, monospace',
		);

		if ( $key === false )
			return $fonts;
		else
			return $fonts[ $key ];
	}

	static function FontSize()
	{
		return array(
			'10px' => '10px',
			'12px' => '12px',
			'14px' => '14px',
			'16px' => '16px',
			'18px' => '18px',
			'20px' => '20px',
			'22px' => '22px',
		);
	}

	static function P_margin()
	{
		return array(
			'0' => '0',
			'3px' => '3px',
			'5px' => '5px',
			'7px' => '7px',
			'9px' => '9px',
			'12px' => '12px',
			'14px' => '14px',
			'16px' => '16px',
			'18px' => '18px',
			'20px' => '20px',
		);
	}
	static function P_lineheight()
	{
		return array(
			'15px' => '15px',
			'17px' => '17px',
			'19px' => '19px',
			'21px' => '21px',
			'23px' => '23px',
			'25px' => '25px',
			'27px' => '27px',
			'29px' => '29px',
			'31px' => '31px',
		);
	}
}