<?php defined('_JEXEC') or die;

/** @var string $title */
/** @var array $logos */

$modId = 'mod-testimonals-' . $module->id;
$scrollId = $modId . '-scroll';
?>
<div id="<?php echo $modId; ?>" class="mod-testimonals">
    <?php if ($title) : ?>
        <h2 class="mod-testimonals__title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>

    <div class="mod-testimonals__wrapper">
        <button
            type="button"
            class="mod-testimonals__nav mod-testimonals__nav--prev"
            aria-label="Vorherige Logos"
            disabled
            onclick="document.getElementById('<?php echo $scrollId; ?>').scrollBy({left: -300, behavior: 'smooth'})">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>

        <div id="<?php echo $scrollId; ?>" class="mod-testimonals__scroll">
            <ul class="mod-testimonals__list">
                <?php foreach ($logos as $logo) :
                    $image = $logo->image ?? '';
                    $alt = $logo->alt ?? '';
                    $link = $logo->link ?? '';
                    if (!$image) continue;
                ?>
                    <li class="mod-testimonals__item">
                        <?php if ($link) : ?>
                            <a href="<?php echo htmlspecialchars($link, ENT_QUOTES, 'UTF-8'); ?>"
                               class="mod-testimonals__link"
                               target="_blank"
                               rel="noopener noreferrer">
                        <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>"
                                 alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"
                                 class="mod-testimonals__logo"
                                 loading="lazy">
                        <?php if ($link) : ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <button
            type="button"
            class="mod-testimonals__nav mod-testimonals__nav--next"
            aria-label="Nächste Logos"
            disabled
            onclick="document.getElementById('<?php echo $scrollId; ?>').scrollBy({left: 300, behavior: 'smooth'})">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</div>

<script>
(function() {
    const scrollEl = document.getElementById('<?php echo $scrollId; ?>');
    const prevBtn = scrollEl.parentElement.querySelector('.mod-testimonals__nav--prev');
    const nextBtn = scrollEl.parentElement.querySelector('.mod-testimonals__nav--next');
    const wrapper = scrollEl.parentElement;

    function updateNav() {
        const canScrollLeft = scrollEl.scrollLeft > 0;
        const canScrollRight = scrollEl.scrollLeft + scrollEl.clientWidth < scrollEl.scrollWidth - 1;

        prevBtn.disabled = !canScrollLeft;
        nextBtn.disabled = !canScrollRight;

        // Hide entire nav if no overflow
        const hasOverflow = scrollEl.scrollWidth > scrollEl.clientWidth;
        wrapper.classList.toggle('mod-testimonals__wrapper--scrollable', hasOverflow);
    }

    // Initial check
    updateNav();

    // Update on scroll
    scrollEl.addEventListener('scroll', updateNav, { passive: true });

    // Update on resize
    window.addEventListener('resize', updateNav, { passive: true });
})();
</script>
