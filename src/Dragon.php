<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le gros morceau : 30 PV, 8 dégâts.
 *
 * Même parent que `Goblin`, un seul `attack()` qui change : ajouter un monstre
 * ne modifie aucune boucle existante. C'est le polymorphisme du chapitre 5.
 */
final class Dragon extends Monster
{
    public function __construct()
    {
        parent::__construct('Dragon', 30);
    }

    public function attack(): int
    {
        return 8;
    }
}
