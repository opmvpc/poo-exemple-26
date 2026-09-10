<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Une arme : un `Item` qui inflige des dégâts.
 *
 * « Une épée **est un** objet ramassable » : la phrase tient, donc `extends`.
 * Le constructeur appelle `parent::__construct()`, sans quoi le nom et le poids
 * resteraient vides. `damage` est `public readonly` : fixé à la création, lu partout.
 *
 * Chapitre 5.
 */
final class Weapon extends Item
{
    public function __construct(
        string $name,
        float $weight,
        public readonly int $damage,
        Rarity $rarity = Rarity::Common,
    ) {
        parent::__construct($name, $weight, $rarity);
    }

    /** « Épée courte : arme (2 kg, 5 dégâts) ». */
    public function describe(): string
    {
        return sprintf('%s : arme (%s kg, %d dégâts)', $this->name, $this->weight, $this->damage);
    }
}
