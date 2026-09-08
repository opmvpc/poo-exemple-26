<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le donjon : une suite de salles.
 *
 * On le construit avec une liste de **noms**, pas avec une liste de salles :
 * impossible de lui passer une salle déjà peuplée. Les salles naissent ici et
 * n'existent pas ailleurs. C'est une **composition**, losange plein :
 * `Dungeon *-- "1..*" Room`.
 *
 * Chapitre 4.
 */
final class Dungeon
{
    /** @var Room[] Les salles, fabriquées ici. */
    private array $rooms = [];

    /** @param string[] $names */
    public function __construct(array $names)
    {
        foreach ($names as $name) {
            $this->rooms[] = new Room($name);
        }
    }

    /** La salle numéro $index (0 pour la première). */
    public function room(int $index): Room
    {
        return $this->rooms[$index];
    }

    /** @return Room[] */
    public function rooms(): array
    {
        return $this->rooms;
    }
}
