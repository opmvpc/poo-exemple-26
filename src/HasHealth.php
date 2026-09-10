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
 * `public private(set)` : tout le monde lit `$hero->hp`, seule la classe écrit.
 * Le `set` hook porte la règle « jamais sous 0, jamais au-dessus de maxHp » :
 * `takeDamage()` et `heal()` n'ont plus qu'à soustraire et additionner.
 *
 * Chapitre 6.
 */
trait HasHealth
{
    /** Le maximum de points de vie : lecture publique, écriture réservée à la classe. */
    public private(set) int $maxHp = 0;

    /** Les points de vie courants. Le hook les borne à chaque écriture. */
    public private(set) int $hp = 0 {
        set => max(0, min($this->maxHp, $value));
    }

    /** Propriété virtuelle : calculée à la lecture, rien n'est stocké. */
    public bool $isFullHealth {
        get => $this->hp === $this->maxHp;
    }

    /** Encaisse des dégâts. Le hook empêche de passer sous 0. */
    public function takeDamage(int $amount): void
    {
        $this->hp -= $amount;
    }

    /** Soigne. Le hook empêche de dépasser maxHp. */
    public function heal(int $amount): void
    {
        $this->hp += $amount;
    }

    /** Encore debout ? */
    public function isAlive(): bool
    {
        return $this->hp > 0;
    }
}
