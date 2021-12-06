<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;

defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPhocaOGHead extends JFormField
{
	protected $type = 'PhocaOGHead';

	protected function getLabel() {
		return '';
	}

	protected function getInput() {

		// Temporary solution

		//echo '<div class="clr"></div>';
		$image		= '';
		$style		= '<style> .ph-options-head { background-image: linear-gradient(-90deg, #129ED9,#0a5c80); color: #fff; border-radius: 3px; padding: 1em;} @media (min-width: 992px) { .ph-options-head {margin-left: -240px;}} </style>';
		$document	= Factory::getDocument();
		$document->addCustomTag($style);


		if ($this->element['default']) {

			if ($image != '') {
				return '<div class="ph-options-head">'
				.'<table border="0"><tr>'
				.'<td valign="middle" align="center">'. $image.'</td>'
				.'<td valign="middle" align="center">'
				.'<strong>'. JText::_($this->element['default']) . '</strong></td>'
				.'</tr></table>'
				.'</div>';
			} else {
				return '<div class="ph-options-head">'
				.'<strong>'. JText::_($this->element['default']) . '</strong>'
				.'</div>';
			}
		} else {
			return parent::getLabel();
		}
		//echo '<div class="clr"></div>';
	}
}
?>
