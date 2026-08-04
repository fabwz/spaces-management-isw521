# Reglas de Negocio y Casos Límite

Cada regla incluye, inmediatamente después, sus casos límite concretos —
para que regla y prueba de verdad vivan juntas y no se desincronicen.

---

## Regla 1 — Intervalos semiabiertos `[inicio, fin)`

Dos usos del mismo espacio, en el mismo día, se consideran en conflicto
**solo si** sus intervalos de tiempo se solapan bajo la regla semiabierta:
un intervalo que termina en el instante X y otro que empieza en el
instante X **no** se solapan.

Fórmula de solape entre `[a_inicio, a_fin)` y `[b_inicio, b_fin)`:
```
solape = a_inicio < b_fin AND b_inicio < a_fin
```
(nótese `<` estricto en ambos lados — no `<=`)

**Casos límite obligatorios:**
- `[08:00, 13:00)` vs `[13:00, 15:00)` → **NO** hay solape (criterio de
  aceptación explícito de AU-03).
- `[18:00, 21:59)` vs `[19:00, 20:30)` → **SÍ** hay solape (criterio de
  aceptación explícito de AU-03).
- Un intervalo completamente contenido dentro de otro → SÍ hay solape.
- Dos intervalos idénticos → SÍ hay solape.
- Intervalos en días distintos → NUNCA hay solape, sin importar la hora.

Esta regla vive únicamente en `Domain/ValueObjects/Intervalo.php`. Ningún
otro archivo reimplementa esta comparación.

**Construcción en dos etapas:** `feature/domain-core` crea `Intervalo`
solo con la guarda de constructor (`inicio < fin`), necesaria para que
`ReservaPuntual` valide horas desde el CRUD. `feature/au-03-choque-espacio`
**extiende** ese mismo archivo agregando `seSolapaCon()` — nunca se crea
un segundo `Intervalo` ni se reimplementa la validación de constructor en
otro lugar mientras tanto.

---

## Regla 2 — Estados de uso considerados para detección de choques (AU-03)

Solo estos estados de uso participan en la validación de solape:
- Grupos con asignación **"Confirmada"**.
- Grupos con asignación **"Sugerida — pendiente de confirmación"**.
- Reservas puntuales en estado **"Aprobada"**.

Reservas en estado "Solicitada", "Rechazada" o "Cancelada" **no** bloquean
la grilla y no participan en la detección de choques.

**Sobre el mapeo con el esquema oficial:** el enum `grupos.estado` del
script SQL del profesor no incluye literalmente "Confirmada" ni "Sugerida
— pendiente de confirmación". Por restricción del equipo, **no se agrega
ninguna columna nueva a `grupos`** — el estado de asignación de aula se
deriva únicamente de los campos ya existentes (`estado`, `aula_id`), sin
modificar el esquema oficial. La lógica exacta de esa derivación la
define el responsable de dominio/BD al implementar AU-02/AU-03, usando
solo esos campos.

---

## Regla 3 — Rango preferente de capacidad (AU-02)

Dado un grupo con matrícula estimada `M`, el rango preferente de capacidad
es:
```
rango_preferente = [M, PISO(1.5 × M)]
```

**Casos límite obligatorios:**
- `M = 25` → rango preferente `[25, 37]` (`PISO(37.5) = 37`).
- Un espacio con capacidad exactamente `M` → sí califica (límite inferior
  inclusivo).
- Un espacio con capacidad exactamente `PISO(1.5×M)` → sí califica (límite
  superior inclusivo).
- Ningún espacio en el rango preferente, pero sí con `C ≥ M` → se elige el
  de menor capacidad disponible, etiquetado "Sobredimensionado".
- Ningún espacio con `C ≥ M` → alerta "No hay espacios disponibles para
  este grupo en este horario — redistribuya el horario o amplíe el
  inventario".

---

## Regla 4 — Regla de desempate en AU-02

Cuando dos o más espacios empatan en capacidad dentro del rango
preferente `[M, PISO(1.5×M)]`, se aplica un único procedimiento
determinista, en este orden de prioridad:

1. **Menor piso.**
2. **Si persiste el empate (mismo piso): menor número/nombre de
   espacio.**

Equivalente a `ORDER BY piso ASC, nombre ASC`. La razón de negocio del
primer criterio: entre dos aulas de igual capacidad, se prefiere la de
piso más bajo por accesibilidad y menor desplazamiento para grupos
grandes. El segundo criterio garantiza que el procedimiento siempre
resuelva a una única respuesta, incluso en el caso extremo de dos aulas
de igual capacidad en el mismo piso.

---

## Regla 5 — Un grupo usa un único espacio para todas sus sesiones

AU-02 exige que el espacio sugerido esté disponible en **todas** las
sesiones del grupo (no una asignación distinta por sesión). Esto es
consistente con el esquema oficial: `grupos.aula_id` es una única FK, y
`horarios` (las sesiones) son múltiples registros por grupo, todos
apuntando al mismo grupo y por tanto a la misma aula.

---

## Regla 6 — Filtro de laboratorio se aplica primero (AU-02)

Si `cursos.requiere_laboratorio = 1`, el filtro por `tipo` de espacio
(según `cursos.tipo_laboratorio`) se aplica **antes** que el cálculo de
rango preferente. Un espacio que no cumple el tipo de laboratorio requerido
queda excluido sin importar su capacidad.

---

## Esquema de base de datos oficial

Fuente: `context/sistema_gestion_academica_utn.sql` (script SQL entregado
por el profesor, confirmado como base oficial, solo lectura). Es el
esquema compartido de los cinco módulos del curso; solo se documentan aquí
las tablas relevantes a este módulo (`GestionEspacios`).

**Motor de base de datos: MySQL.** Confirmado por el equipo. El
instalador de Laravel 13 trae SQLite como default — se cambia a MySQL en
`.env` (`DB_CONNECTION=mysql`) apenas se clona el proyecto.

> **Restricción obligatoria:** el esquema se usa exactamente tal como el
> profesor lo entregó — mismos nombres de tabla, mismos nombres de
> columna, mismos tipos, mismos valores de enum, misma estructura. No se
> agregan, renombran ni modifican tablas o columnas, ni siquiera si una
> alternativa "serviría mejor". Cualquier lógica de dominio que necesite
> algo que el esquema no ofrece se resuelve derivándolo de los campos
> existentes, nunca alterando el esquema origen.

El script es una guía compartida entre los cinco equipos del curso; cada
equipo implementa su propia base de datos tomando únicamente el
subconjunto de tablas que le compete a su módulo, sin modificarlas.

### Tablas propias del dominio de espacios

**`aulas`** (AU-01 — llamadas "espacios" en el lenguaje de negocio)
```
id, recinto_id (FK), nombre, piso, 
tipo ENUM('Aula regular','Laboratorio de cómputo','Laboratorio de ciencias',
          'Laboratorio de idiomas','Auditorio','Otro'),
capacidad (nullable), no_disponible_desde (date, nullable)
```

**`recintos`**
```
id, nombre, es_propio (bool), activo (bool)
```

**`equipamientos`** + **`aula_equipamiento`** (pivote N:M)
```
equipamientos: id, nombre
aula_equipamiento: aula_id (FK), equipamiento_id (FK), cantidad
```
El equipamiento especial de AU-01 es un catálogo relacional, no un campo
JSON.

**`reservas_aulas`** (AU-06 — Y también el origen "bloqueo administrativo"
de AU-01)
```
id, aula_id (FK), user_id (FK, nullable),
tipo ENUM('Reserva','Bloqueo administrativo'),
solicitante (nullable, NULL en bloqueos administrativos), motivo,
fecha_inicio, fecha_fin (nullable = un solo día),
dias_semana SET('Lunes',...,'Domingo') (nullable = todos/día único),
hora_inicio, hora_fin,
estado ENUM('Solicitada','Aprobada','Rechazada','Cancelada')
```
Esta tabla fusiona reservas puntuales y bloqueos administrativos mediante
la columna `tipo`. Siguiendo la restricción de no modificar el esquema
oficial, el dominio respeta esa misma fusión: `ReservaPuntual` es una
única Entidad con un atributo `tipo` (Value Object `TipoUso`, valores
`Reserva` / `Bloqueo administrativo`) que distingue ambos casos, en vez de
modelar dos Entidades separadas o agregar tablas nuevas.

### Tablas de otros contextos, consumidas por este módulo (solo lectura)

**`grupos`** (AU-02 — pertenece conceptualmente al contexto de Oferta
Académica, gestionado por otro equipo del curso)
```
id, curso_id (FK), periodo_academico_id (FK), meta_id (FK), 
aula_id (FK, nullable), modalidad_id (FK), numero, cupo,
matricula_estimada, matricula_real,
estado ENUM('Necesidad solicitada','Borrador','Enviado al CONTA',
            'Consolidado','Enviado a RRHH','Confirmado por RRHH','Cerrado')
```
Este módulo solo necesita, a través de su Puerto `RepositorioGrupos`:
`matricula_estimada`, y (desde `cursos`) `requiere_laboratorio` y
`tipo_laboratorio`. No debe depender del resto de campos del flujo
académico/RRHH.

**`horarios`** (las sesiones de un grupo)
```
id, grupo_id (FK), 
dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
hora_inicio TIME, hora_fin TIME
```

**`cursos`** (solo los campos relevantes)
```
requiere_laboratorio (bool), 
tipo_laboratorio ENUM('Laboratorio de cómputo','Laboratorio de ciencias',
                       'Laboratorio de idiomas') (nullable)
```

**`roles`, `permissions`, `permission_role`** (RBAC)
El "rol Coordinador" de AU-06 se valida mediante el permiso
`reservas.gestionar`, que en los datos semilla del profesor lo tienen los
roles `Administrador` y `Coordinadora de Docencia`. No se implementa un
campo de rol fijo tipo enum para esto.

---

## Convenciones de formato de datos

- **Formato de hora:** `HH:MM`, consistente con el tipo `TIME` de MySQL
  usado en `horarios.hora_inicio` / `hora_fin` y `reservas_aulas`.
- **Zona horaria:** no aplica manejo especial — un solo campus, sin
  necesidad de conversión de zona horaria.
- **Días de la semana:** en español, con mayúscula inicial, tal como el
  enum oficial (`Lunes`, `Martes`, ..., `Domingo`).

---

## Estrategia y Casos de Prueba Obligatorios

**Framework: Pest.** Corre sobre PHPUnit (misma compatibilidad con
Laravel), pero permite datasets (`it(...)->with([...])`) para escribir de
forma compacta las muchas variaciones de un mismo caso que este proyecto
necesita — especialmente los pares de intervalos de `Intervalo`, que es el
archivo de pruebas con más peso en la rúbrica.

| Tipo | Ubicación | Qué cubre |
|---|---|---|
| Unitarias de Dominio | `tests/Unit/Domain/` | Value Objects y Servicios de Dominio, sin base de datos ni framework |
| Feature | `tests/Feature/` | Endpoints HTTP completos, incluyendo persistencia real |

### Casos obligatorios — `Intervalo` (Value Object)

1. `[08:00, 13:00)` vs `[13:00, 15:00)` → NO hay solape.
2. `[18:00, 21:59)` vs `[19:00, 20:30)` → SÍ hay solape.
3. Un intervalo completamente contenido dentro de otro → SÍ hay solape.
4. Dos intervalos idénticos → SÍ hay solape.
5. Intervalos en días distintos → NUNCA hay solape.
6. Un `Intervalo` con `inicio >= fin` no debe poder construirse.

### Casos obligatorios — `DetectorDeChoques` (Servicio de Dominio, AU-03)

1. Rechazo con detalle del conflicto: espacio, día, horario existente, uso
   en conflicto y su estado.
2. Solo los usos en estado "Confirmada", "Sugerida — pendiente de
   confirmación" o "Aprobada" participan en la detección (Regla 2). Un uso
   "Rechazada" o "Cancelada" no debe generar falso conflicto.
3. Ningún conflicto cuando no hay solape real, incluyendo el caso límite
   de intervalos que se tocan en el borde.

### Casos obligatorios — `SugeridorDeAula` (Servicio de Dominio, AU-02)

1. Grupo de 25 estudiantes sin laboratorio con un aula de 30 disponible en
   todas sus sesiones → se sugiere esa aula, sin etiqueta.
2. Sin ningún aula con capacidad entre 25 y 37 → se sugiere la de menor
   capacidad con `C ≥ 25`, etiquetada "Sobredimensionado".
3. Sin ninguna aula con `C ≥ 25` → alerta de indisponibilidad.
4. Curso que requiere laboratorio → el filtro de tipo se aplica antes que
   el cálculo de capacidad.
5. El espacio sugerido debe estar disponible en **todas** las sesiones del
   grupo.
6. Empate de capacidad dentro del rango preferente, resuelto por menor
   piso y, si persiste, menor número/nombre de espacio (Regla 4).

### Casos obligatorios — `AprobarReservaPuntual` (Caso de Uso, AU-06)

1. Reserva sobre fecha/horario libre → al aprobar, pasa a "Aprobada",
   bloquea el intervalo, visible en la grilla con su motivo.
2. Reserva sobre un intervalo ya ocupado por un grupo o por otra reserva
   aprobada → rechazo mostrando el conflicto (reutiliza
   `DetectorDeChoques`, no una validación propia).
3. Solo un usuario con el permiso `reservas.gestionar` puede aprobar.

### Qué NO probar todavía

Solo la integración de API externa — hasta que se confirme cuál se va a
usar (ver `architecture.md` → "Integración de API REST externa").
