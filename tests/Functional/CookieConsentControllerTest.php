<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CookieConsentControllerTest extends WebTestCase
{
    public function testReturnsJsonConfig(): void
    {
        $client = static::createClient();
        $client->request('GET', '/fr/cookies-consent-config.json');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');

        $content = $client->getResponse()
            ->getContent();
        self::assertIsString($content);

        $config = json_decode($content, true);
        self::assertIsArray($config);
        self::assertArrayHasKey('categories', $config);

        $categories = $config['categories'];
        self::assertIsArray($categories);
        self::assertArrayHasKey('necessary', $categories);
        self::assertArrayHasKey('analytics', $categories);
    }
}
