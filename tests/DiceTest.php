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

test('un dé a au moins deux faces : la validation vit dans le constructeur', function (): void {
    expect(fn () => new Dice(1))->toThrow(InvalidArgumentException::class, 'Un dé a au moins 2 faces, 1 reçu.');
    expect(fn () => new Dice(0))->toThrow(InvalidArgumentException::class);
    expect((new Dice(2))->sides)->toBe(2);
});

test('le nombre de faces est readonly : on ne peut pas le réécrire', function (): void {
    $dice = new Dice(6);

    expect(function () use ($dice): void {
        // @phpstan-ignore-next-line : c'est justement l'erreur qu'on veut voir.
        $dice->sides = 20;
    })->toThrow(Error::class);
});

