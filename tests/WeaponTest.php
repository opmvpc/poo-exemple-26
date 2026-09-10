<?php

declare(strict_types=1);

use Dungeon\Item;
use Dungeon\Rarity;
use Dungeon\Weapon;

test('une arme est un Item', function (): void {
    expect(new Weapon('Épée courte', 2.0, 5))->toBeInstanceOf(Item::class);
});

test('une arme garde ses dégâts', function (): void {
    expect((new Weapon('Marteau de guerre', 12.0, 9))->damage)->toBe(9);
});

test('parent::__construct a bien été appelé : le nom et le poids sont là', function (): void {
    $sword = new Weapon('Épée courte', 2.0, 5);

    expect($sword->name)->toBe('Épée courte');
    expect($sword->weight)->toBe(2.0);
});

test('describe décrit une arme', function (): void {
    expect((new Weapon('Épée courte', 2.0, 5))->describe())
        ->toBe('Épée courte : arme (2 kg, 5 dégâts)');
});

test('une arme peut être rare', function (): void {
    $blade = new Weapon('Lame du dragon', 3.0, 20, Rarity::Legendary);

    expect($blade->rarity)->toBe(Rarity::Legendary);
    expect($blade->value())->toBe(9.0);
});
