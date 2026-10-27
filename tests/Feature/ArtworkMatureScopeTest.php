<?php

use App\Models\Artwork;

test('the mature and notMature scopes split artwork on the mature flag', function () {
    $safe = Artwork::factory()->create();
    $mature = Artwork::factory()->mature()->create();

    expect(Artwork::notMature()->pluck('id')->all())->toBe([$safe->id])
        ->and(Artwork::mature()->pluck('id')->all())->toBe([$mature->id]);
});
