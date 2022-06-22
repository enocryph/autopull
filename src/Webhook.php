<?php

namespace AutoPull;

class Webhook
{
    private string $secret;

    private array $data = [];

    private string $event = '';

    public function setSecret(string $secret): void
    {
        $this->secret = $secret;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function isSecretValid(): bool
    {
        if (empty($_SERVER['HTTP_X_HUB_SIGNATURE']) || empty($_SERVER['HTTP_X_GITHUB_EVENT'])) {
            return false;
        }

        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
        $event = $_SERVER['HTTP_X_GITHUB_EVENT'];

        if (!$this->isSignatureValid($signature, $payload)) {
            return false;
        }

        $this->data = json_decode($payload, true);
        $this->event = $event;
        return true;
    }

    protected function isSignatureValid($signatureHttpHeader, $payload): bool
    {
        [$algorithm, $signature] = explode("=", $signatureHttpHeader);

        if ($algorithm !== 'sha1') {
            return false;
        }

        return hash_hmac($algorithm, $payload, $this->secret) === $signature;
    }
}