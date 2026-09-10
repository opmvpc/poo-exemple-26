<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le sac du héros : une liste d'`Item` et un poids maximum.
 *
 * C'est une **composition** : l'inventaire est créé dans le constructeur du
 * héros et n'a aucun sens sans lui. Si le héros disparaît, son sac aussi.
 *
 * `maxWeight` est `public readonly` : on le lit (`$bag->maxWeight`), on ne le
 * change pas. La liste, elle, reste `private` : on passe par `add()` et `remove()`.
 *
 * `Countable` et `IteratorAggregate` (chapitre 6) branchent l'objet sur la
 * syntaxe du langage : `count($sac)` et `foreach ($sac as $item)`.
 *
 * @implements \IteratorAggregate<int, Item>
 */
final class Inventory implements \Countable, \IteratorAggregate
{
    /** @var Item[] La liste des objets transportés. */
    private array $items = [];

    public function __construct(
        public readonly float $maxWeight = 20.0,
    ) {
    }

    /**
     * Ajoute un objet, ou refuse le sac en levant une exception.
     *
     * La signature a changé au chapitre 5 : elle renvoyait `bool` aux chapitres
     * 3 et 4, elle renvoie `void` et lève depuis. Un `false` que personne ne
     * teste disparaît en silence ; une exception, non.
     *
     * @throws InventoryFullException si le poids dépasse la limite du sac
     */
    public function add(Item $item): void
    {
        if ($this->totalWeight() + $item->weight > $this->maxWeight) {
            throw new InventoryFullException(sprintf(
                '"%s" ne rentre pas : le sac ne porte que %s kg.',
                $item->name,
                $this->maxWeight,
            ));
        }

        $this->items[] = $item;
    }

    /** Y a-t-il un objet portant ce nom dans le sac ? */
    public function has(string $name): bool
    {
        foreach ($this->items as $item) {
            if ($item->name === $name) {
                return true;
            }
        }

        return false;
    }

    /** Retire le premier objet portant ce nom (ne fait rien s'il n'y est pas). */
    public function remove(string $name): void
    {
        foreach ($this->items as $index => $item) {
            if ($item->name === $name) {
                unset($this->items[$index]);
                // On renumérote les clés pour garder un vrai tableau 0,1,2…
                $this->items = array_values($this->items);

                return;
            }
        }
    }

    /** Contrat de Countable : ce que `count($inventory)` doit renvoyer. */
    public function count(): int
    {
        return count($this->items);
    }

    /** La somme des poids, en kilos. */
    public function totalWeight(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->weight;
        }

        return $total;
    }

    /**
     * Contrat de IteratorAggregate : ce que `foreach` doit parcourir.
     *
     * @return \Traversable<int, Item>
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items);
    }
}
