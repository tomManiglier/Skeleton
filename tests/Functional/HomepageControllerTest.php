<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class HomepageControllerTest extends WebTestCase
{
    public function testSuccessFR(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => '',
        ]);

        self::assertResponseIsSuccessful();
    }

    public function testSuccessEN(): void
    {
        $client = static::createClient();
        $client->request('GET', '/en/');

        self::assertResponseIsSuccessful();
    }
}
