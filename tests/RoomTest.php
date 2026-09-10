<?php

declare(strict_types=1);

use Dungeon\Room;
use Dungeon\Weapon;

test('une salle neuve n\'a rien au sol', function (): void {
    $room = new Room('Trésor');

    expect($room->name)->toBe('Trésor');
    expect($room->loot)->toBeNull();
    expect($room->describe())->toBe('Trésor : rien au sol');
});

test('la salle garde l\'objet qu\'on lui donne, pas une copie', function (): void {
    $room = new Room('Salle des gardes');
    $sword = new Weapon('Épée courte', 2.0, 5);

    $room->drop($sword);

    // Agrégation : c'est le même objet, pas un clone.
    expect($room->loot)->toBe($sword);
    expect($room->describe())->toBe('Salle des gardes : Épée courte');
});

test('le butin se lit de l\'extérieur mais ne se pose que par drop()', function (): void {
    $room = new Room('Salle des gardes');

    expect(function () use ($room): void {
        // private(set) : seule la salle décide de ce qu'il y a au sol.
        $room->loot = new Weapon('Épée courte', 2.0, 5);
    })->toThrow(Error::class);
});

test('take retire l\'objet du sol et le rend', function (): void {
    $room = new Room('Salle des gardes');
    $sword = new Weapon('Épée courte', 2.0, 5);
    $room->drop($sword);

    $taken = $room->take();

    expect($taken)->toBe($sword);
    expect($room->loot)->toBeNull();
    expect($room->take())->toBeNull();
});
