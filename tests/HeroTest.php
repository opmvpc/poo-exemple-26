<?php

declare(strict_types=1);

use Dungeon\Hero;
use Dungeon\Inventory;

test('un héros naît avec tous ses points de vie', function (): void {
    $hero = new Hero('Arthur');

    expect($hero->name)->toBe('Arthur');
    expect($hero->maxHp())->toBe(10);
    expect($hero->hp())->toBe(10);
    expect($hero->isAlive())->toBeTrue();
});

test('takeDamage retire des points de vie', function (): void {
    $hero = new Hero('Arthur');

    $hero->takeDamage(3);

    expect($hero->hp())->toBe(7);
});

test('les points de vie ne descendent jamais sous zéro', function (): void {
    $hero = new Hero('Arthur');

    $hero->takeDamage(999);

    expect($hero->hp())->toBe(0);
    expect($hero->isAlive())->toBeFalse();
});

test('heal ne dépasse jamais le maximum', function (): void {
    $hero = new Hero('Arthur');
    $hero->takeDamage(4);

    $hero->heal(100);

    expect($hero->hp())->toBe(10);
});

test('le héros s\'affiche avec ses points de vie', function (): void {
    $hero = new Hero('Arthur');
    $hero->takeDamage(3);

    expect((string) $hero)->toBe('Arthur (7/10 PV)');
});

test('le nom est readonly : on ne peut pas le réécrire', function (): void {
    $hero = new Hero('Arthur');

    expect(function () use ($hero): void {
        // @phpstan-ignore-next-line : c'est justement l'erreur qu'on veut voir.
        $hero->name = 'Mordred';
    })->toThrow(Error::class);
});

test('le héros possède un inventaire dès sa création', function (): void {
    $hero = new Hero('Arthur');

    expect($hero->inventory())->toBeInstanceOf(Inventory::class);
    expect($hero->inventory()->count())->toBe(0);
});

test('attaquer renvoie la force du héros', function (): void {
    $hero = new Hero('Arthur', 10, 4);

    expect($hero->attack())->toBe(4);
});
