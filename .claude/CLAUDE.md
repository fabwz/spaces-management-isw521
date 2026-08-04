# CLAUDE.md — Índice y Reglas del Proyecto

## Proyecto

**Spaces Management ISW-521** — Módulo de Gestión de Espacios Físicos (Grilla
Única y Motor de Asignación de Aulas). Curso Programación en Ambiente Web I,
código ISW-521, UTN Sede San Carlos, II Cuatrimestre 2026.

Cubre los requerimientos AU-01, AU-02, AU-03 y AU-06 (adaptados de
FR-AU-01, FR-AU-02, FR-AU-03, FR-AU-06 del SRS v1.2 del Sistema Integrado de
Gestión Académica y Docente de la UTN). Es uno de cinco módulos del mismo
curso, cada uno desarrollado por un equipo distinto sobre un esquema de base
de datos compartido a nivel de diseño (ver `context/business-rules.md`).

> **Nota de nomenclatura:** `context/` (raíz del repo, minúscula) es
> documentación del proyecto para el equipo y para IA. `app/Contexts/`
> (dentro de `app/`, mayúscula) es el término de Domain-Driven Design
> "Bounded Context" — la carpeta donde vive el código del dominio
> `GestionEspacios`. Son cosas distintas sin relación entre sí; la
> coincidencia de nombre es casual.

## Orden de lectura obligatorio antes de escribir código

1. `context/requirements.md` — qué hay que construir (AU-01/02/03/06) y el
   lenguaje ubicuo del dominio.
2. `context/business-rules.md` — reglas de negocio, casos límite, esquema
   de base de datos oficial, y estrategia/casos de prueba obligatorios.
3. `context/architecture.md` — estructura de carpetas, capas, convenciones
   de nombres/idioma/branching. Incluye el estado de la integración de API
   REST externa, la única decisión que sigue pendiente de confirmación
   externa (por el profesor).
4. `context/technical-logbook.md` — decisiones técnicas, correcciones y
   consultas a la IA ya registradas por el equipo. Revisar antes de
   repetir una decisión ya tomada o un error ya corregido.

## Reglas duras (no negociables)

- El dominio (`app/Contexts/GestionEspacios/Domain/`) **nunca** importa
  clases de Laravel, Livewire ni Alpine.js. Si al eliminar el framework el
  dominio deja de compilar, la arquitectura está mal separada.
- Los intervalos de tiempo son **semiabiertos `[inicio, fin)`**. Un uso que
  termina a las 13:00 y otro que empieza a las 13:00 en el mismo espacio
  **NO** se consideran solapados. Esta regla vive en un único lugar:
  `Domain/ValueObjects/Intervalo.php`. Nunca se reimplementa la comparación
  de horarios en ningún otro archivo.
- Eloquent vive **solo** en `Infrastructure/Eloquent/` como mapper puro de
  persistencia. Nunca contiene lógica de negocio ni se usa directamente
  como si fuera una Entidad de dominio.
- Nunca commitear directo a `main`. Todo cambio va en una rama
  `feature/*`, `fix/*`, `test/*` o `docs/*`, vía Pull Request.
- No existe rama `develop`. El flujo es `feature/* → main` (ver
  `context/architecture.md`).
- Commits en inglés, Conventional Commits estricto:
  `<type>(<scope>): <description>`.
- El esquema de base de datos oficial del profesor
  (`sistema_gestion_academica_utn.sql`) se usa **exactamente como viene**:
  no se agregan, renombran ni modifican tablas, columnas, tipos ni
  valores de enum. Todo mapeo entre ese esquema y el dominio (Entidades,
  Value Objects) se hace sin alterar el esquema origen — nunca se propone
  una columna nueva "porque serviría mejor así".
- La integración de API REST externa sigue **pendiente de confirmación
  del profesor** (ver `context/architecture.md` → "Integración de API
  REST externa"). No implementar ninguna integración hasta que se
  confirme; cuando se confirme, se documenta en un archivo nuevo
  `context/api.md`.
- No inventar campos, tablas, enums o reglas que no estén explícitamente en
  `context/business-rules.md` o en el script SQL oficial del profesor.

## Convención de idioma

| Elemento | Idioma |
|---|---|
| Carpetas de arquitectura (`Domain/`, `Application/`, `Infrastructure/`, `Controllers/`) | Inglés |
| Entidades, Value Objects, Casos de Uso, Servicios de Dominio, Puertos | Español (Lenguaje Ubicuo) |
| Nombres de tablas/columnas de BD | Español (según script oficial del profesor) |
| Comentarios en código | Español |
| Documentación en `context/` | Español |
| Commits (type, scope, description) | Inglés |

## Stack técnico obligatorio

TALL (Tailwind CSS + Alpine.js + Laravel 13 + Livewire) + TypeScript +
consumo de al menos una API REST externa + autenticación JWT + variables de
entorno + pruebas unitarias con Pest + Arquitectura Hexagonal (Ports &
Adapters) + Domain-Driven Design.

## Bounded Context

Todo el módulo vive en un único Bounded Context: `GestionEspacios`, bajo
`app/Contexts/GestionEspacios/`. No crear contextos adicionales sin
discutirlo primero — AU-01, 02, 03 y 06 comparten la misma grilla de
ocupación y no se benefician de fragmentarse artificialmente.
