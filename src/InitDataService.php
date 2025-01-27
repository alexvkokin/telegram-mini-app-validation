<?php

declare(strict_types=1);

namespace Alexvkokin\TelegramMiniAppValidation;

/**
 * @see https://core.telegram.org/bots/webapps#validating-data-received-via-the-mini-app
 */
final readonly class InitDataService
{
    public const HASH_NAME = 'hash=';

    public function __construct(
        private string $token,
        private string $initData,
    ) {
    }

    public function validate(): bool
    {
        $params = $this->explode($this->initData);

        $hash = $this->findHash($params);

        return $hash === self::generateHash($this->token, $params);
    }

    public function resolve(): WebAppUser
    {
        if (!$this->validate()) {
            throw new \RuntimeException('initData, Validation failed');
        }

        $user = [];
        foreach ($this->explode($this->initData) as $item) {
            if (str_starts_with($item, 'user=')) {
                $user = json_decode(substr($item, 5), true);
                break;
            }
        }

        try {
            return new WebAppUser(...$user);
        } catch (\Throwable) {
            throw new \RuntimeException('Cant resolve telegram WebAppUser from initData');
        }
    }

    /**
     * @return array<array-key, string> $params
     */
    private function explode(string $initData): array
    {
        $encoded = urldecode($initData);

        return explode('&', $encoded);
    }

    /**
     * @param array<array-key, string> $params
     */
    private function findHash(array $params): ?string
    {
        foreach ($params as $item) {
            if (str_starts_with($item, self::HASH_NAME)) {
                return substr($item, 5);
            }
        }
        return null;
    }

    /**
     * @param array<array-key, string> $params
     */
    public static function generateHash(string $token, array $params): string
    {
        foreach ($params as $key => $item) {
            if (str_starts_with($item, self::HASH_NAME)) {
                unset($params[$key]);
                break;
            }
        }

        $secret = hash_hmac('sha256', $token, 'WebAppData', true);

        sort($params, SORT_STRING);

        $dataCheckString = implode("\n", $params);

        return hash_hmac('sha256', $dataCheckString, $secret, false);
    }
}
