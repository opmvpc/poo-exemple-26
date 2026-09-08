<?php

declare(strict_types=1);

use Dungeon\Item;

test('un objet a un nom et un poids', function (): void {
    $item = new Item('Épée courte', 2.0);

    expect($item->name())->toBe('Épée courte');
    expect($item->weight())->toBe(2.0);
});

test('un objet s\'affiche lisiblement', function (): void {
    expect((string) new Item('Corde', 1.5))->toBe('Corde (1.5 kg)');
});

test('un poids négatif est refusé', function (): void {
    expect(fn () => new Item('Bug', -1.0))->toThrow(InvalidArgumentException::class);
});
