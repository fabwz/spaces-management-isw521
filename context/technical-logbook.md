# Bitácora Técnica

Registro cronológico de decisiones técnicas, implementaciones importantes,
correcciones y consultas relevantes a la IA a lo largo del proyecto. Es el
entregable que cubre la Sección 3.d de la ficha del proyecto (Diario de
Decisiones Técnicas e IA, 10% de la nota final).

**Cuándo agregar una entrada:** cada vez que la IA cometa un error real
que se detecte y corrija, se tome una decisión técnica indispensable para
el proyecto, o se implemente algo importante cuyo razonamiento valga la
pena dejar registrado.

**Regla de autoría:** cada entrada lleva el nombre de quien la escribió.
Un diario sin autoría individual no cumple el rubro de la rúbrica que
exige reflexión de aprendizaje *por estudiante* — no por el equipo en
general. Cualquiera de los tres puede agregar entradas, en cualquier
momento del desarrollo.

---

## Plantilla de entrada (copiar para cada nueva entrada)

```markdown
## [Fecha] — [Título corto de la decisión/error/corrección]

**Autor:** [nombre]
**Contexto:** ¿Qué se estaba resolviendo? (ej. "implementar Intervalo VO para AU-03")
**Consulta a la IA:** ¿Qué se le preguntó, textualmente o resumido con fidelidad?
**Qué se aceptó:** ...
**Qué se rechazó y por qué:** ...
**Error detectado (si aplica):** ¿Qué propuso mal la IA? ¿Cómo se descubrió? ¿Cómo se corrigió?
**Aprendizaje:** ¿Qué aprendió [autor] específicamente de esto?
```

---

## Ejemplo (reemplazar por entradas reales del equipo)

## 2026-08-03 — Regla de desempate de capacidad en AU-02

**Autor:** [nombre de quien tomó la decisión]
**Contexto:** El enunciado exige que el equipo defina y documente su
propia regla determinista de desempate cuando dos espacios empatan en
capacidad dentro del rango preferente, ya que la regla original del SRS
("mismo recinto del cuatrimestre anterior") no aplica por falta de
historial.
**Consulta a la IA:** Se le pidió una recomendación de regla de desempate
acorde al contexto de negocio del proyecto, no solo una regla arbitraria.
**Qué se aceptó:** Regla en dos niveles — (1) menor piso, (2) si persiste
el empate, menor número/nombre de espacio — con justificación de negocio
(accesibilidad y menor desplazamiento).
**Qué se rechazó y por qué:** No se usó un criterio de un solo nivel
("menor número de espacio" a secas, sugerido como ejemplo en el
enunciado) porque no tiene relación con el negocio real y sería difícil
de justificar en la defensa oral.
**Error detectado:** N/A en esta entrada.
**Aprendizaje:** Una regla de desempate técnicamente válida no siempre es
la mejor opción — vale la pena que tenga una justificación de negocio
verificable, no solo que sea determinista.

---

*(Las entradas reales del equipo van debajo de esta línea, en orden
cronológico.)*

## 2026-08-04 — CHECK constraints no son un método nativo del Blueprint

**Autor: Luis Giovanni Sandi Azofeifa**
**Contexto:** Implementar la migración de `reservas_aulas`, que en el esquema oficial tiene dos `CHECK` constraints (`hora_fin > hora_inicio` y `fecha_fin IS NULL OR fecha_fin >= fecha_inicio`).
**Consulta a la IA:** Se le pidió generar la migración de Laravel replicando exactamente el esquema SQL oficial, incluyendo los CHECK.
**Qué se aceptó:** La estructura general de columnas, tipos y foreign keys generada por la IA.
**Qué se rechazó y por qué:** El uso de `$table->check(...)` dentro del closure de `Schema::create`, porque no existe como método del Blueprint de Laravel — el `php artisan migrate` falló con `BadMethodCallException: Method Illuminate\Database\Schema\Blueprint::check does not exist`.
**Error detectado:** La IA generó una sintaxis inventada asumiendo que el schema builder soporta CHECK constraints igual que otros motores. Se descubrió al correr la migración, y se corrigió moviendo ambos CHECK a `DB::statement('ALTER TABLE ... ADD CONSTRAINT ... CHECK (...)')` ejecutado después del `Schema::create`.
**Aprendizaje:** El schema builder de Laravel no cubre toda la sintaxis SQL — features menos comunes como CHECK constraints hay que agregarlas con SQL crudo vía `DB::statement()`, y conviene verificar la documentación oficial de Laravel en vez de asumir que el ORM soporta 1:1 lo que el motor de base de datos permite.

## 2026-08-04 — Construcción en dos etapas de Intervalo (domain-core vs au-03)

**Autor:** Fabián Zamora
**Contexto:** Al planear feature/crud-gestion-espacios (CRUD completo
pedido por el profesor para el Avance 1), surgió la duda de dónde deben
vivir las validaciones básicas de las Entidades (capacidad positiva,
hora_fin > hora_inicio) sin romper la separación de Arquitectura
Hexagonal, dado que domain-core se planeó sin Intervalo.
**Consulta a la IA:** Se le preguntó específicamente dónde deberían vivir
esas validaciones para las 4 entidades del CRUD (Espacio, Recinto,
Equipamiento, ReservaPuntual), antes de que exista la lógica de detección
de choques.
**Qué se aceptó:** Las invariantes básicas van en el constructor de cada
Entidad/VO en Domain/, nunca en el FormRequest (que solo valida forma de
los datos). Para ReservaPuntual, en vez de duplicar la validación de
horas inline y borrarla después, se construye Intervalo en dos etapas:
domain-core crea el VO con solo el constructor (inicio < fin);
au-03-choque-espacio lo extiende agregando seSolapaCon().
**Qué se rechazó y por qué:** El plan original (domain-core sin Intervalo
en absoluto) se descartó porque hubiera obligado a escribir la validación
de horas dos veces (una temporal en la Entidad, otra definitiva en
Intervalo) y luego recordar borrar la primera.
**Error detectado:** N/A — fue un refinamiento de diseño, no una
corrección de error.
**Aprendizaje:** Al planear ramas de trabajo por fases, conviene
preguntarse si una regla de negocio que "llega después" en el plan en
realidad ya es necesaria antes, aunque sea en su forma mínima — evita
trabajo duplicado y violaciones temporales de la regla de "un único
lugar" para la lógica de dominio.