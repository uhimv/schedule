# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Symfony 7.4 (PHP 8.4) application that generates school schedules ("розклад") from curriculum, teacher, subject, class and bell (lesson-slot) data. Single working endpoint: `GET /schedule/generate`.

## Commands

All app commands run inside the `php` Docker container via `make`, from the repo root (not `app/`).

```
make up_build_app      # build + start containers (web:80, php:9000, db)
make up_app            # start containers
make stop_app          # stop containers
make init_app          # full init: build + composer install + migrations
make composer_install
make migrations_up     # doctrine:migrations:migrate

make check_quality     # phpcs + psalm
make phpcs             # PSR-12 check (phpcs.xml, scans app/src)
make phpcbf            # auto-fix code style
make psalm             # static analysis (psalm.xml, errorLevel=3)
```

There is no PHPUnit/test suite configured in this project (no `tests/` dir, no phpunit dependency) — don't assume `make test` exists.

To run a single phpcs/psalm check on one file, exec into the container directly:
```
docker exec -it php vendor/bin/phpcs --standard=phpcs.xml src/Path/To/File.php
docker exec -it php vendor/bin/psalm src/Path/To/File.php
```

`.env` is a symlink to `app/.env`; DB credentials (`DB_NAME`, `DB_PASS`, `DB_PORT`) come from the root `.env` and feed both the `db` container and Doctrine DBAL.

## Architecture

Layered/DDD-lite structure under `app/src/`, one subtree per bounded concept (`Bell`, `Curriculum`, `SchoolClass`, `Subject`, `Teacher`, `TeacherSubject`):

- **`Domain/`** — framework-agnostic core. Excluded from Symfony's autowiring resource scan (`services.yaml` excludes `../src/Domain/*`); only explicit interface→implementation bindings are wired.
  - `Entity/` — plain classes with **private constructors**; the only way to build one is `Entity::hydrate(SomeHydrateCommandInterface $command)`. Entities expose `toArray()` for serialization.
  - `Command/<Concept>/Hydrate<Concept>Command(Interface)` — a typed DTO pair that carries raw scalar data into an entity's `hydrate()`. Interface + concrete impl per concept.
  - `ValueObject/<Concept>/` and `ValueObject/Shared/` — immutable, validating VOs (e.g. `Uuid`, `Name`). Validation happens in the constructor; invalid input throws `Domain\Exception\InvalidArgumentException` immediately — see the DTO/VO rule below.
  - `Collection/` — typed collections extending `AbstractCollection` (`Countable`, `IteratorAggregate`). Subclasses implement `getTargetClass()`; `add()` runtime-checks the item's class and throws on mismatch. This is the project's pattern for validating "array of X" contracts that PHPDoc alone can't enforce.
  - `Repository/*RepositoryInterface` — one per concept, method `findAll(): XCollection`.
  - `DomainService/ScheduleGenerator` — the core scheduling algorithm: shuffles curriculum×hours-per-week slots, then greedily assigns each to the first non-conflicting (teacher-free AND class-free) `DayWeek`×`Bell` slot. `ScheduleLine` is the resulting tuple (day, bell, class, subject, teacher).
- **`Application/UseCase/<Concept>/`** — orchestrates repositories + domain services for one use case (e.g. `Schedule\GenerateHandler` loads all six collections, runs `ScheduleGenerator`, maps lines to arrays, sorts by day/class/bell).
- **`Infrastructure/`**
  - `Controller/` — thin Symfony controllers (attribute routing, `#[Route(...)]`), delegate straight to an Application handler.
  - `Repository/` — implements the Domain repository interfaces using **raw SQL via `EntityManagerInterface::getConnection()`** (not Doctrine ORM entity mapping — there are no ORM-mapped entity classes). Doctrine ORM/migrations are used only for schema migrations.
  - `Repository/Mapper/` — one mapper per concept, `fromArray(array $row): Entity`, builds the `Hydrate*Command` and calls `Entity::hydrate()`.

Data flow for the one existing feature: `ScheduleController` → `GenerateHandler` (Application) → six `*Repository::findAll()` (Infrastructure, raw SQL → Mapper → Entity) → `ScheduleGenerator` (Domain) → array of schedule lines → JSON response.

### Conventions to follow when extending this pattern

- New domain concepts follow the existing five-folder shape: `Entity` (private ctor + `hydrate()`), `Command/Hydrate<X>Command(Interface)`, `ValueObject`, `Repository/<X>RepositoryInterface`, `Collection/<X>Collection extends AbstractCollection`.
- Wire new repository interfaces in `app/config/services.yaml` under the explicit interface bindings list (autowiring won't find them since `Domain/*` is excluded).
- Entity IDs are `Uuid` value objects (UUIDv7, custom implementation in `Domain/ValueObject/Shared/Uuid.php` — **not** `symfony/uid`); DB columns are `BINARY(16)` written via `UUID_TO_BIN(...)`. `Uuid::toBase32()` mirrors Symfony's `AbstractUid::toBase32()` for external-facing IDs.
- Per the DTO/VO rule (see global CLAUDE.md): Hydrate commands and mappers must read required keys directly (`$data['id']`), never mask a missing key with `?? ''`/`?? 0`/etc. Let it throw.
- New `id` / FK columns in migrations should be declared `UNSIGNED` when integer-based (per global convention); this project's existing tables use `BINARY(16)` UUIDs instead, which don't apply.
