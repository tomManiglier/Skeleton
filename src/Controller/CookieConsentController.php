<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CookieConsentController extends AbstractController
{
    #[Route('/cookies-consent-config.json', name: 'app_cookie_consent_config')]
    public function __invoke(): Response
    {
        return $this->render(
            'components/cookie_consent_config.json.twig',
            [],
            new Response(headers: [
                'Content-Type' => 'application/json',
            ]),
        );
    }
}
