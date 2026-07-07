<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\LocaleSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

final class LocaleSubscriberTest extends TestCase
{
    public function testRouteLocaleTakesPriorityOverUrlSegment(): void
    {
        $request = Request::create('/fr/some-page');
        $request->attributes->set('_locale', 'en');

        $this->dispatch($request);

        self::assertSame('en', $request->getLocale());
    }

    public function testUrlSegmentFallback(): void
    {
        $request = Request::create('/en/some-page');
        $request->headers->set('Accept-Language', 'fr');

        $this->dispatch($request);

        self::assertSame('en', $request->getLocale());
    }

    public function testAcceptLanguageFallback(): void
    {
        $request = Request::create('/');
        $request->headers->set('Accept-Language', 'en');

        $this->dispatch($request);

        self::assertSame('en', $request->getLocale());
    }

    public function testDefaultLocaleFallback(): void
    {
        $request = Request::create('/');
        $request->headers->remove('Accept-Language');

        $this->dispatch($request);

        self::assertSame('fr', $request->getLocale());
    }

    private function dispatch(Request $request): void
    {
        $subscriber = new LocaleSubscriber('fr');
        $kernel = $this->createStub(HttpKernelInterface::class);
        $event = new RequestEvent($kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $subscriber->onKernelRequest($event);
    }
}
