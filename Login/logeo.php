<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <header>
    <div class="contenedor header">

      <div class="logo">
        <img src="../imagenes/logoescuela.png">

        <div>
          <h2>ESCUELA PRIMARIA N°16</h2>
          <p>Dr. Pedro Goyena</p>
        </div>
      </div>

      <nav>
        <a href="../index.php" class="botonnav">Inicio</a>
        <a href="../nuestraescuela.php" class="botonnav">Nuestra Escuela</a>
        
        <?php if(isset($_SESSION['usuario'])): ?>
            <a href="logout.php" class="botonnav">Cerrar sesión</a>
        <?php else: ?>
            <a href="logeo.php" class="botonnav">Iniciar Sesión</a>
        <?php endif; ?>
      </nav>

    </div>
  </header>
  <section class="IS">
  <div class="texto">
 <h2>Iniciar Sesión</h2>
  </div>
   

    <form action="login.php" method="POST">

        <input
            type="text"
            name="usuario"
            placeholder="Usuario"
            required>

        <br><br>

        <input
            type="password"
            name="password"
            placeholder="Contraseña"
            required>

        <br><br>

        <button type="submit" class="boton">
            Ingresar
        </button>

    </form>
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