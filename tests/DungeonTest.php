<?php

declare(strict_types=1);

use Dungeon\Dungeon;
use Dungeon\Room;
use Dungeon\Weapon;

test('le donjon fabrique ses salles à partir de noms', function (): void {
    $dungeon = new Dungeon(['Entrée', 'Salle des gardes', 'Trésor']);

    expect($dungeon->rooms)->toHaveCount(3);
    expect($dungeon->room(0))->toBeInstanceOf(Room::class);
    expect($dungeon->room(2)->name)->toBe('Trésor');
});

test('deux donjons ne partagent jamais une salle : c\'est une composition', function (): void {
    $a = new Dungeon(['Entrée']);
    $b = new Dungeon(['Entrée']);

    expect($a->room(0))->not->toBe($b->room(0));
});

test('on retrouve la même salle à chaque appel', function (): void {
    $dungeon = new Dungeon(['Entrée', 'Salle des gardes']);
    $dungeon->room(1)->drop(new Weapon('Épée courte', 2.0, 5));

    expect($dungeon->room(1)->loot?->name)->toBe('Épée courte');
    expect($dungeon->room(0)->describe())->toBe('Entrée : rien au sol');
});
