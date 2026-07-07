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
    }
}
