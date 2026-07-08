# AGENTS.md

Instructions pour les agents de code travaillant sur ce dépôt.

## Exécution

- Le projet ne s'exécute que via Docker Compose. N'installe jamais de dépendances PHP ou npm en local : passe toujours par `docker compose exec/run`.
- `make start` et `tests/bootstrap.php` recréent la base de données à chaque exécution. Ne pas s'étonner de perdre des données locales après un `make start` ou un `make test`.
- `vendor/`, `var/` et `node_modules/` sont montés comme volumes Docker nommés (pas en bind mount) pour éviter la lenteur des bind mounts Windows (plusieurs secondes par requête avec des milliers de fichiers `vendor/`). Conséquence : après un `docker compose down -v` ou une suppression manuelle de ces volumes, il faut relancer `composer install` / `npm install` (déjà fait par `make start`) pour les repeupler.

## Git & GitHub

- Une branche par changement, nommée `type/description-courte` (`feat/`, `fix/`, `chore/`, `docs/`, `refactor/`, `test/`). Toujours partir de `main` à jour (`git fetch && git checkout -b type/nom origin/main`), jamais d'une autre branche de feature.
- Messages de commit au format [Conventional Commits](https://www.conventionalcommits.org/) : `feat: ...`, `fix: ...`, `chore: ...`, `docs: ...`, `refactor: ...`, `test: ...`. Sujet à l'impératif, un commit = un changement cohérent.
- Les PR sont mergées en **merge commit** (pas de squash, pas de rebase) : l'historique de `main` garde tous les commits de la branche d'origine plus un commit de merge.
- Pas de protection stricte sur `main` : un push direct reste possible pour un correctif mineur, mais tout changement non trivial passe par une branche + PR.
- Ne jamais force-push une branche déjà partagée ou revue sans prévenir.

## Locale

- La locale par défaut et la liste des locales supportées sont centralisées dans `src/Locale/SupportedLocales.php` (`SupportedLocales::LIST` / `DEFAULT_LOCALE`). `config/services.yaml` référence `SupportedLocales::DEFAULT_LOCALE` via `!php/const` pour le paramètre `app.default_locale`. Ne pas dupliquer la liste des locales ailleurs (attributs de route, subscriber, config de routing) : référencer cette classe.
- Le préfixe d'URL par locale n'est pas posé route par route : `config/routes.php` importe les contrôleurs une seule fois avec un préfixe calculé par locale (`RoutingConfigurator::prefix()`), vide pour `DEFAULT_LOCALE` et `/{locale}` pour les autres. La locale par défaut (`fr`) n'apparaît donc pas dans l'URL (`/`, `/cookies-consent-config.json`) alors que les autres locales sont préfixées (`/en/`, `/en/cookies-consent-config.json`). Les contrôleurs déclarent des chemins sans `{_locale}`.
- L'ordre de résolution de la locale (voir `src/EventSubscriber/LocaleSubscriber.php`) est : locale de route > segment d'URL > `Accept-Language` > `app.default_locale`. Le segment d'URL et l'`Accept-Language` ne servent que de filet de sécurité, la locale de route (posée par le préfixe ci-dessus) étant presque toujours déjà résolue quand une requête matche une route.
- `src/EventSubscriber/HomepageLocaleRedirectSubscriber.php` redirige une seule fois `/` vers la locale préférée du navigateur (`Accept-Language`) si elle diffère de la locale par défaut. Le premier passage est mémorisé via la présence de `_locale` en session (déjà peuplée par `LocaleSubscriber`) : les visites suivantes, ou une navigation explicite vers `/`, ne sont plus redirigées.

## Qualité

- `make quality` applique ECS avec `--fix` : il modifie le code automatiquement, ce n'est pas un simple contrôle.
- PHPStan tourne au niveau `max` sur `src/` et `tests/`. Toute nouvelle classe doit rester typée strictement (`declare(strict_types=1)`).
- Prettier ne formate que le SCSS et quelques JSON de config (voir `.prettierignore`) : ne pas s'attendre à ce qu'il touche `src/`, `templates/`, `tests/`, `config/`, `*.php`, `*.yaml` ou `*.ts`.

## Migrations

- Les migrations sont à plat dans `migrations/` (pas de sous-dossier par année). Le template custom est `migrations/template.tpl`.

## Hors scope

- Pas de Messenger dans ce squelette (retiré volontairement).
- Le déploiement (Cloud Build, images de prod) n'est pas encore traité dans ce squelette.
