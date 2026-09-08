<?php

declare(strict_types=1);

use Dungeon\Goblin;
use Dungeon\Monster;

test('un gobelin est un monstre', function (): void {
    expect(new Goblin())->toBeInstanceOf(Monster::class);
});

test('un gobelin s\'appelle Gobelin et a 5 PV', function (): void {
    $goblin = new Goblin();

    expect($goblin->name)->toBe('Gobelin');
    expect($goblin->maxHp())->toBe(5);
});

test('un gobelin frappe toujours pour 2', function (): void {
    expect((new Goblin())->attack())->toBe(2);
    expect((new Goblin())->attack())->toBe(2);
});

test('class_uses ne remonte pas aux parents', function (): void {
    // Le trait est collé dans Monster, pas dans Goblin : la liste est vide.
    expect(class_uses(Goblin::class))->toBe([]);
});
