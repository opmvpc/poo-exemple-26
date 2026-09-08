<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Levée par `Inventory::add()` quand l'objet ne rentre plus dans le sac.
 *
 * Corps vide : tout vient de `RuntimeException`. C'est le **nom** qui compte,
 * parce que c'est lui qui rend le `catch` précis.
 *
 * Chapitre 5.
 */
final class InventoryFullException extends \RuntimeException
{
}
