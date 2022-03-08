<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = App\Bootstrap::boot();
$container = $configurator->createContainer(); //kontejner vytvoříme pomocí $configurator
$application = $container->getByType(Nette\Application\Application::class); //vyrobí objekt application
$application->run(); //metoda run() se spustí Nette aplication
