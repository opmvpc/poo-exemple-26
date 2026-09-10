<?php

declare(strict_types=1);

use Dungeon\Fighter;
use Dungeon\Goblin;
use Dungeon\HasHealth;
use Dungeon\Monster;

test('Monster est abstraite : on ne croise jamais « un monstre »', function (): void {
    expect((new ReflectionClass(Monster::class))->isAbstract())->toBeTrue();
});

test('un monstre naît avec tous ses points de vie', function (): void {
    $goblin = new Goblin();

    expect($goblin->name)->toBe('Gobelin');
    expect($goblin->maxHp)->toBe(5);
    expect($goblin->hp)->toBe(5);
});

test('un monstre encaisse sans jamais descendre sous zéro', function (): void {
    $goblin = new Goblin();

    $goblin->takeDamage(2);
    expect($goblin->hp)->toBe(3);
    expect($goblin->isAlive())->toBeTrue();

    $goblin->takeDamage(999);
    expect($goblin->hp)->toBe(0);
    expect($goblin->isAlive())->toBeFalse();
});

test('un monstre s\'affiche avec ses points de vie', function (): void {
    expect((string) new Goblin())->toBe('Gobelin (5/5 PV)');
});

test('Monster signe le contrat Fighter et utilise le trait HasHealth', function (): void {
    expect(is_subclass_of(Monster::class, Fighter::class))->toBeTrue();
    expect(class_uses(Monster::class))->toContain(HasHealth::class);
});
