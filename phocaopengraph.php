<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

class plgSystemPhocaOpenGraphHelper
{
	public static function renderTag($name, $value, $type = 1) {
		$document 	= JFactory::getDocument();
		if ($type == 1) {
			$document->setMetadata(htmlspecialchars($name), htmlspecialchars($value));
		} else {
			$document->addCustomTag('<meta property="'.htmlspecialchars($name).'" content="' . htmlspecialchars($value) . '" />');
		}
	}
	
}

class plgSystemPhocaOpenGraph extends JPlugin
{
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}
	
	/*function onBeforeRender() {
		$app 	= JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		//$view	= JRequest::getCmd('view');
		
		if ($option == 'com_phocainclude') {
			$document 	= JFactory::getDocument();
			$type		= $this->params->get('render_type', 1);
			plgSystemPhocaOpenGraphHelper::renderTag('og:site_name', 'Phoca', $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:title', $document->title, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:description', $document->description, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:url', $document->base, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:type', 'website', $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:image', 'http://www.phoca.cz/phoca.png', $type);	
		}
	}*/
	
	function onBeforeRender() {
		$app 	= JFactory::getApplication();
		$option	= JRequest::getCmd('option');
		//$view	= JRequest::getCmd('view');
		
		$format = JRequest::getWord('format');
		if ($format == 'feed' || $format == 'pdf' || $format == 'json') {
			return true;
		}
		
		if ($app->getName() != 'site') { return;}
		
		//com_phocadownload
		//com_phocadocumentation
		//com_phocainclude
		if ($option != 'com_content') {
			$document 	= JFactory::getDocument();
			
			$type		= $this->params->get('render_type', 1);
			plgSystemPhocaOpenGraphHelper::renderTag('og:site_name', 'Phoca', $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:title', $document->title, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:description', $document->description, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:url', $document->base, $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:type', 'website', $type);
			plgSystemPhocaOpenGraphHelper::renderTag('og:image', 'http://www.phoca.cz/phoca.png', $type);	
		}
	}
}
?>