<?php

declare(strict_types=1);

namespace Alexvkokin\TelegramMiniAppValidation;

/**
 * @see https://core.telegram.org/bots/webapps#webappuser
 */
final readonly class WebAppUser
{
    public function __construct(
        public int $id,
        public string $first_name,
        public ?bool $is_bot = null,
        public ?string $last_name = null,
        public ?string $username = null,
        public ?string $language_code = null,
        public ?bool $is_premium = null,
        public ?bool $added_to_attachment_menu = null,
        public ?bool $allows_write_to_pm = null,
        public ?string $photo_url = null,
    ) {
    }
}
