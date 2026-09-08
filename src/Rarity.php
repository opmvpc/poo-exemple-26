<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * La rareté d'un objet : une liste fermée de trois valeurs, pas une chaîne libre.
 *
 * Enum « backed » (`: string`) : chaque cas porte une valeur texte, celle qu'on
 * enregistrerait en base de données. `Rarity::from('rare')` retrouve le cas,
 * `Rarity::tryFrom('epic')` renvoie `null` au lieu de lever.
 *
 * Chapitre 6.
 */
enum Rarity: string
{
    case Common = 'common';
    case Rare = 'rare';
    case Legendary = 'legendary';

    /** Combien vaut un objet de cette rareté, par rapport à un objet banal. */
    public function multiplier(): float
    {
        return match ($this) {
            Rarity::Common => 1.0,
            Rarity::Rare => 1.5,
            Rarity::Legendary => 3.0,
        };
    }

    /** Le libellé affiché au joueur. */
    public function label(): string
    {
        return match ($this) {
            Rarity::Common => 'Commun',
            Rarity::Rare => 'Rare',
            Rarity::Legendary => 'Légendaire',
        };
    }
}
