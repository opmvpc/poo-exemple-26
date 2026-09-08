# Le Donjon — exemple complet (poo-exemple-26)

Ceci est **l'exemple de référence** du bloc POO du cours 5XCOS. On le lit ensemble
en séance 1, fichier par fichier. Tout le code des chapitres 1 à 4 vient d'ici.

Il couvre les **niveaux 1 et 2** des katas : `Dice`, `Hero`, `Item`, `Inventory`.
Pas d'héritage, pas d'interface, pas de trait : ça arrive aux niveaux suivants,
dans le repo `poo-katas-26`.

## Démarrer

```bash
composer install   # installe Pest dans vendor/ et génère l'autoload
composer test      # lance les tests : tout doit être vert
php play.php       # joue une mini-partie dans le terminal
```

PHP 8.3 minimum. Pas de base de données, pas de serveur web : que du terminal.

## Ce qu'il y a dans le dossier, et pourquoi

| Fichier | À quoi il sert |
|---|---|
| `composer.json` | La carte d'identité du projet : la version de PHP exigée, la seule dépendance (`pestphp/pest`), l'autoload PSR-4 (`Dungeon\` → `src/`) et les raccourcis `composer test` / `composer play`. |
| `composer.lock` / `vendor/` | Générés par `composer install`. `vendor/` n'est **jamais** committé (voir `.gitignore`) : on le régénère. |
| `phpunit.xml` | La configuration du lanceur de tests : où sont les tests (`tests/`), quel fichier charger d'abord (`vendor/autoload.php`). Pest s'appuie dessus. |
| `.gitignore` | Ce que Git doit ignorer : `vendor/`, les caches de tests, les fichiers d'éditeur. |
| `.github/workflows/tests.yml` | La CI : à chaque `push`, GitHub installe PHP 8.4, fait `composer install` et `composer test`. C'est la coche verte (ou rouge) de l'onglet **Actions**. |
| `src/Dice.php` | La classe `Dice`. Le plus petit objet du jeu, et le prétexte parfait pour opposer **méthode d'instance** (`roll()`, qui a besoin d'un dé) et **méthode statique** (`Dice::d6()`, une fabrique qui n'a besoin de rien). |
| `src/Hero.php` | La classe `Hero` : l'objet central. On y voit l'**état** (nom, PV, force), le **comportement** (`takeDamage`, `heal`, `isAlive`), la **promotion de constructeur**, `readonly` sur le nom, `__toString()`, et l'**encapsulation** : pas de `setHp()`, on expose les verbes du jeu. |
| `src/Item.php` | La classe `Item` : un objet ramassable (nom + poids). Deux propriétés privées et deux getters. Volontairement minuscule : au niveau 3 elle deviendra abstraite et servira de parent à `Weapon` et `Potion`. |
| `src/Inventory.php` | La classe `Inventory` : le sac. Une classe qui **contient une liste d'autres objets** (`/** @var Item[] */`). C'est le premier exemple de relation entre objets : `add`, `has`, `remove`, `count`, `totalWeight`. Un ajout trop lourd retourne `false` (au niveau 3, ce sera une exception). |
| `play.php` | Le point d'entrée « à la main » : il fait `require vendor/autoload.php`, crée un héros, explore 4 salles, ramasse ce qui rentre dans le sac, encaisse des coups de gobelin tirés au dé et boit une potion si ça tourne mal. À lire **après** les classes : il montre à quoi elles servent une fois assemblées. |
| `tests/Pest.php` | Le fichier de configuration de Pest : il dit que tous les fichiers de `tests/` sont des tests. On y touche rarement. |
| `tests/DiceTest.php` | Documente `Dice` : les bornes du lancer (vérifiées 100 fois), les fabriques statiques, le refus d'un dé à 1 face. |
| `tests/HeroTest.php` | Documente `Hero` : PV au départ, plancher à 0, plafond à `maxHp`, `__toString()`, et le test qui prouve que `name` est bien `readonly` (une écriture lève `Error`). |
| `tests/ItemTest.php` | Documente `Item` : nom, poids, affichage, refus d'un poids négatif. |
| `tests/InventoryTest.php` | Documente `Inventory` : sac vide au départ, ajout, poids total, refus d'un objet trop lourd, retrait par nom. |

## Ce que les tests racontent

Les tests ne sont pas là pour faire joli : **ils sont la spécification lisible** de
chaque classe. Quand vous vous demandez « qu'est-ce que `heal()` fait exactement si
je dépasse ? », vous ouvrez `tests/HeroTest.php` et vous lisez :

```php
test('heal ne dépasse jamais le maximum', function (): void {
    $hero = new Hero('Arthur');
    $hero->takeDamage(4);

    $hero->heal(100);

    expect($hero->hp())->toBe(10);
});
```

C'est ce réflexe qu'on veut installer : **le test répond avant la documentation**.

## Le style de code, en trois règles

1. `declare(strict_types=1)` en haut de chaque fichier : PHP refuse de convertir
   `"3"` en `3` en douce. Une erreur de type se voit tout de suite.
2. **Des types partout** : sur les propriétés, les paramètres, les retours.
3. **Un fichier = une classe**, et le chemin suit le namespace (`Dungeon\Hero`
   → `src/Hero.php`). C'est la règle PSR-4, celle que suit Laravel.

## Et ensuite ?

- Les exercices : [`poo-katas-26`](https://github.com/opmvpc/poo-katas-26), niveaux 1 à 4 (+ bonus).
- Ce repo s'arrête volontairement au niveau 2. Héritage, exceptions, interfaces,
  traits et enums arrivent aux chapitres 5 et 6.
