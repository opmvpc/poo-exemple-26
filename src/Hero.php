<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le personnage joueur.
 *
 * État : un nom (qui ne change jamais), des points de vie, une force, un sac,
 * parfois une arme. Comportement : encaisser, se soigner, équiper, boire, frapper.
 * Pas de `setHp()` : on expose les verbes du jeu, pas des accès bruts aux
 * propriétés. C'est ça, l'encapsulation.
 *
 * `use HasHealth;` (chapitre 6) apporte hp, maxHp et les cinq méthodes de santé,
 * partagées avec `Monster` sans qu'aucun des deux ne soit le parent de l'autre.
 * `implements Fighter` (chapitre 6) : le contrat commun avec les monstres.
 */
final class Hero implements Fighter
{
    use HasHealth;

    /** Le sac : créé ici, donc il vit et meurt avec le héros (composition). */
    private Inventory $inventory;

    /** L'arme équipée, ou null si le héros se bat à mains nues (agrégation). */
    private ?Weapon $weapon = null;

    public function __construct(
        public readonly string $name,
        int $maxHp = 10,
        public readonly int $strength = 2,
    ) {
        $this->maxHp = $maxHp;
        $this->hp = $maxHp;
        $this->inventory = new Inventory();
    }

    /** Le sac du héros. */
    public function inventory(): Inventory
    {
        return $this->inventory;
    }

    /** Le héros porte une arme. Il n'en est pas une. */
    public function equip(Weapon $weapon): void
    {
        $this->weapon = $weapon;
    }

    /** L'arme équipée, ou null. */
    public function weapon(): ?Weapon
    {
        return $this->weapon;
    }

    /** Boit la potion : elle soigne, puis elle quitte le sac. */
    public function drink(Potion $potion): void
    {
        $this->heal($potion->healing());
        $this->inventory->remove($potion->name());
    }

    /** La force du héros, plus les dégâts de son arme s'il en porte une. */
    public function attack(): int
    {
        return $this->strength + ($this->weapon?->damage() ?? 0);
    }

    /** Affichage : « Arthur (8/10 PV) ». */
    public function __toString(): string
    {
        return sprintf('%s (%d/%d PV)', $this->name, $this->hp, $this->maxHp);
    }
}
