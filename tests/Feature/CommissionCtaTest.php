<?php

/*
 * #53: every public commission CTA goes through CommissionCta.vue, labelled "Commissions".
 * These scan the Vue sources because the CTA is rendered client-side.
 */

function publicVueSources(): array
{
    $files = [resource_path('js/Layouts/PublicLayout.vue')];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('js/pages/Public')));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'vue') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

it('has a shared CommissionCta labelled Commissions that links to the commission contact form', function () {
    $path = resource_path('js/components/CommissionCta.vue');

    expect($path)->toBeFile();

    $source = file_get_contents($path);

    expect($source)
        ->toContain('Commissions')
        ->toContain("type: 'commission'")
        ->toContain('variant="accent"');
});

it('links to the commission form only through CommissionCta on public pages', function () {
    foreach (publicVueSources() as $file) {
        $source = file_get_contents($file);

        expect($source)->not->toContain('type=commission', "{$file} hardcodes the commission link");

        foreach (['Hire Me', 'Start a Commission', 'Request a Commission'] as $label) {
            expect($source)->not->toContain($label, "{$file} uses the old CTA label \"{$label}\"");
        }
    }
});

it('uses CommissionCta in the header, sheet, home and artwork detail page', function () {
    $layout = file_get_contents(resource_path('js/Layouts/PublicLayout.vue'));
    $home = file_get_contents(resource_path('js/pages/Public/Home.vue'));
    $artwork = file_get_contents(resource_path('js/pages/Public/Artwork/Show.vue'));

    expect(substr_count($layout, '<CommissionCta'))->toBe(2)
        ->and(substr_count($home, '<CommissionCta'))->toBe(2)
        ->and(substr_count($artwork, '<CommissionCta'))->toBe(1);
});
