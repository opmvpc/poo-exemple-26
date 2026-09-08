<?php

declare(strict_types=1);

use Dungeon\Fighter;
use Dungeon\HasHealth;
use Dungeon\Hero;
use Dungeon\Inventory;
use Dungeon\Potion;
use Dungeon\Weapon;

test('un héros naît avec tous ses points de vie', function (): void {
    $hero = new Hero('Arthur');

    expect($hero->name)->toBe('Arthur');
    expect($hero->maxHp())->toBe(10);
    expect($hero->hp())->toBe(10);
    expect($hero->strength)->toBe(2);
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

test('deux héros n\'ont jamais le même sac', function (): void {
    $arthur = new Hero('Arthur');
    $morgane = new Hero('Morgane');

    $arthur->inventory()->add(new Weapon('Épée courte', 2.0, 5));

    expect($arthur->inventory()->count())->toBe(1);
    expect($morgane->inventory()->count())->toBe(0);
});

test('à mains nues, attaquer renvoie la force du héros', function (): void {
    $hero = new Hero('Arthur', 10, 4);

    expect($hero->weapon())->toBeNull();
    expect($hero->attack())->toBe(4);
});

test('équiper une arme ajoute ses dégâts', function (): void {
    $hero = new Hero('Arthur');
    $sword = new Weapon('Épée courte', 2.0, 5);

    $hero->equip($sword);

    expect($hero->weapon())->toBe($sword);
    expect($hero->attack())->toBe(7);
});

test('boire une potion soigne et vide la potion du sac', function (): void {
    $hero = new Hero('Arthur');
    $potion = new Potion('Potion de soin', 0.5, 5);
    $hero->inventory()->add($potion);
    $hero->takeDamage(8);

    $hero->drink($potion);

    expect($hero->hp())->toBe(7);
    expect($hero->inventory()->has('Potion de soin'))->toBeFalse();
});

test('le héros signe le contrat Fighter et utilise le trait HasHealth', function (): void {
    expect(new Hero('Arthur'))->toBeInstanceOf(Fighter::class);
    expect(class_uses(Hero::class))->toContain(HasHealth::class);
});
