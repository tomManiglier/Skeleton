import 'vanilla-cookieconsent/dist/cookieconsent.css';
import * as CookieConsent from 'vanilla-cookieconsent';

const configUrl = document.body.dataset.cookieConsentConfigUrl;

if (configUrl) {
    fetch(configUrl)
        .then((response) => response.json())
        .then((config) => CookieConsent.run(config))
        .catch((error: unknown) => {
            console.error('Unable to load cookie consent config', error);
        });
} else {
    console.error('Missing cookie consent config URL on <body>');
}
