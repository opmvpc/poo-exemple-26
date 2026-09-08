<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Les points de vie, extraits de `Hero` et de `Monster`.
 *
 * Un trait n'est **pas** un parent : c'est du code que PHP recopie dans chaque
 * classe qui écrit `use HasHealth;`. Il ne se dessine pas en UML, et
 * `class_uses()` ne remonte pas aux classes parentes.
 *
 * Chapitre 6.
 */
trait HasHealth
{
    /** Les points de vie courants, toujours entre 0 et $maxHp. */
    protected int $hp = 0;

    /** Le maximum de points de vie. */
    protected int $maxHp = 0;

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

    /** Encore debout ? */
    public function isAlive(): bool
    {
        return $this->hp > 0;
    }
}
