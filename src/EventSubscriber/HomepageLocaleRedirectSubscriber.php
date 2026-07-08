<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Locale\SupportedLocales;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class HomepageLocaleRedirectSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (! $event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ('app_homepage' !== $request->attributes->get('_route')) {
            return;
        }

        if (SupportedLocales::DEFAULT_LOCALE !== $request->attributes->get('_locale')) {
            return;
        }

        if ($this->alreadyVisited($request)) {
            return;
        }

        $preferred = $request->getPreferredLanguage(SupportedLocales::LIST);
        if (null === $preferred || SupportedLocales::DEFAULT_LOCALE === $preferred) {
            return;
        }

        // setResponse() stops propagation, so LocaleSubscriber never runs for this
        // request: persist the locale here too, otherwise the redirect would repeat forever.
        if ($request->hasSession()) {
            $request->getSession()
                ->set('_locale', $preferred);
        }

        $url = $this->urlGenerator->generate('app_homepage.' . $preferred);
        $event->setResponse(new RedirectResponse($url));
    }

    private function alreadyVisited(Request $request): bool
    {
        return $request->hasSession() && $request->getSession()
            ->has('_locale');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 25]],
        ];
    }
}
