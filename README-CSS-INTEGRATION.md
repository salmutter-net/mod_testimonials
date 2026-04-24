# mod_testimonials CSS Integration Guide

This module uses a **Base + Local** CSS architecture that works across all Joomla projects.

## How It Works

1. **Base CSS** (module provides): Functional layout from `media/mod_testimonials/css/default.css`
2. **Local CSS** (template provides): Project-specific branding, colors, typography

CSS loads in order: `base → local` (local naturally overrides base)

---

## Integration Methods (choose one)

### Method 1: Template Override File (Simplest - Works Everywhere)

Create this file in your template:
```
templates/YOURTEMPLATE/html/mod_testimonials/css/local.css
```

The module auto-detects and loads it. Example content:

```css
/* Project-specific overrides */
.mod-testimonals {
    --mt-bg: #f8f9fa;
    --mt-text: #1a1a1a;
    --mt-gap: 2rem;
}

.mod-testimonals__title {
    font-family: 'Your Brand Font', sans-serif;
    font-size: 2rem;
}
```

**Pros:** No code changes, works in any template  
**Cons:** Static file, no build pipeline integration

---

### Method 2: WebAsset Pre-Registration (Advanced)

In your template's `index.php` (before the module renders):

```php
$wa = $this->getWebAssetManager();

// Register your custom CSS
$wa->registerStyle(
    'mod_testimonials.local',
    'templates/yourtemplate/css/modules/testimonials.css',
    [],
    ['media' => 'all']
);
```

The module detects `mod_testimonials.local` is registered and loads it.

**Pros:** Full control over path, can use template parameters  
**Cons:** Requires template code modification

---

### Method 3: Template Helper (Vite/Build-Pipeline Templates)

For templates using Vite, Webpack, or similar build systems (like customersuite).

In your template's `logic.php` or `index.php`, implement:

```php
/**
 * Register module CSS via template's build system.
 *
 * @param \Joomla\CMS\WebAsset\WebAssetManager $wa
 * @param string $moduleName  e.g., 'mod_testimonials'
 * @param string $layout      e.g., 'default'
 */
function tplYourtemplateRegisterModuleCss($wa, $moduleName, $layout = 'default'): void
{
    $manifest = yourTemplateViteManifest(); // Your manifest loader
    
    // Check for template override first
    $overrideEntry = "html/{$moduleName}/{$layout}.scss";
    if (isset($manifest[$overrideEntry])) {
        $wa->registerAndUseStyle(
            "tpl.yourtemplate.{$moduleName}",
            yourTemplateViteCss($overrideEntry),
            [],
            ['media' => 'all']
        );
        return;
    }
    
    // Fall back to module's SCSS if template has built it
    $modEntry = "../modules/{$moduleName}/tmpl/{$layout}.scss";
    if (isset($manifest[$modEntry])) {
        $wa->registerAndUseStyle(
            "tpl.yourtemplate.{$moduleName}",
            yourTemplateViteCss($modEntry),
            [],
            ['media' => 'all']
        );
    }
}
```

When this function exists, the module delegates ALL CSS loading to the template helper (base + local combined).

**Pros:** Vite HMR, SCSS compilation, dependency optimization  
**Cons:** Requires template to implement helper

---

## CSS Custom Properties Reference

Base CSS defines these custom properties for easy customization:

```css
.mod-testimonals {
    --mt-bg: #f5f5f5;          /* Module background */
    --mt-text: #333;            /* Title text color */
    --mt-nav-bg: #fff;          /* Nav button background */
    --mt-nav-hover: #e0e0e0;    /* Nav button hover */
    --mt-logo-bg: #fff;         /* Logo card background */
    --mt-gap: 1rem;             /* Gap between logos */
    --mt-item-width: 200px;     /* Logo card width */
}
```

Override these in your local CSS instead of rewriting rules.

---

## File Structure Summary

```
mod_testimonials/
├── media/css/default.css          ← Base CSS (always loaded)
└── tmpl/default.php               ← Template

templates/yourtemplate/
└── html/mod_testimonials/css/
    └── local.css                  ← Local CSS (optional, auto-detected)
```

---

## Backwards Compatibility

- **Old projects without helper:** Module loads base CSS, checks for local.css
- **New projects with helper:** Helper takes over, can use Vite/SCSS
- **Hybrid:** Method 2 (WebAsset) works in both old and new templates

The module **only loads CSS when present on the page** (Joomla's module rendering lifecycle).
