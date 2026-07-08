<?php

declare(strict_types=1);

use App\Locale\SupportedLocales;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $prefixes = [];
    foreach (SupportedLocales::LIST as $locale) {
        $prefixes[$locale] = SupportedLocales::DEFAULT_LOCALE === $locale ? '' : '/'.$locale;
    }

    $routes->import('routing.controllers')
        ->prefix($prefixes);
};
