<?php

declare(strict_types=1);

use Dungeon\Dragon;
use Dungeon\Monster;

test('un dragon est un monstre', function (): void {
    expect(new Dragon())->toBeInstanceOf(Monster::class);
});

test('un dragon s\'appelle Dragon et a 30 PV', function (): void {
    $dragon = new Dragon();

    expect($dragon->name)->toBe('Dragon');
    expect($dragon->maxHp)->toBe(30);
    expect((string) $dragon)->toBe('Dragon (30/30 PV)');
});

test('un dragon frappe pour 8', function (): void {
    expect((new Dragon())->attack())->toBe(8);
});

test('le polymorphisme : une boucle, deux réponses différentes', function (): void {
    /** @var Monster[] $monsters */
    $monsters = [new Dungeon\Goblin(), new Dragon(), new Dungeon\Goblin()];

    $total = 0;
    foreach ($monsters as $monster) {
        $total += $monster->attack();   // pas un seul if
    }

    expect($total)->toBe(12);
});
