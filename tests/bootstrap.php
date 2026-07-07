<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$environment = is_string($_SERVER['APP_ENV'] ?? null) ? $_SERVER['APP_ENV'] : 'test';
$kernel = new Kernel($environment, (bool) $_SERVER['APP_DEBUG']);
$application = new Application($kernel);
$application->setAutoExit(false);

$runCommand = static function (string $command, array $arguments = []) use ($application): void {
    $application->run(new ArrayInput(array_merge([
        'command' => $command,
    ], $arguments)), new NullOutput());
};

$runCommand('doctrine:database:drop', [
    '--force' => true,
    '--if-exists' => true,
]);
$runCommand('doctrine:database:create');
$runCommand('doctrine:migrations:migrate', [
    '--no-interaction' => true,
]);
$runCommand('doctrine:fixtures:load', [
    '--no-interaction' => true,
]);
