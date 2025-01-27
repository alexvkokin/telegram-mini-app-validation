<?php

declare(strict_types=1);

namespace Alexvkokin\TelegramMiniAppValidation\Tests;

use Alexvkokin\TelegramMiniAppValidation\InitDataGenerator;
use Alexvkokin\TelegramMiniAppValidation\InitDataService;
use Alexvkokin\TelegramMiniAppValidation\WebAppUser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InitDataService::class)]
class TelegramMiniAppValidationTest extends TestCase
{
    public function test_validate(): void
    {
        $token = 'BotTokenXXXX';
        $initData = InitDataGenerator::generate($token, ['id'=>123123123, 'first_name'=> 'bot']);
        $sut = new InitDataService($token, $initData);

        $result = $sut->validate();

        $this->assertTrue($result);
    }

    public function test_resolve(): void
    {
        $token = 'BotTokenXXXX';
        $initData = InitDataGenerator::generate($token, ['id'=>111111, 'first_name'=> 'test']);
        $sut = new InitDataService($token, $initData);

        $webAppUser = $sut->resolve();

        $this->assertEquals(new WebAppUser(111111, 'test'), $webAppUser);
    }
}