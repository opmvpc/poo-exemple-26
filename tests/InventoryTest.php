<?php

declare(strict_types=1);

use Dungeon\Inventory;
use Dungeon\Item;

test('un sac neuf est vide', function (): void {
    $bag = new Inventory();

    expect($bag->count())->toBe(0);
    expect($bag->totalWeight())->toBe(0.0);
    expect($bag->maxWeight())->toBe(20.0);
});

test('on ajoute un objet et on le retrouve', function (): void {
    $bag = new Inventory();

    expect($bag->add(new Item('Épée courte', 2.0)))->toBeTrue();
    expect($bag->count())->toBe(1);
    expect($bag->has('Épée courte'))->toBeTrue();
    expect($bag->has('Bouclier'))->toBeFalse();
});

test('le poids total est la somme des poids', function (): void {
    $bag = new Inventory();
    $bag->add(new Item('Épée courte', 2.0));
    $bag->add(new Item('Potion', 0.5));

    expect($bag->totalWeight())->toBe(2.5);
});

test('un objet trop lourd est refusé et n\'entre pas dans le sac', function (): void {
    $bag = new Inventory(5.0);

    expect($bag->add(new Item('Enclume', 50.0)))->toBeFalse();
    expect($bag->count())->toBe(0);
});

test('on peut retirer un objet par son nom', function (): void {
    $bag = new Inventory();
    $bag->add(new Item('Potion', 0.5));

    $bag->remove('Potion');

    expect($bag->has('Potion'))->toBeFalse();
    expect($bag->count())->toBe(0);
});

test('retirer un objet absent ne casse rien', function (): void {
    $bag = new Inventory();
    $bag->add(new Item('Potion', 0.5));

    $bag->remove('Dragon en peluche');

    expect($bag->count())->toBe(1);
});
