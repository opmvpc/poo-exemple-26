<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Une potion : un `Item` qui rend des points de vie.
 *
 * Même modèle que `Weapon`, avec `healing` à la place de `damage`.
 *
 * Chapitre 5.
 */
final class Potion extends Item
{
    public function __construct(
        string $name,
        float $weight,
        public readonly int $healing,
        Rarity $rarity = Rarity::Common,
    ) {
        parent::__construct($name, $weight, $rarity);
    }

    /** « Potion de soin : potion (0.5 kg, +5 PV) ». */
    public function describe(): string
    {
        return sprintf('%s : potion (%s kg, +%d PV)', $this->name, $this->weight, $this->healing);
    }
}
