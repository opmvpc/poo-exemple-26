<?php

declare(strict_types=1);

/**
 * Une mini-partie jouée toute seule, pour voir les objets vivre.
 * Lancez-la avec : php play.php   (ou : composer play)
 */

require __DIR__.'/vendor/autoload.php';

use Dungeon\Dice;
use Dungeon\Hero;
use Dungeon\Item;

/** Petit raccourci d'affichage. */
function say(string $line = ''): void
{
    echo $line, PHP_EOL;
}

$hero = new Hero('Arthur', 12, 3);
$d6 = Dice::d6();

say('=== LE DONJON ===');
say("Notre héros : {$hero}");
say("Son sac peut porter {$hero->inventory()->maxWeight()} kg.");
say();

// Le butin possible dans les salles visitées.
$loot = [
    new Item('Épée courte', 2.0),
    new Item('Potion de soin', 0.5),
    new Item('Bouclier de bois', 4.0),
    new Item('Enclume du forgeron', 50.0),
];

foreach ($loot as $number => $item) {
    say('--- Salle '.($number + 1).' ---');
    say("Au sol : {$item}");

    if ($hero->inventory()->add($item)) {
        say("Ramassé. Sac : {$hero->inventory()->count()} objet(s), {$hero->inventory()->totalWeight()} kg.");
    } else {
        say('Trop lourd pour le sac : laissé sur place.');
    }

    // Un gobelin surgit une fois sur deux (dé à 6 faces).
    $roll = $d6->roll();
    if ($roll >= 4) {
        $damage = $d6->roll();
        $hero->takeDamage($damage);
        say("Un gobelin surgit (dé {$roll}) et frappe pour {$damage} dégâts → {$hero}");
    } else {
        say("Salle calme (dé {$roll}).");
    }

    // Si la potion est dans le sac et que ça va mal, on la boit.
    if ($hero->hp() < $hero->maxHp() / 2 && $hero->inventory()->has('Potion de soin')) {
        $hero->heal(5);
        $hero->inventory()->remove('Potion de soin');
        say("Arthur boit la potion (+5 PV) → {$hero}");
    }

    say();
}

say('=== FIN DE L\'EXPLORATION ===');
say($hero->isAlive() ? "Arthur ressort vivant : {$hero}" : 'Arthur est tombé au fond du donjon…');
say('Contenu du sac :');
foreach ($hero->inventory()->items() as $item) {
    say("  - {$item}");
}
say(sprintf('Total : %d objet(s) pour %.1f kg.', $hero->inventory()->count(), $hero->inventory()->totalWeight()));
