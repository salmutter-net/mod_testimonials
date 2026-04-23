<?php defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();

// Register module stylesheet
$wa->registerStyle('mod_testimonials', 'media/mod_testimonials/css/default.css')
   ->useStyle('mod_testimonials');

// Module data
$title = $params->get('title', '');
$logos = (array)$params->get('logos', []);

// Normalize logos to array
if (!is_array($logos)) {
    $logos = [];
}

// Bail out if no logos
if (empty($logos)) {
    return;
}

require ModuleHelper::getLayoutPath('mod_testimonials', $params->get('layout', 'default'));
