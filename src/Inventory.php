<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le sac du héros : une liste d'`Item` et un poids maximum.
 *
 * C'est une **composition** : l'inventaire est créé dans le constructeur du
 * héros et n'a aucun sens sans lui. Si le héros disparaît, son sac aussi.
 */
final class Inventory
{
    /** @var Item[] La liste des objets transportés. */
    private array $items = [];

    public function __construct(
        private readonly float $maxWeight = 20.0,
    ) {
    }

    /** Le poids maximum transportable. */
    public function maxWeight(): float
    {
        return $this->maxWeight;
    }

    /**
     * Ajoute un objet si le sac peut encore le porter.
     * Retourne `false` (et n'ajoute rien) si le poids dépasse la limite.
     */
    public function add(Item $item): bool
    {
        if ($this->totalWeight() + $item->weight() > $this->maxWeight) {
            return false;
        }

        $this->items[] = $item;

        return true;
    }

    /** Y a-t-il un objet portant ce nom dans le sac ? */
    public function has(string $name): bool
    {
        foreach ($this->items as $item) {
            if ($item->name() === $name) {
                return true;
            }
        }

        return false;
    }

    /** Retire le premier objet portant ce nom (ne fait rien s'il n'y est pas). */
    public function remove(string $name): void
    {
        foreach ($this->items as $index => $item) {
            if ($item->name() === $name) {
                unset($this->items[$index]);
                // On renumérote les clés pour garder un vrai tableau 0,1,2…
                $this->items = array_values($this->items);

                return;
            }
        }
    }

    /** Le nombre d'objets dans le sac. */
    public function count(): int
    {
        return count($this->items);
    }

    /** La somme des poids, en kilos. */
    public function totalWeight(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->weight();
        }

        return $total;
    }

    /**
     * Copie de la liste, pour l'affichage.
     *
     * @return Item[]
     */
    public function items(): array
    {
        return $this->items;
    }
}
