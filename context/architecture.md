# Arquitectura

Arquitectura Hexagonal (Ports & Adapters) + Domain-Driven Design.

---

## Bounded Context

Un único contexto: `GestionEspacios`, bajo `app/Contexts/GestionEspacios/`.
AU-01, AU-02, AU-03 y AU-06 comparten la misma grilla de ocupación — no se
fragmentan en contextos separados.

## Mapa de las cuatro capas

| Capa | Qué contiene | Regla de dependencia | Carpeta |
|---|---|---|---|
| Dominio | Entidades, Value Objects, Puertos, Servicios de Dominio, Eventos | Cero dependencias externas | `Domain/` |
| Aplicación | Casos de Uso, DTOs | Depende solo del Dominio | `Application/` |
| Infraestructura | Repositorios Eloquent, adaptadores, colas | Implementa los Puertos del Dominio | `Infrastructure/` |
| Presentación | Controladores, Requests, Resources | Traduce entrada/salida, invoca Aplicación | `Infrastructure/Http/` |

Todas las flechas de dependencia apuntan hacia el Dominio. El Dominio nunca
apunta hacia afuera.

## Árbol de carpetas completo

```
spaces-management-isw521/
├── .claude/
│   └── CLAUDE.md
├── context/
│   ├── requirements.md
│   ├── business-rules.md
│   ├── architecture.md
│   ├── technical-logbook.md
│   ├── sistema_gestion_academica_utn.sql   # fuente oficial del profesor, SOLO LECTURA — nunca se edita
│   └── api.md              # se agrega cuando se confirme la API externa
├── app/
│   ├── Console/                        # default de Laravel
│   ├── Exceptions/                      # default de Laravel
│   ├── Http/
│   │   └── Middleware/                  # middleware de JWT
│   ├── Providers/
│   │   └── AppServiceProvider.php       # default — sin bindings de dominio
│   └── Contexts/
│       └── GestionEspacios/
│           ├── Domain/
│           │   ├── Entidades/
│           │   │   ├── Espacio.php
│           │   │   ├── GrupoAcademico.php
│           │   │   └── ReservaPuntual.php          # Aggregate Root
│           │   ├── ValueObjects/
│           │   │   ├── Intervalo.php               # [inicio, fin) semiabierto
│           │   │   ├── Capacidad.php
│           │   │   ├── TipoEspacio.php
│           │   │   └── EstadoUso.php
│           │   ├── Puertos/
│           │   │   ├── RepositorioEspacios.php
│           │   │   ├── RepositorioReservas.php
│           │   │   └── RepositorioGrupos.php
│           │   ├── Servicios/
│           │   │   ├── DetectorDeChoques.php       # AU-03 — Servicio de Dominio
│           │   │   └── SugeridorDeAula.php          # AU-02 — Servicio de Dominio
│           │   └── Eventos/
│           │       ├── ReservaAprobada.php
│           │       └── AulaAsignada.php
│           ├── Application/
│           │   ├── CasosDeUso/
│           │   │   ├── RegistrarEspacio.php
│           │   │   ├── ConsultarGrillaDeEspacio.php
│           │   │   ├── SugerirAulaParaGrupo.php
│           │   │   ├── ConfirmarAsignacionDeAula.php
│           │   │   ├── SolicitarReservaPuntual.php
│           │   │   └── AprobarReservaPuntual.php
│           │   └── DTOs/
│           └── Infrastructure/
│               ├── Eloquent/
│               │   ├── AulaEloquentModel.php
│               │   ├── ReservaAulaEloquentModel.php
│               │   └── GrupoEloquentModel.php
│               ├── Repositorios/
│               │   ├── RepositorioEspaciosEloquent.php
│               │   ├── RepositorioReservasEloquent.php
│               │   └── RepositorioGruposEloquent.php
│               ├── Http/
│               │   ├── Controllers/
│               │   ├── Requests/
│               │   └── Resources/
│               ├── Listeners/
│               │   └── NotificarReservaAprobada.php
│               └── Providers/
│                   └── GestionEspaciosServiceProvider.php
├── bootstrap/
│   └── providers.php                    # registra GestionEspaciosServiceProvider
├── config/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   └── views/                           # Blade + Livewire (TALL stack)
├── routes/
├── tests/
│   ├── Unit/Domain/
│   └── Feature/
├── .env.example
├── .gitignore
├── composer.json                        # autoload PSR-4: App\Contexts\ → app/Contexts/
└── README.md
```

---

## Convención de idioma

| Elemento | Idioma | Motivo |
|---|---|---|
| Carpetas de arquitectura (`Domain/`, `Application/`, `Infrastructure/`, `Controllers/`, `Requests/`) | Inglés | Términos técnicos universales de la industria |
| Entidades, Value Objects, Casos de Uso, Servicios de Dominio, Puertos | Español | Lenguaje Ubicuo — el código habla como habla el negocio |
| Tablas/columnas de BD | Como venga en el script oficial del profesor | No es una elección del equipo — se redacta exactamente según el esquema entregado, sin traducir ni ajustar nombres |
| Comentarios en código | Español | Consistencia con el resto |
| `context/*.md` y `CLAUDE.md` | Español | Es donde se documenta el lenguaje ubicuo y las decisiones de negocio |

## Modelo de ramas

Sin rama `develop`. Todo sale de `main` y regresa a `main` vía Pull
Request.

La lista siguiente es el núcleo de ramas ya anticipado por el alcance
actual del proyecto — **no es una lista cerrada**. Cualquier rama nueva
que surja sobre la marcha (un fix, una prueba aislada, autenticación JWT
si no queda resuelta en `project-setup`, etc.) sigue el mismo patrón de
nombres `tipo/descripcion-corta`, sale de `main` y vuelve a `main` vía PR.
La regla dura no es "solo estas ramas existen", es "ninguna rama se
commitea directo a `main`, sin importar si estaba anticipada aquí o no".

```
main
├── feature/project-setup            # Laravel + carpetas + .claude/ + context/ + README
├── feature/persistence-skeleton     # Avance 1: migrations, models, factories, seeders
├── feature/domain-core              # Entidades y Puertos mínimos (Espacio, Recinto,
│                                     # Equipamiento, ReservaPuntual) + Intervalo VO mínimo
│                                     # (solo constructor: inicio < fin) — sin seSolapaCon()
│                                     # ni DetectorDeChoques todavía, eso llega con au-03
├── feature/crud-gestion-espacios    # Avance 1 (CRUD): Casos de Uso simples + Controllers/
│                                     # Requests/Resources/rutas/vistas para las 5 tablas
├── feature/au-03-choque-espacio     # extiende Intervalo con seSolapaCon() + agrega
│                                     # DetectorDeChoques; bloqueante para au-02 y au-06
├── feature/au-01-inventario-grilla  # puede ir en paralelo a au-03
├── feature/au-02-sugerencia-aula    # depende de au-03
├── feature/au-06-reservas-puntuales # depende de au-03
└── feature/api-integration           # transversal
```

Orden de merge: `project-setup → persistence-skeleton → domain-core →
crud-gestion-espacios → au-03 → (au-01 en paralelo) → au-02 → au-06 →
api-integration`.

**Reglas:**
- Nunca commitear directo a `main`.
- Cada PR debe ser revisado por al menos otro integrante del equipo, no
  solo por quien lo escribió (aunque el equipo sea de 3), para reforzar el
  dominio distribuido del conocimiento evaluado en la defensa oral.
- `main` debe estar estable antes de cada revisión (semanas 10, 12, 14).

### Resolución de la API REST externa (única pendiente)

Cuando el profesor confirme cuál API se va a usar:
- Se crea `context/api.md` con el detalle de la integración (endpoint,
  autenticación, uso concreto dentro del dominio).
- Se actualiza esta sección ("Integración de API REST externa" más abajo)
  para referenciar ese archivo nuevo en vez del bloque "Pendiente".
- Ambos cambios van en el mismo commit, en rama `docs/resolve-api-rest` o
  directamente dentro de `feature/api-integration` si ya se va a
  implementar en el mismo momento — nunca directo en `main` sin PR.

---

## Integración de API REST externa

> **Pendiente de confirmación del profesor.** Todavía no se ha elegido
> cuál API REST externa se va a consumir. Dirección propuesta (no
> confirmada): integración de notificaciones conectada al evento de
> dominio `ReservaAprobada` de AU-06. No implementar ninguna integración
> hasta que el profesor la confirme. Cuando se confirme, se documenta en
> un archivo nuevo `context/api.md` (ver "Resolución de la API REST
> externa" arriba, en la sección de branching).

## Eloquent como mapper de persistencia

Eloquent implementa Active Record, lo cual es contrario a DDD por diseño.
Se usa estrictamente en `Infrastructure/Eloquent/` como mapper (patrón
Data Mapper): reconstruye Entidades de dominio puras y nunca al revés. Las
Entidades de dominio (`Espacio`, `ReservaPuntual`, etc.) son clases PHP
normales que no extienden ningún `Model` de Eloquent.

Como los modelos Eloquent no están en `app/Models/`, hay que sobrescribir
`newFactory()` en cada modelo para que Laravel encuentre su factory
correspondiente en `database/factories/`.

## Acoplamiento entre Bounded Contexts

`grupos` (necesario para AU-02) tiene FKs hacia `cursos`,
`periodos_academicos`, `metas` y `modalidades` — tablas que pertenecen
conceptualmente a otro contexto (Oferta Académica, de otro equipo del
curso). El dominio de `GestionEspacios` no conoce esos detalles: accede
solo a lo mínimo necesario (`matricula_estimada`, `requiere_laboratorio`,
`tipo_laboratorio`) a través de su propio Puerto `RepositorioGrupos`.
