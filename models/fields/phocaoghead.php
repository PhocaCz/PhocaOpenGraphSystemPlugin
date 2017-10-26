<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;
jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldPhocaOGHead extends JFormField
{
	protected $type = 'PhocaOGHead';
	
	protected function getInput() {
		return '';
	}
	
	protected function getLabel() {
	
		// Temporary solution
		
		echo '<div class="clr"></div>';
		$image		= '';
		$style		= 'background: #CCE6FF; color: #0069CC; padding:5px; margin:0px; -webkit-border-radius: 3px;
border-radius: 3px;';
		
		if ($this->element['default']) {
		
			if ($image != '') {
				return '<div style="'.$style.'">'
				.'<table border="0"><tr>'
				.'<td valign="middle" align="center">'. $image.'</td>'
				.'<td valign="middle" align="center">'
				.'<strong>'. JText::_($this->element['default']) . '</strong></td>'
				.'</tr></table>'
				.'</div>';
			} else {
				return '<div style="'.$style.'">'
				.'<strong>'. JText::_($this->element['default']) . '</strong>'
				.'</div>';
			}
		} else {
			return parent::getLabel();
		}
		echo '<div class="clr"></div>';
	}
}
?>