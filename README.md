# ProtoAPIO V1

Sitio web de la **Escuela Primaria N°16 «Dr. Pedro Goyena»**, con un módulo de noticias administrable y comentarios de visitantes.

## Funciones

- **Páginas informativas:** inicio, nuestra escuela, planta baja y primer piso con sus aulas.
- **Noticias:** el administrador publica, edita y elimina noticias con imagen opcional (JPG, PNG o WEBP de hasta 5 MB).
- **Comentarios:** los visitantes registrados comentan, editan o borran sus comentarios y reportan los ajenos. Con 5 reportes de personas distintas, el comentario se elimina solo.
- **Registro con verificación por SMS:** código de 6 dígitos al teléfono, también usado para recuperar la contraseña.

## Tecnologías

- PHP (mysqli), sin framework
- MySQL / MariaDB
- HTML, CSS y un poco de JavaScript
- Twilio para el envío de SMS (con modo de prueba)

## Instalación

1. Copiar la carpeta `ProtoAPIO.V1` al directorio público del servidor (en XAMPP, `htdocs`).
2. Crear la base de datos `escuela16` e importar `escuela16.sql`. Las tablas que falten se crean solas al abrir cualquier página.
3. Revisar los datos de conexión en `ConexionBD/conexion.php` (por defecto: `localhost`, usuario `root`, sin contraseña).
4. Dar permiso de escritura a `Noticias/imagenes/`.
5. Abrir `http://localhost/ProtoAPIO.V1/index.php`.

## Uso

| Qué | Dónde |
|---|---|
| Inicio | `/index.php` |
| Listado de noticias | `/Noticias/noticias.php` |
| Ingreso de administrador | `/Login/acceso_admin.php` (no está en ningún menú: se escribe la dirección) |
| Panel de administración | `/Noticias/panel.php` |
| Ingreso de comentaristas | `/Login/logeo_comentario.php` |

El dump trae un administrador inicial (`admin` / `1234`). **Cambiar esa contraseña antes de publicar el sitio.**

## Estructura

```
ProtoAPIO.V1/
├── index.php, nuestraescuela.php   Páginas públicas
├── P1/, P2/                        Pisos y aulas
├── Login/                          Ingreso, registro, verificación, recuperación
├── Noticias/                       Listado, panel, alta/edición/baja, comentarios
│   └── imagenes/                   Fotos subidas
├── ConexionBD/                     Conexión, tablas, funciones y envío de SMS
├── CSS/, imagenes/                 Estilos y recursos
└── escuela16.sql                   Volcado de la base de datos
```

## Configuración para producción

- Cambiar la contraseña del administrador.
- Usar un usuario propio de MySQL en lugar de `root` sin contraseña.
- En `ConexionBD/config_sms.php`, poner `SMS_MODO_PRUEBA` en `false` y cargar `TWILIO_SID`, `TWILIO_TOKEN` y `TWILIO_FROM`. En modo prueba no se envía SMS y el código aparece en pantalla.

## Seguridad

Consultas preparadas, contraseñas con `password_hash`, salida escapada con `htmlspecialchars`, token anti-CSRF en acciones que borran o reportan, bloqueo tras 5 intentos de ingreso fallidos en 15 minutos y límites de envío de códigos SMS.

## Limitaciones conocidas

- No hay buscador de noticias ni paginación.
- Los errores de validación del panel llevan a una página de aviso; el formulario no conserva lo escrito.
- Los administradores se cargan directamente en la base: no hay pantalla de alta.

## Documentación

- [Manual técnico](manual_tecnico.md)
- [Casos de prueba](pruebas.md)

## Autores

Completar con los integrantes del equipo.
