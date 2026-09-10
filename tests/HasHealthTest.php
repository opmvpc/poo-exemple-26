<?php

declare(strict_types=1);

use Dungeon\Goblin;
use Dungeon\HasHealth;
use Dungeon\Hero;
use Dungeon\Monster;

test('le trait est collé dans Hero et dans Monster, et nulle part ailleurs', function (): void {
    expect(class_uses(Hero::class))->toContain(HasHealth::class);
    expect(class_uses(Monster::class))->toContain(HasHealth::class);
    expect(class_uses(Goblin::class))->toBe([]);
});

test('un trait n\'est pas un parent : Hero n\'est pas un Monster', function (): void {
    expect(is_subclass_of(Hero::class, Monster::class))->toBeFalse();
    expect((new ReflectionClass(HasHealth::class))->isTrait())->toBeTrue();
});

test('les mêmes règles de santé pour le héros et pour le monstre', function (): void {
    $hero = new Hero('Arthur');
    $goblin = new Goblin();

    $hero->takeDamage(999);
    $goblin->takeDamage(999);

    expect($hero->hp)->toBe(0);
    expect($goblin->hp)->toBe(0);

    $hero->heal(999);
    $goblin->heal(999);

    expect($hero->hp)->toBe(10);
    expect($goblin->hp)->toBe(5);
});
