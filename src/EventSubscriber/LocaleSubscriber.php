<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Locale\SupportedLocales;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly string $defaultLocale
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (! $event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $locale = $this->resolveLocale($request);

        $request->setLocale($locale);

        if ($request->hasSession()) {
            $request->getSession()
                ->set('_locale', $locale);
        }
    }

    private function resolveLocale(Request $request): string
    {
        $routeLocale = $request->attributes->get('_locale');
        if (is_string($routeLocale) && in_array($routeLocale, SupportedLocales::LIST, true)) {
            return $routeLocale;
        }

        $segment = substr($request->getPathInfo(), 1, 2);
        if (in_array($segment, SupportedLocales::LIST, true)) {
            return $segment;
        }

        $preferred = $request->getPreferredLanguage(SupportedLocales::LIST);
        if (null !== $preferred) {
            return $preferred;
        }

        return $this->defaultLocale;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
