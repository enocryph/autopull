<?php

namespace AutoPull\Commands;

use AutoPull\Webhook;

class AutoPullCommand implements CommandInterface
{
    private Webhook $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function execute(): void
    {
        $data = $this->webhook->getData();
        $branch = pathinfo($data['ref'])['filename'];

        if ($branch && ($path = getenv('BRANCH_PATH'))) {
            echo exec("cd {$path} && /usr/bin/git pull origin {$branch} && /usr/bin/composer install 2>&1");
            throw new \Exception('Successful pull');
        } else {
            throw new \Exception('Pull failed');
        }
    }
}
