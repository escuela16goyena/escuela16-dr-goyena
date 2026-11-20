# Cambios y correcciones — ProtoAPIO V1

## Errores corregidos
1. **Noticias/editar_comentario.php** — faltaba `$stmt = $conn->prepare(` (error de sintaxis: la página no cargaba).
2. **Login/login.php** — consulta preparada (antes: inyección SQL), contraseña con hash. Si la contraseña guardada está en texto plano (ej. `1234`), se convierte a hash en el primer ingreso correcto.
3. **Login/logeo.php** — ahora muestra "Usuario o contraseña incorrectos" en la misma pantalla (antes: texto suelto en blanco).
4. **Noticias/eliminarnoticias.php** — id validado, consulta preparada, token anti-CSRF y borrado del archivo de imagen del disco.
5. **Noticias/eliminar_comentario.php, noticias.php, panel.php** — enlaces de eliminar con token anti-CSRF.
6. **Noticias/guardar_noticia.php** — valida título/contenido vacíos, valida la imagen antes de publicar (tipo real, 5 MB), y si algo falla no deja la noticia a medias.
7. **Noticias/editarnoticias.php** — misma validación que al publicar (título vacío, imagen real) antes de modificar la noticia.
8. **ConexionBD/conexion.php** — crea todas las tablas en orden, agrega la columna `editado` si falta, usa utf8mb4 y ya no muestra datos internos si falla la conexión. Incluye funciones compartidas (token CSRF, validación de imagen, página de error).
9. **Sesiones** — `session_regenerate_id()` al iniciar sesión (login y registro).
10. **Comentarios** — límite de 1000 caracteres (formulario y servidor).
11. **P2/aulaciencia.html** — enlaces del menú corregidos (`../`) y quitadas dos imágenes vacías (`src="#"`).
12. **P1/aulas.html** — atributos mal escritos en el menú. **P2/aulatecnologia.html** e **index.php** — textos alternativos de imágenes corregidos.

## Pendiente / recomendado
- Cambiar la contraseña del administrador (el dump trae `admin` / `1234`).
- Cambiar el usuario `root` sin contraseña de conexion.php por un usuario propio al publicar online.
- Agregar la carpeta `imagenes/` (logo y fotos), que no venía en el .rar.

---

# Novedades: reportes de comentarios y verificación de teléfono

## Sistema de reportes
- Cada usuario con sesión puede presionar **Reportar** en comentarios ajenos (no en los propios).
- Una persona solo puede reportar un mismo comentario una vez.
- Al llegar a **5 reportes de personas distintas** el comentario se elimina automáticamente (el número se cambia en `REPORTES_PARA_ELIMINAR`, dentro de `ConexionBD/conexion.php`).
- Archivos: `Noticias/reportar_comentario.php`, tabla nueva `reportes_comentarios`, cambios en `noticias.php`.

## Verificación por teléfono (una cuenta por número)
- El registro pide el teléfono en formato internacional (ej. `+54 9 223 1234567`) y envía un código de 6 dígitos por SMS (vence a los 10 minutos, máximo 5 intentos).
- Hasta verificar el número, la cuenta no puede iniciar sesión para comentar.
- Un número verificado no puede repetirse: la columna `telefono_unico` tiene índice UNIQUE y solo se completa al verificar (así nadie puede "bloquear" un número ajeno).
- Las cuentas ya existentes deben verificar su teléfono la próxima vez que inicien sesión.
- Límites: 1 código por minuto por cuenta y 3 cada 10 minutos por número.
- Archivos: `Login/telefono.php`, `enviar_codigo.php`, `confirmar_codigo.php`, `ConexionBD/verificacion.php`, `ConexionBD/config_sms.php`.
- Las columnas y tablas nuevas se crean solas la primera vez que se abre cualquier página.

## IMPORTANTE: SMS reales
`ConexionBD/config_sms.php` viene con `SMS_MODO_PRUEBA = true`: **no se envía ningún SMS** y el código aparece en pantalla (solo sirve para probar). Para publicar el sitio hay que poner `false` y cargar las credenciales de Twilio (`TWILIO_SID`, `TWILIO_TOKEN`, `TWILIO_FROM`). El envío de SMS tiene costo.

## Acceso de administrador oculto
- `Login/logeo.php` → renombrado a `Login/acceso_admin.php` (no está enlazado en ningún menú, con `noindex`). `Login/logeo.php` ahora solo redirige al login público.
- El botón "Iniciar Sesión" del inicio lleva al login de comentarios.
- Acceso del admin: escribir la URL `/Login/acceso_admin.php` o hacer 5 clics rápidos sobre el logo en el inicio.

## Recuperar contraseña por SMS + correcciones
- **Olvidé mi contraseña** (`Login/recuperar.php`, `recuperar_enviar.php`, `recuperar_confirmar.php`): se ingresa el usuario, llega un código de 6 dígitos al teléfono verificado de la cuenta (vence en 10 min, 5 intentos) y se elige una contraseña nueva. Si el usuario no existe o no tiene teléfono verificado, se avisa con un mensaje claro.
- **Límite de intentos fallidos** (5 cada 15 minutos por IP) en login de administrador, login de comentarios y recuperación. Tabla nueva `intentos_login` (se crea sola).
- **Tope de SMS**: máximo 5 códigos por hora por cuenta (columnas nuevas `codigo_envios` y `codigo_ventana`, se agregan solas).
- **Cambiar número durante la verificación** ya no obliga a esperar 60 s ni deja a la vista un código viejo.
- **Registro**: valida largos máximos y ya no da error fatal si dos personas eligen el mismo usuario a la vez.
- **Sesiones**: entrar como admin cierra la sesión de comentarios y al revés (antes se mezclaban).
- **editarnoticias.php**: errores técnicos crudos reemplazados por mensajes claros.
