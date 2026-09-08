<?php

declare(strict_types=1);

use Dungeon\Rarity;

test('chaque rareté a son multiplicateur', function (): void {
    expect(Rarity::Common->multiplier())->toBe(1.0);
    expect(Rarity::Rare->multiplier())->toBe(1.5);
    expect(Rarity::Legendary->multiplier())->toBe(3.0);
});

test('chaque rareté a son libellé français', function (): void {
    expect(Rarity::Common->label())->toBe('Commun');
    expect(Rarity::Rare->label())->toBe('Rare');
    expect(Rarity::Legendary->label())->toBe('Légendaire');
});

test('les cas portent une valeur texte et se retrouvent avec from()', function (): void {
    expect(Rarity::Rare->value)->toBe('rare');
    expect(Rarity::Rare->name)->toBe('Rare');
    expect(Rarity::from('legendary'))->toBe(Rarity::Legendary);
});

test('tryFrom renvoie null au lieu de lever', function (): void {
    expect(Rarity::tryFrom('epic'))->toBeNull();
    expect(fn () => Rarity::from('epic'))->toThrow(ValueError::class);
});

test('cases() liste les trois cas dans l\'ordre de déclaration', function (): void {
    expect(Rarity::cases())->toBe([Rarity::Common, Rarity::Rare, Rarity::Legendary]);
});
