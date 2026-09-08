<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un dé à N faces.
 *
 * Sert d'exemple pour la différence entre méthode d'instance (`roll()`,
 * qui a besoin d'un dé concret) et méthode statique (`d6()`, une fabrique
 * qui ne dépend d'aucun dé existant).
 *
 * Pas de `final` ici : au dernier kata, les tests fabriquent un `FixedDice`
 * qui hérite de `Dice` pour rendre un combat reproductible.
 */
class Dice
{
    // Promotion de constructeur : la propriété est déclarée dans la signature.
    // `readonly` : le nombre de faces d'un dé ne change jamais.
    public function __construct(
        public readonly int $sides,
    ) {}

    /** Fabrique statique : `Dice::d6()` se lit mieux que `new Dice(6)`. */
    public static function d6(): self
    {
        return new self(6);
    }

    /** Même idée pour le dé à 20 faces du jeu de rôle. */
    public static function d20(): self
    {
        return new self(20);
    }

    /** Lance le dé : un entier entre 1 et `sides` inclus. */
    public function roll(): int
    {
        return random_int(1, $this->sides);
    }
}
