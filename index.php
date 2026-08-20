<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escuela Primaria N°16</title>

  <link rel="stylesheet" href="CSS/styles.css">
</head>

<body>

  <header>
    <div class="contenedor header">

      <div class="logo">
        <img src="imagenes/logoescuela.png">

        <div>
          <h2>ESCUELA PRIMARIA N°16</h2>
          <p>Dr. Pedro Goyena</p>
        </div>
      </div>

      <nav>
        <a href="index.php" class="botonnav">Inicio</a>
        <a href="nuestraescuela.php" class="botonnav">Nuestra Escuela</a>

        <?php if(isset($_SESSION['usuario'])): ?>

          <a href="Login/logout.php" class="botonnav">Cerrar sesión</a>

        <?php else: ?>

          <a href="Login/logeo.php" class="botonnav">Iniciar Sesión</a>

        <?php endif; ?>

      </nav>

    </div>
  </header>

  <section class="inicio">

    <div class="contenedor inicio-grid">

      <div class="texto">
        <h1>
          Bienvenidos a la <br>
          Escuela Primaria N°16 <br>
          Dr. Pedro Goyena
        </h1>

        <p>
          Formamos estudiantes con valores, respeto y compromiso
          para construir un mejor futuro.
        </p>
<div class="botonescentro">
        <a href="nuestraescuela.php" class="boton">Ver más</a>
        <a href="Noticias/noticias.php" class="boton">Noticias</a>
</div>


      </div>

      <div class="imagen">
        <img src="imagenes/escuela.jpeg" alt="Escuela">
      </div>

    </div>

  </section>

  <section class="info">

    <div class="contenedor">

      <h2>Nuestra Escuela</h2>

      <p class="descripcion">
        Somos una escuela pública ubicada en Mar del Plata.
      </p>

      <div class="cards">

        <div class="card">
          <h3>Acompañamiento</h3>
          <p>Docentes comprometidos con cada estudiante.</p>
        </div>

        <div class="card">
          <h3>Valores</h3>
          <p>Promovemos el respeto y la solidaridad.</p>
        </div>

        <div class="card">
          <h3>Educación</h3>
          <p>Brindamos educación pública y de calidad.</p>
        </div>

      </div>

    </div>

  </section>

  <section class="materias">

    <div class="contenedor">

      <h2>Propuesta Educativa</h2>

      <div class="lista-materias">
        <span>Lengua</span>
        <span>Matemática</span>
        <span>Ciencias Naturales</span>
        <span>Ciencias Sociales</span>
        <span>Arte y Música</span>
        <span>Educación Física</span>
      </div>

    </div>

  </section>

  <footer>

    <div class="contenedor footer">

      <div>
        <p>Mar del Plata, Buenos Aires</p>
        <p>(0223) 123-4567</p>
        <p>escuela16mdp@gmail.com</p>
      </div>

    </div>

  </footer>

</body>
</html>