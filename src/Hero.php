<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le personnage joueur.
 *
 * État : un nom (qui ne change jamais), des points de vie, une force, un sac,
 * parfois une arme. Comportement : encaisser, se soigner, équiper, boire, frapper.
 * Pas de `setHp()`, pas de `hp()` non plus : ce qui ne change pas est `readonly`,
 * ce que la classe modifie elle-même est `private(set)`, et les règles du jeu
 * passent par des verbes. C'est ça, l'encapsulation.
 *
 * `use HasHealth;` (chapitre 6) apporte hp, maxHp, isFullHealth et les méthodes
 * de santé, partagées avec `Monster` sans qu'aucun des deux ne soit le parent
 * de l'autre. `implements Fighter` (chapitre 6) : le contrat commun avec les monstres.
 */
final class Hero implements Fighter
{
    use HasHealth;

    /** Le sac : créé ici, donc il vit et meurt avec le héros (composition). */
    public readonly Inventory $inventory;

    /** L'arme équipée, ou null si le héros se bat à mains nues (agrégation). */
    public private(set) ?Weapon $weapon = null;

    public function __construct(
        public readonly string $name,
        int $maxHp = 10,
        public readonly int $strength = 2,
    ) {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Un héros a un nom.');
        }

        if ($maxHp < 1) {
            throw new \InvalidArgumentException("maxHp doit valoir au moins 1, $maxHp reçu.");
        }

        $this->maxHp = $maxHp;
        $this->hp = $maxHp;
        $this->inventory = new Inventory();
    }

    /** Le héros porte une arme. Il n'en est pas une. */
    public function equip(Weapon $weapon): void
    {
        $this->weapon = $weapon;
    }

    /** Boit la potion : elle soigne, puis elle quitte le sac. */
    public function drink(Potion $potion): void
    {
        $this->heal($potion->healing);
        $this->inventory->remove($potion->name);
    }

    /** La force du héros, plus les dégâts de son arme s'il en porte une. */
    public function attack(): int
    {
        return $this->strength + ($this->weapon?->damage ?? 0);
    }

    /** Affichage : « Arthur (8/10 PV) ». */
    public function __toString(): string
    {
        return sprintf('%s (%d/%d PV)', $this->name, $this->hp, $this->maxHp);
    }
}
