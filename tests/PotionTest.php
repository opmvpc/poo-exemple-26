<?php

declare(strict_types=1);

use Dungeon\Item;
use Dungeon\Potion;
use Dungeon\Rarity;

test('une potion est un Item', function (): void {
    expect(new Potion('Potion de soin', 0.5, 5))->toBeInstanceOf(Item::class);
});

test('une potion garde ses points de soin', function (): void {
    expect((new Potion('Élixir', 0.5, 12))->healing)->toBe(12);
});

test('describe décrit une potion', function (): void {
    expect((new Potion('Potion de soin', 0.5, 5))->describe())
        ->toBe('Potion de soin : potion (0.5 kg, +5 PV)');
});

test('une potion rare vaut plus cher', function (): void {
    expect((new Potion('Élixir', 0.5, 12, Rarity::Rare))->value())->toBe(0.75);
});
