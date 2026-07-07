# AGENTS.md

Instructions pour les agents de code travaillant sur ce dépôt.

## Exécution

- Le projet ne s'exécute que via Docker Compose. N'installe jamais de dépendances PHP ou npm en local : passe toujours par `docker compose exec/run`.
- `make start` et `tests/bootstrap.php` recréent la base de données à chaque exécution. Ne pas s'étonner de perdre des données locales après un `make start` ou un `make test`.

## Locale

- La locale par défaut et la liste des locales supportées sont centralisées dans `config/services.yaml` (`app.default_locale`) et `src/Locale/SupportedLocales.php` (`SupportedLocales::LIST` / `PATTERN`). Ne pas dupliquer la liste des locales ailleurs (attributs de route, subscriber) : référencer cette classe.
- L'ordre de résolution de la locale (voir `src/EventSubscriber/LocaleSubscriber.php`) est : locale de route > segment d'URL > `Accept-Language` > `app.default_locale`.

## Qualité

- `make quality` applique ECS avec `--fix` : il modifie le code automatiquement, ce n'est pas un simple contrôle.
- PHPStan tourne au niveau `max` sur `src/` et `tests/`. Toute nouvelle classe doit rester typée strictement (`declare(strict_types=1)`).
- Prettier ne formate que le SCSS et quelques JSON de config (voir `.prettierignore`) : ne pas s'attendre à ce qu'il touche `src/`, `templates/`, `tests/`, `config/`, `*.php`, `*.yaml` ou `*.ts`.

## Migrations

- Les migrations sont à plat dans `migrations/` (pas de sous-dossier par année). Le template custom est `migrations/template.tpl`.

## Hors scope

- Pas de Messenger dans ce squelette (retiré volontairement).
- Le déploiement (Cloud Build, images de prod) n'est pas encore traité dans ce squelette.
