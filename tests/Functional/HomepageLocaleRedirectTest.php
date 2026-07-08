<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomepageLocaleRedirectTest extends WebTestCase
{
    public function testRedirectsToPreferredLocaleOnFirstVisit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);

        self::assertResponseRedirects('/en/');
    }

    public function testDoesNotRedirectOnSubsequentVisit(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);
        self::assertResponseRedirects('/en/');

        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => 'en',
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testDoesNotRedirectWithoutAcceptLanguage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => '',
        ]);

        self::assertResponseIsSuccessful();
    }
}
