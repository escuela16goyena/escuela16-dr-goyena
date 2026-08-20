# Sugerencias — Escuela 16 Dr. Goyena

Revisión de la profesora. El proyecto ya recorre el ciclo completo —login, noticias, base de datos— y eso es un logro. Los puntos que siguen son oportunidades para llevarlo un paso más, aplicando conceptos que ya vimos en clase. La consigna es resolverlos aplicando lo aprendido, no copiando un código hecho.

## Fortalezas

- **CSS en archivo aparte** y carpetas por función (`Login/`, `Noticias/`, `ConexionBD/`) — Clase 2.
- **Login con sesión y páginas protegidas**: el panel, guardar, editar y eliminar verifican la sesión antes de actuar — Clase 2.
- **CRUD completo de noticias**: listar, crear, editar con el `id` viajando por la URL y borrar — Clase 9.

## Puntos de mejora

1. **Consultas preparadas — Clase 8**

   Hoy las consultas arman el SQL con los datos tal como llegan del usuario. Pasarlas a sentencias preparadas las vuelve sólidas: un apóstrofo deja de romper la consulta y la base queda protegida ante inyección SQL. Es el cierre de la Clase 8, con `$stmt` y los `?`.

2. **Credenciales fuera del repo — Clase 8**

   El archivo de conexión está subido con el usuario y la contraseña de MySQL. Un `.gitignore` mantiene esa llave en la máquina de cada integrante y fuera de GitHub. Es el segundo punto del TP de la Clase 8.

3. **Borrado con confirmación — Clase 9**

   Eliminar con un solo clic es arriesgado: un error y la noticia desaparece. El borrado en dos pasos con confirmación de la Clase 9 protege el dato y deja la decisión en manos del usuario.

4. **Relación entre tablas — Clase 7**

   `usuarios` y `noticias` conviven sin vínculo. Conectar quién publica cada noticia mediante una clave foránea completa el modelo de datos: la relación uno-a-muchos de la Clase 7.

5. **Commits por etapa — Clase 3**

   Commits por avance con mensajes que digan qué cambió dejan un historial que documenta el crecimiento del proyecto, tal como se trabajó en la Clase 3.

6. **Esquema de la base versionado — Clase 5**

   Subir el `.sql` con el `CREATE TABLE` de las dos tablas: el repo pasa a guardar también la estructura de la base, como pedía el TP de la Clase 5.

## Ajustes finos

- En `Noticias/noticias.php` hay un `<<div` con un `<` de más.
- Las operaciones redirigen sin aviso; el aviso después de cada operación (Clase 9) deja al usuario al tanto de lo que pasó.
- El encabezado está repetido en cada página; extraerlo a un archivo con `include`, como ya hacen con la conexión, evita duplicarlo.
- La contraseña guardada en texto plano queda como desafío, más allá de lo visto en la materia.
