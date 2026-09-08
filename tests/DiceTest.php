<?php

declare(strict_types=1);

use Dungeon\Dice;

test('un dé garde son nombre de faces', function (): void {
    $dice = new Dice(6);

    expect($dice->sides)->toBe(6);
});

test('un lancer reste entre 1 et le nombre de faces', function (): void {
    $dice = new Dice(6);

    // On lance 100 fois : le hasard ne doit jamais sortir des bornes.
    for ($i = 0; $i < 100; $i++) {
        expect($dice->roll())->toBeGreaterThanOrEqual(1)->toBeLessThanOrEqual(6);
    }
});

test('les fabriques statiques créent les dés classiques', function (): void {
    expect(Dice::d6()->sides)->toBe(6);
    expect(Dice::d20()->sides)->toBe(20);
});

test('un dé à moins de 2 faces est refusé', function (): void {
    expect(fn () => new Dice(1))->toThrow(InvalidArgumentException::class);
});
