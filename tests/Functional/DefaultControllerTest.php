<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DefaultControllerTest extends WebTestCase
{
    public function testRedirectsToDefaultLocaleHomepage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/', server: [
            'HTTP_ACCEPT_LANGUAGE' => '',
        ]);

        self::assertResponseRedirects('/fr/');
    }
}
