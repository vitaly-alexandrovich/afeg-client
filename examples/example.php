<?php
include_once __DIR__ . '/../vendor/autoload.php';

$apiKey = '';
$client = new \Afeg\Client('YOUR_API_KEY');

print 'Создаем случайный email на случайном домене' . PHP_EOL;
$email = $client->create();

print ' - Создали email: ' . $email->getAddress() . PHP_EOL . PHP_EOL;

// Генерируем случайные домен и почту
$username = 'test' . rand(11111, 99999);
$availableDomains = \Afeg\Client::getAvailableDomains();
$domain = $availableDomains[array_rand($availableDomains)];

print 'Создаем email ' . $username . ' на домене ' . $domain . PHP_EOL;
$email = $client->create($domain, $username);

print ' - Создали email: ' . $email->getAddress() . PHP_EOL . PHP_EOL;

print 'Пробуем получить новые письма с ящика email ' . $username . '@' . $domain . PHP_EOL;
$response = $email->fetch();

print ' - Получили ' . $response->getLength() . ' писем' . PHP_EOL . PHP_EOL;

print 'Пробуем получить все письма с ящика email ' . $username . '@' . $domain . PHP_EOL;
$response = $email->fetchAll();

print ' - Получили ' . $response->getLength() . ' писем' . PHP_EOL . PHP_EOL;