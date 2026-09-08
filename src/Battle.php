<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un duel tour par tour entre deux `Fighter`, et le vainqueur au bout.
 *
 * `Battle` ne connaît ni `Hero` ni `Monster` : il ne connaît que le contrat.
 * Le dé est **reçu** en paramètre au lieu d'être fabriqué ici, ce qui permet de
 * lui donner un dé aux valeurs connues dans un test. C'est la seule fois du
 * cours où l'on montre ce procédé.
 *
 * Chapitre 6, bonus.
 */
final class Battle
{
    public function __construct(
        private readonly Fighter $a,
        private readonly Fighter $b,
        private readonly Dice $dice,
    ) {
    }

    /**
     * Déroule le combat : $a frappe en premier, les dégâts valent `attack()`
     * plus un lancer de dé, et on s'arrête dès que l'un des deux tombe.
     */
    public function fight(): Fighter
    {
        $attacker = $this->a;
        $target = $this->b;

        while ($this->a->isAlive() && $this->b->isAlive()) {
            $target->takeDamage($attacker->attack() + $this->dice->roll());

            // On échange les rôles pour le tour suivant.
            [$attacker, $target] = [$target, $attacker];
        }

        return $this->a->isAlive() ? $this->a : $this->b;
    }
}
