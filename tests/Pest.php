<?php

declare(strict_types=1);

// Tous les fichiers *Test.php du dossier tests/ utilisent la classe de base
// de PHPUnit. Pest s'occupe du reste.
pest()->extend(PHPUnit\Framework\TestCase::class)->in(__DIR__);
