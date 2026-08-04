# Spaces Management ISW-521

Módulo de Gestión de Espacios Físicos — Grilla Única y Motor de Asignación
de Aulas. Proyecto de Programación en Ambiente Web I (ISW-521), UTN Sede
San Carlos, II Cuatrimestre 2026.

Cubre los requerimientos AU-01 (Inventario y Grilla), AU-02 (Sugerencia
Automática de Aula), AU-03 (Bloqueo por Choque de Espacio) y AU-06
(Reservas Puntuales y Préstamos de Espacios), adaptados del SRS v1.2 del
Sistema Integrado de Gestión Académica y Docente de la UTN.

## Stack técnico

- TALL Stack: Tailwind CSS + Alpine.js + Laravel 13 + Livewire
- TypeScript
- Autenticación JWT
- API REST externa (en confirmación, ver `context/architecture.md`)
- Pruebas unitarias con Pest
- Arquitectura Hexagonal (Ports & Adapters) + Domain-Driven Design

## Requisitos

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL (o el motor configurado en `.env`)

## Instalación

```bash
git clone https://github.com/fabwz/spaces-management-isw521.git
cd spaces-management-isw521

composer install
cp .env.example .env
php artisan key:generate

npm install
npm run build

php artisan migrate --seed
```

## Levantar el entorno de desarrollo

```bash
php artisan serve
npm run dev
```

## Pruebas

```bash
php artisan test
```

## Estructura del proyecto

La lógica de dominio vive en `app/Contexts/GestionEspacios/`, organizada
en Arquitectura Hexagonal (Domain / Application / Infrastructure). El
detalle completo de capas, convenciones y modelo de ramas está en
`context/architecture.md`.

Documentación del proyecto para el equipo y para IA (Claude Code):

```
.claude/CLAUDE.md              # reglas del proyecto
context/requirements.md        # requerimientos funcionales (AU-01/02/03/06)
context/business-rules.md      # reglas de negocio y esquema de BD oficial
context/architecture.md        # arquitectura, capas y modelo de ramas
context/technical-logbook.md   # bitácora de decisiones técnicas e IA
```

## Equipo

- Fabián Zamora
- Giovanni Sandi
- Marypaz Lopez

## Docente

Bryan Miguel Chaves Salas
