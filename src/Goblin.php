<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le petit monstre de base : 5 PV, 2 dégâts.
 *
 * L'attaque est une valeur fixe et non un lancer de dé : un monstre qui tire au
 * hasard est amusant, mais on ne peut plus prévoir le résultat d'un test.
 *
 * Chapitre 5.
 */
final class Goblin extends Monster
{
    public function __construct()
    {
        parent::__construct('Gobelin', 5);
    }

    public function attack(): int
    {
        return 2;
    }
}
