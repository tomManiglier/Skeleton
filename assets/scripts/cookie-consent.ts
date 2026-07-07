import 'vanilla-cookieconsent/dist/cookieconsent.css';
import * as CookieConsent from 'vanilla-cookieconsent';

const locale = document.documentElement.lang || 'fr';

fetch(`/${locale}/cookies-consent-config.json`)
    .then((response) => response.json())
    .then((config) => CookieConsent.run(config))
    .catch((error: unknown) => {
        console.error('Unable to load cookie consent config', error);
    });
