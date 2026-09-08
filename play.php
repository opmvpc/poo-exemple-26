<?php

declare(strict_types=1);

/**
 * Une mini-partie jouée toute seule, pour voir les objets vivre.
 * Lancez-la avec : php play.php   (ou : composer play)
 */

require __DIR__.'/vendor/autoload.php';

use Dungeon\Battle;
use Dungeon\Dice;
use Dungeon\Dragon;
use Dungeon\Dungeon;
use Dungeon\Goblin;
use Dungeon\Hero;
use Dungeon\InventoryFullException;
use Dungeon\Item;
use Dungeon\Monster;
use Dungeon\Potion;
use Dungeon\Rarity;
use Dungeon\Weapon;

/** Petit raccourci d'affichage. */
function say(string $line = ''): void
{
    echo $line, PHP_EOL;
}

$hero = new Hero('Arthur', 24, 3);
$d6 = Dice::d6();

// Le donjon fabrique ses salles : composition.
$dungeon = new Dungeon(['Entrée', 'Salle des gardes', 'Bibliothèque', 'Forge', 'Trésor']);

// Le butin, fabriqué ici puis posé au sol : agrégation.
/** @var Item[] $loot */
$loot = [
    new Weapon('Épée courte', 2.0, 5),
    new Potion('Potion de soin', 0.5, 8),
    new Weapon('Bouclier de bois', 4.0, 1),
    new Weapon('Enclume du forgeron', 50.0, 2),
    new Weapon('Lame du dragon', 3.0, 12, Rarity::Legendary),
];

foreach ($dungeon->rooms() as $index => $room) {
    $room->drop($loot[$index]);
}

// Un monstre par salle, ou rien du tout.
/** @var array<int, ?Monster> $monsters */
$monsters = [null, new Goblin(), null, new Goblin(), new Dragon()];

say('=== LE DONJON ===');
say("Notre héros : {$hero}");
say("Son sac peut porter {$hero->inventory()->maxWeight()} kg.");
say();

foreach ($dungeon->rooms() as $index => $room) {
    say('--- '.$room->describe().' ---');

    // On ramasse ce qui traîne, si le sac le supporte.
    $item = $room->take();
    if ($item !== null) {
        try {
            $hero->inventory()->add($item);
            say("Ramassé : {$item} (valeur {$item->value()}, {$item->rarity()->label()})");
        } catch (InventoryFullException $e) {
            $room->drop($item);
            say('Laissé sur place. '.$e->getMessage());
        }
    }

    // La meilleure arme du sac part au poing.
    foreach ($hero->inventory() as $carried) {
        if ($carried instanceof Weapon
            && $carried->damage() > ($hero->weapon()?->damage() ?? 0)) {
            $hero->equip($carried);
            say("Arthur empoigne : {$carried->name()}.");
        }
    }

    $monster = $monsters[$index];
    if ($monster === null) {
        say('Salle calme.');
        say();

        continue;
    }

    say("Un {$monster->name} surgit ! {$monster}");
    $winner = (new Battle($hero, $monster, $d6))->fight();
    say($winner === $hero
        ? "Arthur l'emporte → {$hero}"
        : "Arthur tombe face au {$monster->name}…");

    // Une gorgée de potion si ça tourne mal.
    if ($hero->isAlive() && $hero->hp() < $hero->maxHp() / 2 && $hero->inventory()->has('Potion de soin')) {
        foreach ($hero->inventory() as $carried) {
            if ($carried instanceof Potion) {
                $hero->drink($carried);
                say("Arthur boit la potion (+{$carried->healing()} PV) → {$hero}");

                break;
            }
        }
    }

    if (! $hero->isAlive()) {
        break;
    }

    say();
}

say();
say('=== FIN DE L\'EXPLORATION ===');
say($hero->isAlive() ? "Arthur ressort vivant : {$hero}" : 'Arthur est tombé au fond du donjon…');
say('Contenu du sac :');
foreach ($hero->inventory() as $carried) {
    say("  - {$carried}");
}
say(sprintf(
    'Total : %d objet(s) pour %.1f kg, valeur %.2f.',
    count($hero->inventory()),
    $hero->inventory()->totalWeight(),
    array_sum(array_map(static fn (Item $i): float => $i->value(), iterator_to_array($hero->inventory()))),
));
