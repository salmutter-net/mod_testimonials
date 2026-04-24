<?php defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;

/** @var \Joomla\CMS\Application\SiteApplication $app */
/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$template = $app->getTemplate();

// ============================================================================
// CSS LOADING STRATEGY: Base + Optional Local Override
// ============================================================================
// This module ships with base CSS for layout/functionality. Templates can
// provide localized CSS for project-specific styling via several methods:
//
// 1. TEMPLATE HELPER (Vite/build-pipeline templates like customersuite):
//    Implement in template's logic.php:
//    function tpl{Templatename}RegisterModuleCss($wa, 'mod_testimonials')
//    The helper registers CSS via WebAssetManager (Vite manifest, etc.)
//
// 2. TEMPLATE OVERRIDE FILE (Works in ALL templates):
//    Create: /templates/{template}/html/mod_testimonials/css/local.css
//    Module auto-detects and loads this after base CSS.
//
// 3. WEBASSET PRE-REGISTRATION (Advanced templates):
//    In template's index.php before module render:
//    $wa->registerStyle('mod_testimonials.local', 'path/to/custom.css');
//
// CSS LOAD ORDER: base → local (local cascades/overrides base naturally)
// ============================================================================

$moduleName = 'mod_testimonials';
$layout = $params->get('layout', 'default');

// Check for template helper: tplTemplatenameRegisterModuleCss (camelCase template name)
$helperName = 'tpl' . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $template))) . 'RegisterModuleCss';
$hasHelper = function_exists($helperName);

if ($hasHelper) {
    // Strategy 1: Template helper handles everything (Vite/manifest based)
    $helperName($wa, $moduleName, $layout);
} else {
    // Strategy 2 & 3: Module handles CSS loading

    // Always load base CSS from media folder
    $wa->registerAndUseStyle(
        "{$moduleName}.base",
        "media/{$moduleName}/css/default.css",
        [],
        ['media' => 'all', 'weight' => 100]
    );

    // Check for localized CSS (3 methods in priority order)
    $localLoaded = false;

    // 3a. Check if template pre-registered 'mod_testimonials.local' style
    if ($wa->assetExists('style', "{$moduleName}.local")) {
        $wa->useStyle("{$moduleName}.local");
        $localLoaded = true;
    }

    // 3b. Check for template override CSS file
    if (!$localLoaded) {
        $localCssPath = JPATH_THEMES . "/{$template}/html/{$moduleName}/css/local.css";
        if (file_exists($localCssPath)) {
            $wa->registerAndUseStyle(
                "{$moduleName}.local",
                "templates/{$template}/html/{$moduleName}/css/local.css",
                [],
                ['media' => 'all', 'weight' => 200]
            );
            $localLoaded = true;
        }
    }
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

require ModuleHelper::getLayoutPath($moduleName, $layout);
