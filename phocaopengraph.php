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


class plgSystemPhocaOpenGraph extends JPlugin
{
	
	public $twitterEnable 	= 0;
	
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}
	
	
	public function renderTag($name, $value, $type = 1) {
		$document 	= JFactory::getDocument();
		$docType	= $document->getType();
		if ($docType == 'pdf' || $docType == 'raw' || $docType == 'json') {
			return;
		}
		
		// OG
		if ($type == 1) {
			$document->setMetadata(htmlspecialchars($name), htmlspecialchars($value));
		} else {
			$document->addCustomTag('<meta property="'.htmlspecialchars($name).'" content="' . htmlspecialchars($value) . '" />');
		}
		
		// Tweet with cards
		if ($this->twitterEnable == 1) {
			if ($name == 'og:title') {
				$document->setMetadata('twitter:title', htmlspecialchars($value));
			}
			if ($name == 'og:description') {
				$document->setMetadata('twitter:description', htmlspecialchars($value));
			}
			if ($name == 'og:image') {
				$document->setMetadata('twitter:image', htmlspecialchars($value));
			}
		}
	}
	
	/*function onBeforeRender() {
		$app 	= JFactory::getApplication();
		
		
		if ($option == 'com_phocainclude') {
			$document 	= JFactory::getDocument();
			$type		= $this->params->get('render_type', 1);
			$this->renderTag('og:site_name', 'Phoca', $type);
			$this->renderTag('og:title', $document->title, $type);
			$this->renderTag('og:description', $document->description, $type);
			$this->renderTag('og:url', $document->base, $type);
			$this->renderTag('og:type', 'website', $type);
			$this->renderTag('og:image', 'http://www.phoca.cz/phoca.png', $type);	
		}
	}*/
	
	function onBeforeRender() {
		$app 	= JFactory::getApplication();
		$option	= $app->input->get('option');
		$view	= $app->input->get('view');
		$format = $app->input->get('format');
		
		if ($format == 'feed' || $format == 'pdf' || $format == 'json' || $format == 'raw') {
			return true;
		}
		
		
		if ($app->getName() != 'site') { return;}
		
		
		// Allow to display in article
		$allowed		= 0;
		$articleIds 		= $this->params->get('enable_article', '');
		
		if ($option == 'com_content' && $view == 'categories') {
			$allowed		= 1;
		} else if ($option == 'com_content') {
			if ($articleIds != '') {
				$articleIdsA =  explode(',', $articleIds);
				if (!empty($articleIdsA)) {
					$articleId	= $app->input->get('id', 0, 'int');
					foreach ($articleIdsA as $k => $v) {
						if ($option == 'com_content' && (int)$articleId > 0 &&(int)$articleId == (int)$v) {
							//$articleAllowed = (int)$articleId;
							$allowed = (int)$articleId;
							break;
						}
					}
				}
			}
		}else if ($option != 'com_content') {
			$allowed		= 1;
		}
		
		//com_phocadownload
		//com_phocadocumentation
		//com_phocainclude
		
		if ($allowed > 0) {
			
			$document 				= JFactory::getDocument();
			$config 				= JFactory::getConfig();
			$type					= $this->params->get('render_type', 1);
			$this->twitterEnable 	= $this->params->get('twitter_enable', 0);
			$twitterCard 			= $this->params->get('twitter_card', 'summary_large_image');
			
			if ($this->twitterEnable == 1) {
				$this->renderTag('twitter:card', htmlspecialchars($twitterCard), 1);
				
				if ($this->params->get('twitter_site', '') != '') {
					$this->renderTag('twitter:site', $this->params->get('twitter_site', ''), 1);
				}
				
				if ($this->params->get('twitter_site', '') != '') {
					$this->renderTag('twitter:creator', $this->params->get('twitter_creator', ''), 1);
				}
			}
			
			
			// Site Name
			if ($this->params->get('site_name', '') != '') {
				$this->renderTag('og:site_name', $this->params->get('site_name', ''), $type);
			} else {
				$this->renderTag('og:site_name', $config->get('sitename'), $type);
			}
			$this->renderTag('og:title', $document->title, $type);
			$this->renderTag('og:description', $document->description, $type);
			$this->renderTag('og:url', $document->base, $type);
			$this->renderTag('og:type', 'website', $type);
			
			
			// Try to find image in content
			$imgSet = 0;
			if ($this->params->get('find_image_content') == 1) {
				
				$buffer = $document->getBuffer();
				$docB 	= '';
				if (isset($buffer['component']) && is_array($buffer)) {
					foreach ($buffer['component'] as $v) {
						if (is_array($v)) {
							foreach($v as $v2) {
								$docB .= (string)$v2;
							}
						} else {
							$docB .= (string)$v;
						}
					}
				} else {
					$docB .=	(string)$buffer;
				}
				
				preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $docB, $src);
				if (isset($src[1]) && $src[1] != '') {
					
					$absU = 0;
					// Test if this link is absolute http:// then do not change it
					$pos1 			= strpos($src[1], 'http://');
					if ($pos1 === false) {
					} else {
						$absU = 1;
					}
					
					// Test if this link is absolute https:// then do not change it
					$pos2 			= strpos($src[1], 'https://');
					if ($pos2 === false) {
					} else {
						$absU = 1;
					}
					
					if ($absU == 1) {
						$linkImg = $src[1];
					} else {
						$linkImg = JURI::base(false).$src[1];
						if ($src[1][0] == '/') {
							$myURI = new \Joomla\Uri\Uri(JURI::base(false));
							$myURI->setPath($src[1]);
							$linkImg = $myURI->toString();
						} else {
							$linkImg = JURI::base(false).$src[1];
						}
					}
					
					
					$this->renderTag('og:image', $linkImg, $type);
					$imgSet = 1;
				}
			}
			
			if ($this->params->get('image') != '' && $imgSet == 0) {
				$this->renderTag('og:image', JURI::base(false).$this->params->get('image'), $type);
			}
		}
	}
}
?>