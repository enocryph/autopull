<?php

namespace AutoPull\Commands;

use Dotenv\Dotenv;

class LoaderCommand implements CommandInterface
{
    private string $rootDirectory;

    public function __construct(string $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }

    public function execute(): void
    {
        if (!file_exists($this->rootDirectory . '/.env')) {
            throw new \Exception('.env configuration file not found');
        }

        $env = Dotenv::createImmutable($this->rootDirectory);
        $env->load();
    }
}
