# Requerimientos Funcionales

Fuente: Ficha de Proyecto de Módulo — "Módulo de Gestión de Espacios
Físicos — Grilla Única y Motor de Asignación de Aulas", adaptado de
FR-AU-01, FR-AU-02, FR-AU-03 y FR-AU-06 del SRS v1.2 (Sistema Integrado de
Gestión Académica y Docente, UTN Sede San Carlos).

Los criterios de aceptación son la base de la evaluación funcional y de la
defensa oral: un requerimiento que no cumple su criterio de aceptación se
considera no implementado, aunque el código exista.

---

## Glosario / Lenguaje Ubicuo

| Término | Significado exacto en este proyecto |
|---|---|
| **Espacio** | Aula, laboratorio o auditorio inventariado. En la BD oficial la tabla se llama `aulas` (ver `business-rules.md`), pero el término de negocio es "espacio". |
| **Uso** | Cualquier ocupación de un espacio en un intervalo de tiempo: un grupo académico, una reserva puntual, o un bloqueo administrativo. |
| **Grilla única** | La vista semanal (lunes a domingo) de ocupación de un espacio, que integra los tres orígenes de uso con su etiqueta visual. |
| **Solape** | Que dos usos ocupen el mismo espacio en intervalos de tiempo que se cruzan, evaluado con la regla de intervalos semiabiertos. |
| **Intervalo semiabierto `[inicio, fin)`** | Un uso que termina a una hora X y otro que empieza exactamente a la hora X en el mismo espacio y día **no** constituyen solape. |
| **Rango preferente** | En AU-02, el rango de capacidad `[M, PISO(1.5×M)]` dentro del cual se busca primero un espacio para un grupo con matrícula M. |
| **Sobredimensionado** | Etiqueta que recibe un espacio sugerido cuando no existe ninguno dentro del rango preferente, pero sí existe uno con capacidad `C ≥ M`. |
| **Bloqueo administrativo** | Uno de los tres orígenes de ocupación de la grilla (junto a grupos académicos y reservas puntuales). |
| **Reserva puntual** | Uso no académico regular de un espacio (evento, convenio, tutoría, préstamo), gestionado por AU-06. |

---

## AU-01 — Inventario de Espacios y Grilla Única de Ocupación

**Prioridad:** Alta

**Descripción:** El sistema debe contener el inventario de todos los
espacios con: nombre/número, piso, capacidad máxima, recinto, tipo (aula
regular, laboratorio de cómputo, laboratorio de ciencias, laboratorio de
idiomas, auditorio, otro), equipamiento especial y disponibilidad por
intervalos horarios y día (lunes a domingo). La grilla de ocupación integra
tres orígenes visibles con su etiqueta: grupos académicos, reservas
puntuales (AU-06) y bloqueos administrativos. Un espacio puede marcarse "No
disponible" a partir de una fecha.

**Entradas:**
- Nombre/número, piso, capacidad máxima, recinto, tipo de espacio,
  equipamiento especial.
- Marcado opcional de "No disponible a partir de [fecha]".

**Flujo:**
1. El Administrador registra cada espacio con sus atributos.
2. El sistema construye, para cada espacio, una grilla semanal (lunes a
   domingo) que integra grupos académicos, reservas puntuales y bloqueos
   administrativos.
3. El Administrador puede marcar un espacio como "No disponible" a partir
   de una fecha específica.

**Salidas:**
- Listado de espacios filtrable por tipo y capacidad mínima.
- Grilla semanal por espacio: intervalos ocupados por grupos en un color
  con el nombre del grupo, reservas puntuales en otro color con el motivo,
  disponibles en blanco, y "No disponible a partir de [fecha]" en un tercer
  color.

**Criterios de aceptación:**
- Al filtrar por tipo y capacidad mínima, el sistema lista los espacios que
  cumplen, con la grilla semanal correctamente coloreada según el origen de
  cada ocupación.

---

## AU-02 — Sugerencia Automática de Aula

**Prioridad:** Alta

**Descripción:** Al confirmar un grupo con matrícula estimada M, el sistema
sugiere el espacio disponible en TODAS las sesiones del grupo, de menor
capacidad C, que cumpla `M ≤ C ≤ PISO(1.5 × M)` (rango preferente). Si el
curso requiere laboratorio, el filtro de tipo se aplica primero. Si no
existe espacio en el rango preferente, el sistema propone el espacio
disponible de menor capacidad que cumpla `C ≥ M`, con la etiqueta
"Sobredimensionado"; si no existe ninguno con `C ≥ M`, emite la alerta "No
hay espacios disponibles para este grupo en este horario".

**Adaptación de alcance:** la regla de desempate por "mismo recinto del
cuatrimestre anterior" del SRS original no aplica en este proyecto por no
existir historial entre cuatrimestres; en su lugar, en caso de empate de
capacidad dentro del rango preferente, el equipo debe definir y documentar
una regla determinista propia (por ejemplo, menor número de espacio).

> **Nota:** la regla propia adoptada por el equipo es: (1) menor piso,
> (2) en caso de persistir el empate, menor número/nombre de espacio. Ver
> el detalle y su justificación en `business-rules.md` → Regla 4. Lo
> anterior es texto literal del enunciado; esta nota es una aclaración
> nuestra, no parte del enunciado original.

**Entradas:**
- Matrícula estimada M del grupo.
- Sesiones del grupo (día, hora de inicio, hora de fin).
- Indicador de si el curso requiere laboratorio y de qué tipo.

**Flujo:**
1. El sistema filtra los espacios por tipo, si el curso requiere
   laboratorio.
2. Filtra los espacios disponibles (sin choque, ver AU-03) en TODAS las
   sesiones del grupo.
3. Calcula el rango preferente `[M, PISO(1.5×M)]` y elige, entre los
   disponibles, el de menor capacidad dentro de ese rango.
4. En empate de capacidad dentro del rango preferente, aplica la regla de
   desempate definida y documentada por el equipo.
5. Si no hay ningún espacio en el rango preferente, elige el de menor
   capacidad con `C ≥ M` y lo etiqueta "Sobredimensionado".
6. Si no existe ninguno con `C ≥ M`, muestra la alerta "No hay espacios
   disponibles para este grupo en este horario — redistribuya el horario o
   amplíe el inventario".

**Salidas:**
- Aula sugerida en estado "Sugerida — pendiente de confirmación", con sus
  intervalos bloqueados preventivamente.
- O bien, aula con etiqueta "Sobredimensionado".
- O bien, mensaje de alerta de indisponibilidad.

**Criterios de aceptación:**
- Grupo de 25 estudiantes sin laboratorio con un aula de 30 disponible en
  todas sus sesiones (`25 ≤ 30 ≤ PISO(37.5)=37`) → el sistema propone esa
  aula y bloquea los intervalos.
- Sin ningún aula con capacidad entre 25 y 37 → el sistema propone la de
  menor capacidad con `C ≥ 25`, etiquetada "Sobredimensionado".
- Sin ninguna aula con `C ≥ 25` → el sistema muestra la alerta de
  indisponibilidad.

---

## AU-03 — Bloqueo por Choque de Espacio

**Prioridad:** Alta

**Descripción:** El sistema debe impedir la asignación de un mismo espacio
a dos usos cuyos intervalos `[hora inicio, hora fin)` se solapen en el
mismo día, considerando como usos: grupos con asignación "Confirmada",
grupos con asignación "Sugerida — pendiente de confirmación" y reservas
puntuales aprobadas (AU-06). Ante un intento de doble asignación, el
sistema rechaza la operación mostrando el detalle del conflicto.

**Entradas:**
- Espacio, día, hora de inicio y hora de fin del nuevo uso que se intenta
  asignar.

**Flujo:**
1. El sistema recorre todos los usos ya registrados para ese espacio en
   ese día.
2. Para cada uso existente, evalúa si `[inicio_nuevo, fin_nuevo)` se
   solapa con `[inicio_existente, fin_existente)`, usando la regla de
   intervalos semiabiertos (un uso que termina a las 13:00 y otro que
   empieza a las 13:00 NO se consideran solapados).
3. Si hay solape, rechaza la operación indicando el uso existente en
   conflicto y su estado.
4. Si no hay solape, permite la asignación.

**Salidas:**
- Asignación confirmada, o mensaje de rechazo con el detalle del conflicto
  (espacio, día, horario existente, uso en conflicto, estado del uso).

**Criterios de aceptación:**
- Intento de asignar un aula con sesión lunes 18:00–21:59, cuando esa aula
  ya tiene una reserva aprobada el lunes de 19:00 a 20:30 → rechazo con el
  detalle del conflicto.
- Grupo que termina a las 13:00 y otro que inicia a las 13:00, el mismo
  día y la misma aula → NO se marca conflicto.

---

## AU-06 — Reservas Puntuales y Préstamos de Espacios

**Prioridad:** Alta

**Descripción:** El sistema debe permitir registrar reservas puntuales de
espacios para usos no académicos regulares (eventos, convenios, tutorías,
préstamos), con: solicitante, motivo, espacio, fecha o rango de fechas,
día(s) e intervalo horario, y estado (Solicitada, Aprobada, Rechazada,
Cancelada). La aprobación corresponde a un usuario con rol Coordinador. Al
aprobar, el sistema valida el no-solape contra la grilla única (AU-03) y
bloquea los intervalos correspondientes.

**Entradas:**
- Solicitante, motivo, espacio, fecha o rango de fechas, día(s), hora de
  inicio, hora de fin.

**Flujo:**
1. Cualquier usuario autenticado registra una reserva en estado
   "Solicitada".
2. El Coordinador revisa la lista de reservas solicitadas.
3. Al aprobar, el sistema ejecuta la validación de no-solape (AU-03)
   contra la grilla completa del espacio.
4. Si no hay solape, la reserva pasa a "Aprobada" y bloquea el intervalo
   en la grilla, visible con su motivo.
5. Si hay solape, el sistema rechaza la aprobación mostrando el conflicto
   detectado.

**Salidas:**
- Reserva en estado "Aprobada", visible en la grilla con su motivo.
- O bien, rechazo de la aprobación con el detalle del conflicto.

**Criterios de aceptación:**
- Reserva del auditorio para una fecha y horario libres → al aprobar,
  bloquea el intervalo y es visible en la grilla con su motivo.
- Reserva sobre un intervalo ya ocupado por un grupo o por otra reserva
  aprobada → rechazo mostrando el conflicto.

> Nota: en la base de datos oficial del profesor, "rol Coordinador" se
> valida mediante el permiso `reservas.gestionar` (RBAC), no un campo de
> rol fijo. Ver `business-rules.md`.
