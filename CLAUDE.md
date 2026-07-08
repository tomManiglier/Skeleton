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
