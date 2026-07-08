<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\HomepageLocaleRedirectSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class HomepageLocaleRedirectSubscriberTest extends TestCase
{
    public function testRedirectsToPreferredLocaleOnFirstVisit(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'app_homepage');
        $request->attributes->set('_locale', 'fr');
        $request->headers->set('Accept-Language', 'en');
        $request->setSession(new Session(new MockArraySessionStorage()));

        $event = $this->dispatch($request);
        $response = $event->getResponse();

        self::assertNotNull($response);
        self::assertSame('/en/', $response->headers->get('Location'));
    }

    public function testDoesNotRedirectWhenAlreadyVisited(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'app_homepage');
        $request->attributes->set('_locale', 'fr');
        $request->headers->set('Accept-Language', 'en');

        $session = new Session(new MockArraySessionStorage());
        $session->set('_locale', 'fr');
        $request->setSession($session);

        $event = $this->dispatch($request);

        self::assertNull($event->getResponse());
    }

    public function testDoesNotRedirectOnNonDefaultLocaleRoute(): void
    {
        $request = Request::create('/en/');
        $request->attributes->set('_route', 'app_homepage');
        $request->attributes->set('_locale', 'en');
        $request->headers->set('Accept-Language', 'en');
        $request->setSession(new Session(new MockArraySessionStorage()));

        $event = $this->dispatch($request);

        self::assertNull($event->getResponse());
    }

    public function testDoesNotRedirectWhenAcceptLanguagePrefersDefaultLocale(): void
    {
        $request = Request::create('/');
        $request->attributes->set('_route', 'app_homepage');
        $request->attributes->set('_locale', 'fr');
        $request->headers->set('Accept-Language', 'fr');
        $request->setSession(new Session(new MockArraySessionStorage()));

        $event = $this->dispatch($request);

        self::assertNull($event->getResponse());
    }

    private function dispatch(Request $request): RequestEvent
    {
        $urlGenerator = $this->createStub(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')
            ->willReturnCallback(static fn (string $name): string => match ($name) {
                'app_homepage.en' => '/en/',
                default => '/',
            });

        $subscriber = new HomepageLocaleRedirectSubscriber($urlGenerator);
        $kernel = $this->createStub(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $subscriber->onKernelRequest($event);

        return $event;
    }
}
