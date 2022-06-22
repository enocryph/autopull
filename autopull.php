<?php

if (!file_exists('vendor/autoload.php')) {
    throw new \Exception('Vendor autoload not found, please run composer install');
}

const ROOT = __DIR__;
require_once 'vendor/autoload.php';

use AutoPull\Webhook;
use AutoPull\Commands\LoaderCommand;
use AutoPull\Commands\ValidatorCommand;
use AutoPull\Commands\AutoPullCommand;
use AutoPull\Commands\CommandInterface;

$webhook = new Webhook();

/** @var CommandInterface[] $query */
$query = [
    new LoaderCommand(ROOT),
    new ValidatorCommand($webhook),
    new AutoPullCommand($webhook)
];

foreach ($query as $command) {
    try {
        $command->execute();
    } catch (Exception $ex) {
        echo $ex->getMessage();
        die();
    }
}
