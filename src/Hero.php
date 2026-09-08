<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le personnage joueur.
 *
 * État : un nom (qui ne change jamais), des points de vie, une force.
 * Comportement : encaisser des coups, se soigner, porter un sac.
 * Pas de `setHp()` : on expose des verbes du jeu (`takeDamage`, `heal`),
 * pas des accès bruts aux propriétés. C'est ça, l'encapsulation.
 */
final class Hero
{
    /** Les points de vie courants. Toujours entre 0 et maxHp. */
    private int $hp;

    /** Le maximum de points de vie. Lu par maxHp(), jamais écrit de l'extérieur. */
    private int $maxHp;

    /** Le sac : créé ici, donc il vit et meurt avec le héros (composition). */
    private Inventory $inventory;

    public function __construct(
        public readonly string $name,
        int $maxHp = 10,
        public readonly int $strength = 2,
    ) {
        $this->maxHp = $maxHp;
        $this->hp = $maxHp;
        $this->inventory = new Inventory();
    }

    /** Les PV courants. */
    public function hp(): int
    {
        return $this->hp;
    }

    /** Le maximum de PV. */
    public function maxHp(): int
    {
        return $this->maxHp;
    }

    /** Le sac du héros. */
    public function inventory(): Inventory
    {
        return $this->inventory;
    }

    /** Encaisse des dégâts, sans jamais descendre sous 0 PV. */
    public function takeDamage(int $amount): void
    {
        $this->hp = max(0, $this->hp - $amount);
    }

    /** Soigne, sans jamais dépasser maxHp. */
    public function heal(int $amount): void
    {
        $this->hp = min($this->maxHp, $this->hp + $amount);
    }

    /** Le héros est-il encore debout ? */
    public function isAlive(): bool
    {
        return $this->hp > 0;
    }

    /** Une attaque simple : la force du héros. */
    public function attack(): int
    {
        return $this->strength;
    }

    /** Affichage : "Arthur (8/10 PV)". */
    public function __toString(): string
    {
        return sprintf('%s (%d/%d PV)', $this->name, $this->hp, $this->maxHp);
    }
}
