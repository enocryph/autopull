<?php

namespace AutoPull\Commands;

use AutoPull\Webhook;
use GitHubWebhook\Handler;

class ValidatorCommand implements CommandInterface
{
    private Webhook $webhook;

    public function __construct(Webhook $webhook) {
        $this->webhook = $webhook;
    }

    public function execute(): void
    {
        if (!($secret = getenv('GITHUB_HOOK_SECRET'))) {
            throw new \Exception("GITHUB_HOOK_SECRET not found in .env");
        }

        $this->webhook->setSecret(getenv('GITHUB_HOOK_SECRET'));

        if (!$this->webhook->isSecretValid()) {
            throw new \Exception("Secret validation failed");
        }
    }
}
