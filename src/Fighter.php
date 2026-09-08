<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le contrat de tout ce qui sait se battre dans le donjon.
 *
 * Une interface ne contient que des signatures : aucun code, aucune propriété.
 * `Hero` et `Monster` n'ont aucun parent commun — un héros n'est pas un monstre —
 * mais ils signent le même contrat. C'est ce qui permet d'écrire une fonction ou
 * une classe (`Battle`) qui ne connaît ni l'un ni l'autre.
 *
 * Chapitre 6.
 */
interface Fighter
{
    /** Les dégâts infligés par une attaque. */
    public function attack(): int;

    /** Encaisse des dégâts. */
    public function takeDamage(int $amount): void;

    /** Le combattant est-il encore debout ? */
    public function isAlive(): bool;
}
