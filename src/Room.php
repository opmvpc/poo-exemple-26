<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Une salle du donjon, avec au plus un objet posé au sol.
 *
 * Aucun `new Item` ici : la salle **reçoit** un objet fabriqué ailleurs, elle le
 * garde un moment, elle le rend. C'est une **agrégation**, losange vide :
 * `Room o-- "0..1" Item`. L'épée existait avant la salle et lui survit.
 *
 * Chapitre 4.
 */
final class Room
{
    /** L'objet posé au sol : reçu de l'extérieur, et parfois absent. */
    private ?Item $loot = null;

    public function __construct(
        public readonly string $name,
    ) {
    }

    /** Pose un objet au sol. */
    public function drop(Item $item): void
    {
        $this->loot = $item;
    }

    /** L'objet au sol, ou null. */
    public function loot(): ?Item
    {
        return $this->loot;
    }

    /** Retire l'objet du sol et le rend. */
    public function take(): ?Item
    {
        $item = $this->loot;
        $this->loot = null;

        return $item;
    }

    /** « Salle des gardes : Épée courte ». Le `?->` évite le test sur null. */
    public function describe(): string
    {
        return $this->name.' : '.($this->loot?->name() ?? 'rien au sol');
    }
}
