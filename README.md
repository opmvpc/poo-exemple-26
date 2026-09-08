# Le Donjon — exemple complet (poo-exemple-26)

Ceci est **l'exemple de référence** du bloc POO du cours 5XCOS. On le lit ensemble
en séance 1, fichier par fichier, puis on y revient à chaque chapitre. Tout le code
des chapitres 1 à 6 vient d'ici.

Il contient le Donjon **entier et corrigé** : les objets et les classes du chapitre 1,
les relations du chapitre 4, l'héritage et les exceptions du chapitre 5, les
interfaces, les traits et les enums du chapitre 6. Les katas, eux, sont dans
[`poo-katas-26`](https://github.com/opmvpc/poo-katas-26) : ici, tout est déjà écrit.

## Démarrer

```bash
composer install   # installe Pest dans vendor/ et génère l'autoload
composer test      # lance les tests : tout doit être vert
php play.php       # joue une mini-partie dans le terminal
```

PHP 8.3 minimum. Pas de base de données, pas de serveur web : que du terminal.

## Lecture conseillée, chapitre par chapitre

| Après le chapitre… | Lisez ces fichiers | Ce que vous y retrouvez |
|---|---|---|
| **1 — Objets et classes** | `src/Hero.php`, `src/Dice.php`, `tests/HeroTest.php`, `tests/DiceTest.php` | propriétés typées, promotion de constructeur, `readonly`, `__toString()`, encapsulation (pas de `setHp()`), fabrique statique `Dice::d6()` |
| **2 — L'atelier** | `composer.json`, `phpunit.xml`, `.github/workflows/tests.yml`, `tests/Pest.php` | l'autoload PSR-4 (`Dungeon\` → `src/`), un fichier par classe, `composer test`, la CI |
| **3 — Modéliser** | les en-têtes de `src/Room.php`, `src/Dungeon.php`, `src/Inventory.php` | chaque commentaire dit quel trait du diagramme la classe code : losange plein, losange vide |
| **4 — Relations entre objets** | `src/Inventory.php`, `src/Room.php`, `src/Dungeon.php`, `src/Item.php` | composition (`new` dans le constructeur), agrégation (objet reçu), `?Item`, `?->`, `/** @var Item[] */` |
| **5 — Héritage et polymorphisme** | `src/Item.php`, `src/Weapon.php`, `src/Potion.php`, `src/Monster.php`, `src/Goblin.php`, `src/Dragon.php`, `src/InventoryFullException.php` | `abstract`, `extends`, `protected`, `parent::__construct()`, `attack()` polymorphe, exception personnalisée |
| **6 — Contrats** | `src/Fighter.php`, `src/HasHealth.php`, `src/Rarity.php`, `src/Battle.php`, `src/Inventory.php` | interface, trait, enum, `Countable`, `IteratorAggregate`, `Stringable` |
| **En entier** | `play.php` | les quinze classes assemblées en une partie qui se joue toute seule |

## Ce qu'il y a dans le dossier, et pourquoi

### Le code du jeu (`src/`)

| Fichier | À quoi il sert | Chapitre |
|---|---|---|
| `src/Dice.php` | La classe `Dice`. Le plus petit objet du jeu, et le prétexte parfait pour opposer **méthode d'instance** (`roll()`, qui a besoin d'un dé) et **méthode statique** (`Dice::d6()`, une fabrique qui n'a besoin de rien). Pas de `final` : `Battle` est testé avec un dé truqué qui en hérite. | 1 |
| `src/Hero.php` | La classe `Hero` : l'objet central. **État** (nom, PV, force), **comportement** (`takeDamage`, `heal`, `equip`, `drink`, `attack`), promotion de constructeur, `readonly` sur le nom, `__toString()`, et l'**encapsulation** : pas de `setHp()`. Elle `use HasHealth` et `implements Fighter` depuis le chapitre 6. | 1, 4, 5, 6 |
| `src/Item.php` | `Item` est **abstraite** : on ne ramasse jamais « un objet », on ramasse une arme ou une potion. Nom, poids, rareté, `value()`, `describe()` abstraite, `implements Stringable`. | 4, 5, 6 |
| `src/Weapon.php` | `Weapon extends Item` : « une épée **est un** objet ramassable ». Ajoute `damage()` et écrit son `describe()`. | 5 |
| `src/Potion.php` | `Potion extends Item` : même modèle, avec `healing()`. Deux sœurs, un seul parent. | 5 |
| `src/Rarity.php` | L'enum `Rarity` : trois cas, pas un de plus, avec `multiplier()` et `label()` en `match`. Remplace une chaîne de caractères non contrôlée. | 6 |
| `src/Inventory.php` | Le sac. Une classe qui **contient une liste d'autres objets** (`/** @var Item[] */`) : `add`, `has`, `remove`, `count`, `totalWeight`. `add()` lève une `InventoryFullException` depuis le chapitre 5 (elle renvoyait `bool` avant). `Countable` et `IteratorAggregate` la branchent sur `count()` et `foreach`. | 4, 5, 6 |
| `src/InventoryFullException.php` | Une exception personnalisée, corps vide : c'est le **nom** qui rend le `catch` précis. De l'héritage, rien d'autre. | 5 |
| `src/Room.php` | Une salle, avec au plus un objet au sol. Aucun `new Item` dedans : **agrégation**, losange vide. Montre `?Item` et l'opérateur `?->`. | 4 |
| `src/Dungeon.php` | Le donjon, construit avec une liste de **noms** : il fabrique ses salles lui-même. **Composition**, losange plein. | 4 |
| `src/Monster.php` | `Monster` est **abstraite** et laisse `attack()` à ses filles. Elle `use HasHealth` et `implements Fighter` sans écrire `attack()` : ce sont ses filles qui remplissent le contrat. | 5, 6 |
| `src/Goblin.php` | 5 PV, attaque 2. Valeur fixe et non un lancer de dé : un test doit rester prévisible. | 5 |
| `src/Dragon.php` | 30 PV, attaque 8. Ajouter un monstre ne modifie aucune boucle existante : c'est ça, le polymorphisme. | 5 |
| `src/Fighter.php` | L'interface `Fighter` : trois signatures, aucun code. `Hero` et `Monster` n'ont pas de parent commun et signent pourtant le même contrat. | 6 |
| `src/HasHealth.php` | Le trait `HasHealth` : les PV et leurs cinq méthodes, collés dans `Hero` **et** dans `Monster`. Un trait n'est pas un parent et ne se dessine pas en UML. | 6 |
| `src/Battle.php` | Le duel tour par tour. Il ne connaît que `Fighter`. Le dé lui est **donné** au lieu d'être fabriqué dedans : c'est ce qui rend le combat testable. | 6 (bonus) |

### L'outillage

| Fichier | À quoi il sert |
|---|---|
| `composer.json` | La carte d'identité du projet : la version de PHP exigée, la seule dépendance (`pestphp/pest`), l'autoload PSR-4 (`Dungeon\` → `src/`) et les raccourcis `composer test` / `composer play`. |
| `composer.lock` / `vendor/` | Générés par `composer install`. `vendor/` n'est **jamais** versionné (voir `.gitignore`) : on le régénère. |
| `phpunit.xml` | La configuration du lanceur de tests : où sont les tests (`tests/`), quel fichier charger d'abord (`vendor/autoload.php`). Pest s'appuie dessus. |
| `.gitignore` | Ce que Git doit ignorer : `vendor/`, les caches de tests, les fichiers d'éditeur. |
| `.github/workflows/tests.yml` | La CI : à chaque `push`, GitHub installe PHP 8.4, fait `composer install` et `composer test`. C'est la coche verte (ou rouge) de l'onglet **Actions**. |
| `play.php` | Le point d'entrée « à la main » : un héros traverse cinq salles, ramasse ce qui rentre dans son sac, s'équipe de sa meilleure arme et affronte les monstres. À lire **après** les classes : il montre à quoi elles servent une fois assemblées. |

### Les tests (`tests/`)

Un fichier par classe. Ils ne sont pas là pour faire joli : **ils sont la
spécification lisible** de chaque classe.

| Fichier | Ce qu'il documente |
|---|---|
| `tests/Pest.php` | La configuration de Pest : tous les fichiers de `tests/` sont des tests. On y touche rarement. |
| `tests/Support/FixedDice.php` | Un dé truqué qui déroule une liste de valeurs. Ce n'est pas un test : c'est l'outil qui rend `Battle` reproductible. |
| `tests/DiceTest.php` | Les bornes du lancer (vérifiées 100 fois) et les fabriques statiques. |
| `tests/HeroTest.php` | PV au départ, plancher à 0, plafond à `maxHp`, `__toString()`, `name` bien `readonly`, un sac par héros, `equip`, `drink`, `attack`. |
| `tests/ItemTest.php` | `Item` abstraite, nom, poids, rareté par défaut, `value()`, `Stringable`. |
| `tests/WeaponTest.php` et `tests/PotionTest.php` | L'héritage vu de la classe fille : `parent::__construct()`, le membre en plus, le `describe()` propre à chacune. |
| `tests/RarityTest.php` | Les trois cas, `multiplier()`, `label()`, `from()`, `tryFrom()`, `cases()`. |
| `tests/InventoryTest.php` | Sac vide au départ, ajout, poids total, retrait par nom, refus par exception, `count()` et `foreach`. |
| `tests/InventoryFullExceptionTest.php` | La hiérarchie des exceptions, le message, et le fait qu'une exception n'annule pas ce qui a été fait avant elle. |
| `tests/RoomTest.php` et `tests/DungeonTest.php` | L'agrégation (la salle rend **le même** objet) et la composition (deux donjons ne partagent jamais une salle). |
| `tests/MonsterTest.php`, `tests/GoblinTest.php`, `tests/DragonTest.php` | Classe abstraite, valeurs de chaque monstre, polymorphisme, et le piège de `class_uses()` qui ne remonte pas aux parents. |
| `tests/FighterTest.php` et `tests/HasHealthTest.php` | Le contrat et le trait : ce qu'ils imposent, et ce qu'ils ne disent pas. |
| `tests/BattleTest.php` | Le combat rendu prévisible par un dé donné de l'extérieur. |

Quand vous vous demandez « qu'est-ce que `heal()` fait exactement si je dépasse ? »,
vous ouvrez `tests/HeroTest.php` et vous lisez :

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

- Les exercices : [`poo-katas-26`](https://github.com/opmvpc/poo-katas-26), niveaux 1 à 4 (+ bonus 5).
  Un niveau = un chapitre. Ici tout est écrit, là-bas c'est à vous.
