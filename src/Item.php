<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un objet ramassable du donjon.
 *
 * Classe **abstraite** : on ne ramasse jamais « un objet », on ramasse une arme
 * ou une potion. `describe()` est laissée sans corps, chaque sous-classe l'écrit.
 *
 * `protected` : `Weapon` et `Potion` lisent le nom et le poids, l'extérieur non.
 * `implements Stringable` : `echo $item` renvoie la même chose que `describe()`.
 *
 * Chapitres 5 (héritage) et 6 (rareté, Stringable).
 */
abstract class Item implements \Stringable
{
    public function __construct(
        protected readonly string $name,
        protected readonly float $weight,
        protected readonly Rarity $rarity = Rarity::Common,
    ) {
    }

    /** Le nom affiché de l'objet. */
    public function name(): string
    {
        return $this->name;
    }

    /** Le poids en kilos, utilisé par l'inventaire. */
    public function weight(): float
    {
        return $this->weight;
    }

    /** La rareté, `Rarity::Common` par défaut. */
    public function rarity(): Rarity
    {
        return $this->rarity;
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
