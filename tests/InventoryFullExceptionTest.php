<?php

declare(strict_types=1);

use Dungeon\Inventory;
use Dungeon\InventoryFullException;
use Dungeon\Weapon;

test('c\'est une RuntimeException, donc une Exception', function (): void {
    $e = new InventoryFullException('test');

    expect($e)->toBeInstanceOf(RuntimeException::class);
    expect($e)->toBeInstanceOf(Exception::class);
    expect($e->getMessage())->toBe('test');
});

test('le message dit ce qui ne rentre pas et pourquoi', function (): void {
    $bag = new Inventory(3.0);

    try {
        $bag->add(new Weapon('Marteau de guerre', 12.0, 9));
        $this->fail('L\'exception aurait dû être levée.');
    } catch (InventoryFullException $e) {
        expect($e->getMessage())->toBe('"Marteau de guerre" ne rentre pas : le sac ne porte que 3 kg.');
    }
});

test('un catch ciblé laisse passer le reste, et le sac garde ce qu\'il avait', function (): void {
    $bag = new Inventory(3.0);

    try {
        $bag->add(new Weapon('Épée courte', 2.0, 5));
        $bag->add(new Weapon('Marteau de guerre', 12.0, 9));
    } catch (InventoryFullException) {
        // Une exception n'annule pas ce qui a été fait avant elle.
    }

    expect($bag->count())->toBe(1);
    expect($bag->totalWeight())->toBe(2.0);
});
