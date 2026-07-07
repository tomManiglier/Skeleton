# Skeleton

Squelette de projet Symfony 8 : Docker Compose, PostgreSQL, Webpack Encore (TypeScript + Sass), qualité PHP (ECS + PHPStan) et outillage frontend (ESLint, Stylelint, Prettier, Vitest).

## Prérequis

- Docker et Docker Compose
- Aucun besoin de PHP, Composer ou Node en local : tout tourne dans les conteneurs.

## Installation

```
make build
make start
```

`make start` installe les dépendances PHP et npm, recrée la base de données locale, joue les migrations et les fixtures, puis compile les assets.

## Cycle de développement

```
make up          # démarre les conteneurs
make down        # arrête les conteneurs
make sw          # redémarre les conteneurs
make ps          # état des conteneurs
make watch       # recompile les assets en continu
```

## Entrer dans les conteneurs

```
make in          # shell dans php-fpm
make in-web      # shell dans nginx
make in-db       # psql dans la base
make in-node     # shell dans node
```

## Qualité

```
make quality             # ECS (--fix) + PHPStan sur src/ et tests/
make quality-front       # ESLint, Stylelint, tsc --noEmit, Prettier (scss + JSON de config)
make quality-front-fix   # variantes --fix des mêmes outils
```

## Tests

```
make test            # PHPUnit (recrée la base à chaque exécution)
make test-front      # Vitest
make test-coverage   # PHPUnit avec rapport de couverture HTML (pcov)
```

## Nettoyage

```
make clean
```

Supprime les conteneurs, volumes et le cache Symfony.
