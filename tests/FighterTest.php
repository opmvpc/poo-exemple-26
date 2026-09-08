<?php

declare(strict_types=1);

use Dungeon\Fighter;
use Dungeon\Goblin;
use Dungeon\Hero;
use Dungeon\Monster;

/** Ne demande ni un Hero ni un Goblin : deux objets qui savent se battre. */
function strike(Fighter $attacker, Fighter $target): void
{
    $target->takeDamage($attacker->attack());
}

test('Hero et Monster signent le même contrat', function (): void {
    expect(new Hero('Arthur'))->toBeInstanceOf(Fighter::class);
    expect(new Goblin())->toBeInstanceOf(Fighter::class);
    expect(is_subclass_of(Monster::class, Fighter::class))->toBeTrue();
});

test('une fonction typée par l\'interface accepte les deux', function (): void {
    $arthur = new Hero('Arthur');
    $goblin = new Goblin();

    strike($arthur, $goblin);
    strike($goblin, $arthur);

    expect((string) $arthur)->toBe('Arthur (8/10 PV)');
    expect((string) $goblin)->toBe('Gobelin (3/5 PV)');
});

test('l\'interface ne déclare que des signatures, aucun code', function (): void {
    $contract = new ReflectionClass(Fighter::class);

    expect($contract->isInterface())->toBeTrue();
    expect($contract->getProperties())->toBe([]);
    expect(array_map(
        static fn (ReflectionMethod $m): string => $m->getName(),
        $contract->getMethods(),
    ))->toBe(['attack', 'takeDamage', 'isAlive']);
});
