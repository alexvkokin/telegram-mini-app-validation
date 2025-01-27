<?php

declare(strict_types=1);

namespace Alexvkokin\TelegramMiniAppValidation;

class InitDataGenerator
{
    /**
     * IMPORTANT!
     * This method generates a non-equivalent string to Telegram's initData.
     * The initData from Telegram includes additional parameters such as 'signature', 'initData', etc.
     *
     * This method is intended for testing purposes.
     *
     * @param array<array-key, scalar> $userParams
     */
    public static function generate(string $token, array $userParams): string
    {
        $userStr = 'user=' . json_encode($userParams);

        $hash = InitDataService::HASH_NAME . InitDataService::generateHash($token, [$userStr]);

        return urlencode($userStr . '&' . $hash);
    }
}
