<?php

declare(strict_types=1);

use Dungeon\Inventory;
use Dungeon\InventoryFullException;
use Dungeon\Potion;
use Dungeon\Weapon;

test('un sac neuf est vide', function (): void {
    $bag = new Inventory();

    expect($bag->count())->toBe(0);
    expect($bag->totalWeight())->toBe(0.0);
    expect($bag->maxWeight())->toBe(20.0);
});

test('on ajoute un objet et on le retrouve', function (): void {
    $bag = new Inventory();

    $bag->add(new Weapon('Épée courte', 2.0, 5));

    expect($bag->count())->toBe(1);
    expect($bag->has('Épée courte'))->toBeTrue();
    expect($bag->has('Bouclier'))->toBeFalse();
});

test('le poids total est la somme des poids', function (): void {
    $bag = new Inventory();
    $bag->add(new Weapon('Épée courte', 2.0, 5));
    $bag->add(new Potion('Potion de soin', 0.5, 5));

    expect($bag->totalWeight())->toBe(2.5);
});

test('un objet trop lourd lève une InventoryFullException et n\'entre pas', function (): void {
    $bag = new Inventory(5.0);

    expect(fn () => $bag->add(new Weapon('Enclume', 50.0, 1)))
        ->toThrow(InventoryFullException::class);

    expect($bag->count())->toBe(0);
    expect($bag->totalWeight())->toBe(0.0);
});

test('on peut retirer un objet par son nom', function (): void {
    $bag = new Inventory();
    $bag->add(new Potion('Potion de soin', 0.5, 5));

    $bag->remove('Potion de soin');

    expect($bag->has('Potion de soin'))->toBeFalse();
    expect($bag->count())->toBe(0);
});

test('retirer un objet absent ne casse rien', function (): void {
    $bag = new Inventory();
    $bag->add(new Potion('Potion de soin', 0.5, 5));

    $bag->remove('Dragon en peluche');

    expect($bag->count())->toBe(1);
});

test('count() et foreach fonctionnent grâce aux interfaces natives', function (): void {
    $bag = new Inventory();
    $bag->add(new Weapon('Épée courte', 2.0, 5));
    $bag->add(new Potion('Potion de soin', 0.5, 5));

    expect(count($bag))->toBe(2);

    $lines = [];
    foreach ($bag as $item) {
        $lines[] = (string) $item;
    }

    expect($lines)->toBe([
        'Épée courte : arme (2 kg, 5 dégâts)',
        'Potion de soin : potion (0.5 kg, +5 PV)',
    ]);
});
