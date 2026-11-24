# Escuela Primaria N°16 «Dr. Pedro Goyena»

## Manual del administrador del sitio web (ProtoAPIO V1): cómo funciona la página y cómo administrarla

| | |
|---|---|
| **Qué es** | Guía para quien administra el sitio: publicar noticias, moderar comentarios y mantener el sistema |
| **Tecnologías** | HTML · CSS · PHP 8 · MySQL/MariaDB (mysqli) · JavaScript |
| **Base de datos** | `escuela16` (7 tablas) |
| **Qué vas a aprender** | Entrar al panel · publicar y editar noticias · moderar · configurar SMS · instalar y hacer copias de seguridad |
| **Versión** | Octubre de 2026 |

---

## 1. De qué trata el sitio

El sitio de la Escuela Primaria N°16 tiene dos partes bien distintas:

- **Parte institucional (fija):** el inicio, «Nuestra Escuela» y un recorrido por cada piso (biblioteca, orientación, aulas, ciencias y tecnología). Son páginas HTML y CSS casi sin lógica.
- **Parte dinámica (con base de datos):** el sistema de noticias. El administrador publica noticias (con una imagen opcional) desde el panel, y las personas registradas pueden comentarlas. Acá está toda la programación PHP.

### Quién puede hacer qué

| Rol | Cómo se identifica | Qué puede hacer |
|---|---|---|
| Visitante | Sin sesión | Ver el inicio, Nuestra Escuela, pisos, aulas y noticias con sus comentarios. No puede comentar. |
| Usuario comentarista | Se registra e inicia sesión en la cuenta de comentarios | Todo lo del visitante, más comentar, editar y eliminar sus propios comentarios y reportar los de otros. |
| Administrador | Entra por el acceso oculto (sección 3) | Publicar, editar y eliminar noticias desde el panel. Eliminar cualquier comentario. No comenta. |

> **Dato clave:** hay dos sistemas de cuentas separados. La del administrador (tabla `usuarios`) y las de comentarios (tabla `usuarios_comentarios`) no se mezclan. Solo hay una sesión abierta a la vez: al ingresar como administrador se cierra la de comentarios, y al revés.

## 2. Cómo está armado el sitio

El sitio está hecho en PHP 8 con una base de datos MySQL/MariaDB llamada `escuela16`. Se instala en un servidor web (por ejemplo XAMPP en una computadora, o un hosting).

### Estructura de carpetas

```
ProtoAPIO.V1/
├── index.php              Página de inicio
├── nuestraescuela.php     Historia e identidad de la escuela
├── ConexionBD/
│   ├── conexion.php       Conexión a la base, tablas y funciones comunes
│   ├── verificacion.php   Envío y control de códigos por SMS
│   └── config_sms.php     Modo de prueba o datos de Twilio
├── Login/                 Registro, ingresos, verificación y recuperación
├── Noticias/              Noticias, comentarios y panel de administración
│   └── imagenes/          Fotos subidas a las noticias
├── P1/  P2/               Páginas fijas del primer y segundo piso
└── imagenes/  CSS/        Fotos y hojas de estilo del sitio
```

### Mapa de páginas

| Página | Dirección | Quién entra |
|---|---|---|
| Inicio | `index.php` | Todos |
| Nuestra Escuela | `nuestraescuela.php` | Todos |
| Pisos y aulas | `P1/` y `P2/` | Todos |
| Noticias | `Noticias/noticias.php` | Todos |
| Registro / login de comentarios | `Login/registro.php` · `Login/logeo_comentario.php` | Todos |
| Recuperar contraseña | `Login/recuperar.php` | Todos |
| Acceso de administración | `Login/acceso_admin.php` | Solo quien conoce la dirección |
| Panel y edición de noticias | `Noticias/panel.php` · `Noticias/editarnoticias.php` | Solo administrador |

### Cómo funciona una acción

Los archivos `.php` siguen un patrón simple: una página muestra un formulario (por ejemplo `panel.php`) y otro archivo recibe los datos y los procesa (por ejemplo `guardar_noticia.php`). Al terminar, redirige a otra página. Así, si alguien recarga, no se reenvía el formulario por accidente.

```
Formulario                →  Archivo de acción            →  Redirección
El usuario completa          Valida y guarda en la base      Vuelve a la página de origen
y envía
```

El sitio «recuerda» quién sos con sesiones de PHP:

| Variable de sesión | La crea | Significa |
|---|---|---|
| `$_SESSION["usuario"]` | `Login/login.php` | Hay un administrador conectado. |
| `$_SESSION["comentario_usuario_id"]` | `login_comentario.php` y `confirmar_codigo.php` | Hay un comentarista conectado. |
| `$_SESSION["verificando_usuario_id"]` | `login_comentario.php` y `guardar_registro.php` | Cuenta pendiente de verificar el teléfono. |
| `session_destroy()` | `Login/logout.php` | Cierra la sesión y vuelve al inicio. |

## 3. Acceso del administrador

El ingreso del administrador está oculto: el menú público solo ofrece el login de comentarios. Hay dos formas de llegar al acceso de administración:

- **Opción A:** escribir la dirección oculta en el navegador.
- **Opción B:** 5 clics rápidos sobre el logo del Inicio.

Después: **Ingresar** (usuario y contraseña) → **Noticias → Publicar noticia** → se abre el panel.

1. Guardá la dirección en favoritos. Los 5 clics deben darse con menos de 2,5 segundos entre uno y otro.
2. Escribí el usuario y la contraseña de administrador y tocá **Ingresar**. Te lleva al Inicio.
3. Entrá a **Noticias**: abajo aparece el botón **Publicar noticia**, que abre el panel (también podés escribir `Noticias/panel.php`).
4. Para salir, tocá **Cerrar sesión**.

> **Importante:** la base trae un administrador de ejemplo: usuario `admin`, contraseña `1234`. Cambiala antes de publicar el sitio (sección 10). Ocultar la entrada no reemplaza a la contraseña: la seguridad real depende de que sea larga y privada.

Después de 5 intentos fallidos desde la misma conexión, el acceso se bloquea 15 minutos. Con la sesión de administrador abierta, en Noticias se lee «Conectado como administrador».

## 4. Publicar una noticia

El panel tiene arriba el menú (Inicio, Ver noticias, Cerrar sesión), luego el formulario para publicar y abajo la tabla de noticias publicadas.

1. Escribí el **título** (hasta 200 caracteres).
2. Escribí el **contenido**. Los saltos de línea se respetan; el texto se muestra tal cual, sin interpretar formatos ni HTML.
3. Si querés, elegí una **imagen**: aparece una vista previa para confirmarla.
4. Tocá **Publicar noticia**. Aparece en Noticias, en primer lugar, con fecha y hora.

### Reglas de las imágenes

| Regla | Detalle |
|---|---|
| Formatos | JPG, PNG y WEBP. |
| Tamaño máximo | 5 MB. Si pesa más, reducila antes con el programa de fotos del celular o la computadora. |
| Cantidad | Una sola imagen por noticia. |
| Control | El sitio revisa que el archivo sea realmente una imagen. Si algo falla, avisa y no publica la noticia a medias. |
| Dónde se guardan | `Noticias/imagenes/`, con un nombre automático. |

## 5. Editar y eliminar noticias

En la tabla **Noticias publicadas** del panel, cada fila tiene las acciones **Editar** y **Eliminar**.

### Editar

1. Tocá **Editar** en la noticia que querés cambiar.
2. Modificá el título o el contenido.
3. Imagen: dejarla como está, tildar **Eliminar esta imagen**, o elegir un archivo en **Cambiar imagen** para reemplazarla.
4. Tocá **Guardar Cambios**. Con **Volver al Panel** salís sin guardar.

La fecha original no cambia al editar, así que la noticia mantiene su lugar en la lista.

### Eliminar

> **Cuidado:** Eliminar pide confirmación y borra la noticia, su imagen y todos sus comentarios. No se puede deshacer. Para corregir un error conviene editar, así no se pierden los comentarios.

## 6. Moderar comentarios

Con la sesión de administrador abierta, en Noticias aparece el botón **Eliminar** en todos los comentarios, no solo en los propios. Se pide confirmación y el comentario se borra al instante. El administrador no escribe, edita ni reporta comentarios; para dar un aviso público, publicá una noticia.

### Moderación automática por reportes

```
Una persona reporta  →  Se suman reportes        →  Llega a 5
(una vez por comentario)  (de personas distintas)    El sitio lo elimina solo
```

El número está en `ConexionBD/conexion.php`, en la línea `define("REPORTES_PARA_ELIMINAR", 5);`. Cambiá el 5 si querés exigir más o menos reportes.

**Si una persona molesta de forma repetida:** eliminar sus comentarios no le impide volver a escribir. Se puede borrar su cuenta desde la base de datos (sección 10); así se borran también todos sus comentarios.

## 7. Cuentas de comentarios y SMS

Para comentar, el público crea una cuenta con nombre, usuario, contraseña y teléfono. El teléfono se verifica con un código de 6 dígitos por SMS. Sirve para que haya una cuenta por número y para recuperar la contraseña.

### Reglas del sistema

| Regla | Valor |
|---|---|
| Cuentas por número verificado | Una sola |
| Vencimiento del código | 10 minutos |
| Intentos para escribir el código | 5 |
| Espera entre un SMS y otro | 1 minuto, con tope de 5 por hora por cuenta |
| Antes de verificar | La cuenta no puede comentar |
| Recuperar contraseña | Mismo código por SMS, solo cuentas con teléfono verificado |

### Modo de prueba y SMS reales

La configuración está en `ConexionBD/config_sms.php`.

| Modo | Valor | Qué pasa |
|---|---|---|
| Prueba (viene así) | `SMS_MODO_PRUEBA = true` | No se envía ningún SMS. El código aparece en pantalla para poder probar. |
| Real | `SMS_MODO_PRUEBA = false` | Se envían SMS reales a través de Twilio. |

1. Creá una cuenta en Twilio (twilio.com) y obtené un número emisor.
2. En `config_sms.php` completá `TWILIO_SID`, `TWILIO_TOKEN` y `TWILIO_FROM` (formato `+15551234567`).
3. Cambiá `SMS_MODO_PRUEBA` a `false` y probá con tu propio teléfono antes de avisar a las familias.

> **Antes de publicar:** cada SMS real tiene costo y el servidor necesita la extensión curl de PHP. No publiques el sitio en modo de prueba: cualquiera vería los códigos en pantalla y la verificación no serviría.

## 8. La base de datos `escuela16`

Relaciones (de 1 a N):

```
noticias ──< imagenes_noticias
noticias ──< comentarios ──< reportes_comentarios
usuarios_comentarios ──< comentarios
usuarios_comentarios ──< reportes_comentarios

usuarios (admin)   sin relación con las demás tablas
intentos_login     sin relación con las demás tablas
```

| Tabla | Qué guarda | Columnas principales |
|---|---|---|
| `noticias` | Cada noticia publicada | id, titulo (200), contenido, fecha automática |
| `imagenes_noticias` | La imagen de una noticia | id, noticia_id, nombre original, ruta |
| `usuarios_comentarios` | Cuentas del público | id, nombre, usuario (único), contrasena (hash), telefono, telefono_unico, telefono_verificado, columnas del código SMS |
| `comentarios` | Cada comentario | id, noticia_id, usuario_id, comentario, fecha, editado (0/1) |
| `reportes_comentarios` | Un reporte por persona y comentario | id, comentario_id, usuario_id, fecha |
| `usuarios` | Cuenta del administrador | id, usuario, contrasena, nombre |
| `intentos_login` | Intentos fallidos de ingreso | id, tipo, ip, fecha |

### Borrado en cascada

- Al borrar una noticia se borran su imagen y todos sus comentarios.
- Al borrar un comentario se borran sus reportes.
- Al borrar una cuenta de comentarios se borran sus comentarios y reportes.
- La tabla `usuarios` (admin) y `intentos_login` no se relacionan con las demás.

## 9. Seguridad y límites automáticos

El sitio ya trae protecciones que funcionan solas.

| Protección | Cómo funciona |
|---|---|
| Contraseñas cifradas | Se guardan con hash, nunca en texto legible. |
| Intentos fallidos | 5 fallos cada 15 minutos por conexión bloquean el ingreso de administrador, el de comentarios y la recuperación. |
| Límite de SMS | 1 por minuto y 5 por hora por cuenta, para que nadie llene de mensajes un teléfono. |
| Acciones de borrado | Usan un código de seguridad de la sesión, para que un enlace ajeno no borre cosas. |
| Imágenes | Solo JPG, PNG y WEBP de hasta 5 MB, revisadas por su contenido real. |
| Textos | Lo que escribe el público se muestra como texto, sin ejecutar código. |
| Base de datos | Todas las consultas están protegidas contra inyección SQL. |

### Lo que tiene que hacer el administrador

- Cambiar la contraseña `1234` del administrador antes de publicar.
- Poner `SMS_MODO_PRUEBA` en `false` y configurar Twilio.
- Cambiar el acceso a la base: `conexion.php` usa el usuario `root` sin contraseña, válido solo para pruebas en una computadora. En un hosting, creá un usuario propio con contraseña y escribilo ahí.
- Usar HTTPS (candado en el navegador) para que las contraseñas no viajen sin cifrar.
- Hacer copias de seguridad periódicas (sección 10).

## 10. Instalación y mantenimiento

Estas tareas se hacen en phpMyAdmin (viene con XAMPP y con la mayoría de los hostings). Elegí la base `escuela16` y la pestaña **SQL**.

### Instalar con XAMPP

1. Copiá la carpeta `ProtoAPIO.V1` dentro de `htdocs`.
2. En el panel de XAMPP, iniciá **Apache** y **MySQL**.
3. En phpMyAdmin, andá a **Importar** y subí `escuela16_completa.sql`. Es seguro importarlo más de una vez: no borra datos.
4. Abrí `http://localhost/ProtoAPIO.V1/`.

Hace falta PHP 8 o superior con las extensiones `mysqli` y `curl`. La carpeta `Noticias/imagenes` tiene que poder escribirse para subir fotos.

### Cambiar la contraseña del administrador

```sql
UPDATE usuarios SET contrasena = 'LaNuevaClaveAqui' WHERE usuario = 'admin';
```

En el próximo ingreso el sitio convierte esa clave en hash y deja de guardarla legible. Hasta ese primer ingreso queda como texto en la base, así que ingresá enseguida.

### Liberar el teléfono de una cuenta

Sirve para probar varias cuentas con un mismo número o si una familia cambió de teléfono. La cuenta vuelve a pedir verificación.

```sql
UPDATE usuarios_comentarios
SET telefono = NULL, telefono_unico = NULL, telefono_verificado = 0,
    codigo_hash = NULL, codigo_expira = NULL, codigo_enviado = NULL, codigo_intentos = 0
WHERE usuario = 'nombre_de_usuario';
```

### Borrar una cuenta y desbloquear ingresos

```sql
-- Borra la cuenta, sus comentarios y sus reportes
DELETE FROM usuarios_comentarios WHERE usuario = 'nombre_de_usuario';

-- Levanta los bloqueos por intentos fallidos (se levantan solos a los 15 min)
DELETE FROM intentos_login;
```

### Copias de seguridad

1. En phpMyAdmin, elegí la base `escuela16`, andá a **Exportar** y descargá el archivo `.sql`.
2. Copiá también la carpeta `Noticias/imagenes`, que tiene las fotos de las noticias.
3. Guardá ambas cosas en un lugar seguro. Para restaurar, importá el `.sql` y copiá de nuevo las imágenes.

> **Recomendación:** hacé una copia antes de cada cambio grande y una vez por mes como mínimo.

## 11. Problemas frecuentes

| Problema | Causa probable | Solución |
|---|---|---|
| No encuentro el login de administrador | La entrada está oculta a propósito. | Escribí `Login/acceso_admin.php` en la dirección o hacé 5 clics rápidos en el logo del Inicio. |
| «Usuario o contraseña incorrectos» | Datos mal escritos. | Revisá mayúsculas y minúsculas. Si olvidaste la clave, cambiala por SQL (sección 10). |
| «Demasiados intentos fallidos» | 5 intentos incorrectos en 15 minutos. | Esperá 15 minutos o limpiá `intentos_login`. |
| «Error de conexión con la base de datos» | MySQL apagado o datos de conexión incorrectos. | Iniciá MySQL en XAMPP o corregí `ConexionBD/conexion.php`. |
| No se puede subir una imagen | Formato no permitido, más de 5 MB o carpeta sin permiso de escritura. | Usá JPG, PNG o WEBP liviano y revisá los permisos de `Noticias/imagenes`. |
| La noticia no se publicó | Falló la imagen o el título supera 200 caracteres. | Corregilo y publicá de nuevo: el sitio no deja noticias a medias. |
| La imagen no se ve | Se borró el archivo de la carpeta de imágenes. | Editá la noticia y subí la imagen otra vez. |
| No llegan los SMS | Sigue el modo de prueba o Twilio mal configurado. | Revisá `config_sms.php` (sección 7). |
| Una familia no puede verificar su número | Ese número ya está en otra cuenta. | Liberá el teléfono de la cuenta anterior o borrala (sección 10). |
| No puedo borrar un comentario | La sesión de administrador venció. | Ingresá de nuevo y repetí. |
