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
