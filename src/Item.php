<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un objet ramassable du donjon : une épée, une potion, une corde…
 *
 * Deux données seulement : un nom et un poids en kilos. Les propriétés sont
 * `private` : on les lit par des getters, personne ne peut changer le poids
 * d'une épée après coup.
 */
class Item
{
    public function __construct(
        private readonly string $name,
        private readonly float $weight,
    ) {
        if ($weight < 0) {
            throw new \InvalidArgumentException('Un objet ne peut pas peser moins de 0 kg.');
        }
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

    /** Représentation lisible : "Épée courte (2 kg)". */
    public function __toString(): string
    {
        return sprintf('%s (%s kg)', $this->name, $this->weight);
    }
}
