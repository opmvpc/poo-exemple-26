<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un objet ramassable du donjon.
 *
 * Classe **abstraite** : on ne ramasse jamais « un objet », on ramasse une arme
 * ou une potion. `describe()` est laissée sans corps, chaque sous-classe l'écrit.
 *
 * `public readonly` : le nom, le poids et la rareté se lisent partout
 * (`$item->name`) et ne s'écrivent qu'une fois, ici. Le poids est vérifié dans
 * le corps du constructeur : un `readonly` n'a pas de hook, il valide à la naissance.
 * `implements Stringable` : `echo $item` renvoie la même chose que `describe()`.
 *
 * Chapitres 5 (héritage) et 6 (rareté, Stringable).
 */
abstract class Item implements \Stringable
{
    public function __construct(
        public readonly string $name,
        public readonly float $weight,
        public readonly Rarity $rarity = Rarity::Common,
    ) {
        if ($weight < 0) {
            throw new \InvalidArgumentException("Un poids n'est pas négatif, $weight reçu.");
        }
    }

    /** Règle du jeu, arbitraire : la valeur marchande dépend du poids et de la rareté. */
    public function value(): float
    {
        return $this->weight * $this->rarity->multiplier();
    }

    /** Chaque objet se décrit à sa façon : le parent ne peut pas répondre. */
    abstract public function describe(): string;

    /** Contrat de Stringable : la même chose que describe(). */
    public function __toString(): string
    {
        return $this->describe();
    }
}
