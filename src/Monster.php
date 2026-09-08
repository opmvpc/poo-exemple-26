<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Un monstre du donjon.
 *
 * Classe **abstraite** : on ne croise jamais « un monstre », on croise un
 * gobelin ou un dragon. `attack()` est abstraite, chaque monstre frappe à sa
 * façon — c'est ce qui remplace la cascade de `if` sur un type en chaîne.
 *
 * `implements Fighter` alors qu'elle n'écrit pas `attack()` : ce sont ses
 * classes filles qui remplissent le contrat pour elle.
 *
 * Chapitres 5 (héritage, abstrait) et 6 (Fighter, HasHealth).
 */
abstract class Monster implements Fighter
{
    use HasHealth;

    public function __construct(
        public readonly string $name,
        int $maxHp,
    ) {
        $this->maxHp = $maxHp;
        $this->hp = $maxHp;
    }

    /** Chaque monstre frappe à sa façon. Le parent ne peut pas décider. */
    abstract public function attack(): int;

    /** Affichage : « Gobelin (5/5 PV) ». */
    public function __toString(): string
    {
        return sprintf('%s (%d/%d PV)', $this->name, $this->hp, $this->maxHp);
    }
}
