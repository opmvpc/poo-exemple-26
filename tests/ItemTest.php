<?php

declare(strict_types=1);

use Dungeon\Item;
use Dungeon\Rarity;
use Dungeon\Weapon;

test('Item est abstraite : on ne ramasse jamais « un objet »', function (): void {
    expect((new ReflectionClass(Item::class))->isAbstract())->toBeTrue();
});

test('un objet a un nom et un poids', function (): void {
    $item = new Weapon('Épée courte', 2.0, 5);

    expect($item->name())->toBe('Épée courte');
    expect($item->weight())->toBe(2.0);
});

test('la rareté vaut Common par défaut', function (): void {
    expect((new Weapon('Épée courte', 2.0, 5))->rarity())->toBe(Rarity::Common);
});

test('la valeur est le poids multiplié par la rareté', function (): void {
    expect((new Weapon('Épée courte', 2.0, 5))->value())->toBe(2.0);
    expect((new Weapon('Épée courte', 2.0, 5, Rarity::Rare))->value())->toBe(3.0);
    expect((new Weapon('Épée courte', 2.0, 5, Rarity::Legendary))->value())->toBe(6.0);
});

test('Item implements Stringable : (string) renvoie describe()', function (): void {
    $item = new Weapon('Épée courte', 2.0, 5);

    expect($item)->toBeInstanceOf(Stringable::class);
    expect((string) $item)->toBe($item->describe());
});
