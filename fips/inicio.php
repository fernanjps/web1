<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Usuario no autenticado, redirigir al login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header class="hero">
        <nav class="nav container">

            <div class="nav_logo">
                <h2 class="nav_title">nature-vive</h2>

            </div>

        <ul class="nav_link nav_link--menu"> 
            <li class="nav_items">
                <a href="#" class="nav_links">Inicio</a>
                </li>
            <li class="nav_items">
                <a href="acercade.php" class="nav_links">Acerca de</a>
                </li>

            <li class="nav_items">
                <a href="profile.php" class="nav_links">Perfil</a>
                </li>

            <li class="nav_items">
                <a href="comparador.php" class="nav_links">comparador</a>
                </li>
                <img src="./images/close.svg" class="nav_close">
        </ul>


            <div class="nav_menu">
                <img src="./images/menu.svg" class="nav_img">

            </div>


            
        </nav> 

        <section class="hero_container container">
            
        <main>
            <div class="contenedor__inicio">
                <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p>Esta es la página de inicio después de iniciar sesión.</p>
                <a href="logout.php" class="cta" >Cerrar sesión</a>
                 
            </div>
        </main>


        </section>

    </header>

    <section class="hero_container container">
            <h1 class="hero_title"> contenido del curso  .</h1>
            <p class="hero_paragraph"> contenido en el cuidado de las plantas del hogar.

            </p>         
            <a href="contenido.php" class="cta">Aprende ahora</a>


    </section>

    <section class="hero_container container">
            <h1 class="hero_title"> plan avanzado.</h1>
            <p class="hero_paragraph"> Elige de una vez ser un avanzado en el cuidado de las plantas del hogar.

            </p>
            <a href="formavanzado.php" class="cta">Aprende ahora</a>

    </section>

    <section class="hero_container container">
            <h1 class="hero_title"> plan profesinal .</h1>
            <p class="hero_paragraph"> plan profesional en el cuidado de las plantas del hogar.

            </p>         
            <a href="tareas.php" class="cta">Aprende ahora</a>


    </section>


</body>
</html>
