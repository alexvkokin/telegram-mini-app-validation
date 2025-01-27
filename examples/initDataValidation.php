<?php

declare(strict_types=1);

use Alexvkokin\TelegramMiniAppValidation\InitDataGenerator;
use Alexvkokin\TelegramMiniAppValidation\InitDataService;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$token = 'BotTokenXXXX';
$initData = InitDataGenerator::generate($token, ['id'=>123123123, 'first_name'=> 'bot']);

$initDataService = new InitDataService($token, $initData);
if (!$initDataService->validate()) {
    throw new RuntimeException('Access Deny, User did not pass validation');
}

$webAppUser = $initDataService->resolve();
var_dump($webAppUser);