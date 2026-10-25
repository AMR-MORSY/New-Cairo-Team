<?php

use App\Enums\SiteCategories;

it('takes NDL off and puts it back without changing anything else', function (SiteCategories $category) {
    $base = SiteCategories::removeNodality($category);

    expect($base)->not->toContain('NDL');
    expect(SiteCategories::mapSiteNodality($base))->toBe(SiteCategories::mapSiteNodality($category));

    if (str_contains($category->value, 'NDL')) {
        expect(SiteCategories::mapSiteNodality($base))->toBe($category->value);
    } else {
        expect($base)->toBe($category->value);
    }
})->with(SiteCategories::cases());

it('returns null for a value that is not a category', function () {
    expect(SiteCategories::removeNodality('Spaceship'))->toBeNull();
});

it('takes VIP off and puts it back without changing anything else', function (SiteCategories $category) {
    $base = SiteCategories::downgradeFromVip($category);

    expect($base)->not->toContain('VIP');

    if (str_contains($category->value, 'VIP')) {
        expect(SiteCategories::upgradeToVip($base))->toBe($category->value);
    } else {
        expect($base)->toBe($category->value);
    }
})->with(SiteCategories::cases());

it('lists every category carrying a tag, in any combination', function () {
    expect(SiteCategories::withTag(SiteCategories::VIP))->toEqualCanonicalizing([
        'VIP', 'TP + VIP', 'VIP + NDL', 'VIP + G.SQR', 'VIP + NDL + G.SQR', 'VIP + NDL + TP', 'TP + G.SQR + VIP', 'TP + G.SQR + VIP + NDL',
    ]);

    expect(SiteCategories::withTag(SiteCategories::GOLDEN_SQUARE))->toEqualCanonicalizing([
        'G.SQR', 'NDL + G.SQR', 'TP + G.SQR', 'TP + G.SQR + NDL', 'VIP + G.SQR', 'VIP + NDL + G.SQR', 'TP + G.SQR + VIP', 'TP + G.SQR + VIP + NDL',
    ]);

    // a whole tag, not a substring: LDN and BSC carry no NDL
    expect(SiteCategories::withTag(SiteCategories::NDL))->not->toContain('LDN');
});
