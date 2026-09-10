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

    expect($item->name)->toBe('Épée courte');
    expect($item->weight)->toBe(2.0);
});

test('un poids négatif est refusé', function (): void {
    expect(fn () => new Weapon('Plume', -1.0, 1))
        ->toThrow(InvalidArgumentException::class, 'Un poids n\'est pas négatif, -1 reçu.');
    expect((new Weapon('Plume', 0.0, 1))->weight)->toBe(0.0);
});

test('le nom et le poids sont readonly', function (): void {
    $item = new Weapon('Épée courte', 2.0, 5);

    expect(function () use ($item): void {
        // @phpstan-ignore-next-line : c'est justement l'erreur qu'on veut voir.
        $item->weight = 0.0;
    })->toThrow(Error::class);
});

test('la rareté vaut Common par défaut', function (): void {
    expect((new Weapon('Épée courte', 2.0, 5))->rarity)->toBe(Rarity::Common);
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
