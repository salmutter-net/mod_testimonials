<?php defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$template = $app->getTemplate();

$moduleName = 'mod_testimonials';

// Base CSS (always loaded from module)
$wa->registerAndUseStyle("{$moduleName}.base", "media/{$moduleName}/css/default.css", [], ['media' => 'all']);

// Localized CSS from template (clean path, no hash)
// Vite builds: templates/{template}/html/{module}/css/local.scss
// To: media/templates/site/{template}/css/{module}/local.css
$localCss = "media/templates/site/{$template}/css/{$moduleName}/local.css";
if (file_exists(JPATH_ROOT . "/{$localCss}")) {
	$wa->registerAndUseStyle("{$moduleName}.local", $localCss, [], ['media' => 'all']);
}

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
