# Manual técnico · ProtoAPIO V1

Sitio web de la Escuela Primaria N°16 «Dr. Pedro Goyena».

## 1. Qué es

Sitio institucional con un módulo de **noticias**. Un administrador publica, edita y borra noticias (con una imagen opcional) desde un panel; los visitantes registrados pueden comentar, editar o borrar sus comentarios y reportar los ajenos. El resto del sitio son páginas informativas (inicio, nuestra escuela, planta baja y primer piso).

## 2. Tecnologías y requisitos

- PHP con extensión **mysqli**, sin framework. HTML y CSS propios; JavaScript sólo para la vista previa de imágenes.
- MySQL o MariaDB, base de datos `escuela16`, motor InnoDB, codificación utf8mb4.
- Servidor web con PHP (por ejemplo XAMPP), con la carpeta `Noticias/imagenes/` con permiso de escritura.

## 3. Instalación

1. Copiar la carpeta `ProtoAPIO.V1` al directorio público del servidor (en XAMPP, `htdocs`).
2. Crear la base `escuela16` e importar `escuela16.sql` (trae el usuario administrador inicial). Las tablas que falten se crean solas la primera vez que se abre cualquier página.
3. Revisar `ConexionBD/conexion.php`: servidor `localhost`, usuario `root`, sin contraseña.
4. Abrir `http://localhost/ProtoAPIO.V1/index.php`.

## 4. Estructura de carpetas

| Carpeta / archivo | Contenido |
|---|---|
| `index.php`, `nuestraescuela.php` | Páginas públicas principales. |
| `P1/`, `P2/` | Páginas informativas de los pisos y sus aulas (HTML + CSS). |
| `Login/` | Ingreso de administrador y de comentaristas, registro, verificación por teléfono, recuperación de contraseña y cierre de sesión. |
| `Noticias/` | Listado público, panel de administración, alta, edición y baja de noticias, y comentarios (guardar, editar, eliminar, reportar). `imagenes/` guarda las fotos subidas. |
| `ConexionBD/` | Conexión, creación de tablas y funciones compartidas (`conexion.php`), envío de SMS (`verificacion.php`, `config_sms.php`). |
| `CSS/`, `imagenes/` | Hojas de estilo y recursos gráficos del sitio. |

## 5. Base de datos

| Tabla | Función |
|---|---|
| `usuarios` | Administradores (usuario, contraseña con hash, nombre). No hay pantalla de alta: se cargan en la tabla. |
| `usuarios_comentarios` | Cuentas de visitantes. Teléfono verificado único por cuenta. |
| `noticias` | id, título (máx. 200), contenido, fecha automática. |
| `imagenes_noticias` | Imagen de cada noticia (nombre y ruta). Se borra en cascada con la noticia. |
| `comentarios` | Comentarios por noticia y usuario, con marca de «editado». Cascada con noticia y usuario. |
| `reportes_comentarios` | Un reporte por persona y comentario (clave única). |
| `intentos_login` | Fallos de ingreso por IP y tipo, para el bloqueo temporal. |

## 6. Sesiones y permisos

Hay dos tipos de sesión PHP que no conviven: **administrador** (`$_SESSION["usuario"]`) y **comentarista** (`comentario_nombre` y afines). Al entrar con una se cierra la otra, y el identificador de sesión se regenera al ingresar.

- Las páginas del panel y del alta, edición y baja de noticias exigen sesión de administrador; sin ella redirigen a `Login/acceso_admin.php`.
- El ingreso de administrador no está enlazado en ningún menú: se escribe la dirección `/Login/acceso_admin.php` o se hacen 5 clics rápidos sobre el logo del inicio.
- Comentar, editar, borrar y reportar exige sesión de comentarista. Sólo se editan o borran los comentarios propios; no se reportan los propios.

## 7. Flujos principales

- **Noticias:** `panel.php` (formulario y tabla) y luego `guardar_noticia.php`, `editarnoticias.php`, `eliminarnoticias.php`. Se valida título y contenido no vacíos, título de hasta 200 caracteres e imagen JPG, PNG o WEBP real de hasta 5 MB, antes de guardar.
- **Registro:** el visitante da su teléfono en formato internacional y recibe un código de 6 dígitos por SMS. Hasta verificarlo no puede ingresar. Un número verificado no se repite.
- **Recuperar contraseña:** código por SMS al teléfono verificado y elección de contraseña nueva.
- **Reportes:** con 5 reportes de personas distintas, el comentario se elimina solo (constante `REPORTES_PARA_ELIMINAR` en `conexion.php`).

## 8. Seguridad implementada

- Consultas preparadas (`prepare` / `bind_param`) en las operaciones sobre datos de usuarios y noticias.
- Contraseñas con `password_hash`; las antiguas en texto plano se convierten al primer ingreso correcto.
- Salida escapada con `htmlspecialchars`. Token anti-CSRF en los enlaces que borran o reportan.
- Máximo de 5 intentos fallidos cada 15 minutos por IP. Códigos SMS: vencen a los 10 minutos, 5 intentos por código, 1 por minuto por cuenta, 3 cada 10 minutos por número y 5 por hora por cuenta.
- Comentarios de hasta 1000 caracteres, validados en formulario y en servidor.

## 9. Antes de publicar en internet

- **Cambiar la contraseña del administrador** (el dump trae `admin` / `1234`).
- Reemplazar el usuario `root` sin contraseña en `conexion.php` por un usuario propio de MySQL.
- En `ConexionBD/config_sms.php`, poner `SMS_MODO_PRUEBA` en `false` y cargar las credenciales de Twilio (`TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_FROM`). En modo prueba no se envía SMS y el código aparece en pantalla. El envío real tiene costo.

## 10. Limitaciones conocidas

- No hay buscador de noticias ni paginación en el listado.
- Los errores de validación del panel muestran una página aparte con enlace «Volver»; el formulario no conserva lo escrito.
- No hay alta ni gestión de administradores desde el sistema.
