<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acerca de</title>
    <link rel="stylesheet" href="css/comparador.css">
</head>

<body>
    <header class="hero">
        <nav class="nav container">
            <div class="nav_logo">
                <h2 class="nav_title">Acerca de</h2>
            </div>
            <ul class="nav_link nav_link--menu">
                <li class="nav_items">
                    <a href="index.php" class="nav_links">Inicio</a>
                </li>
                <li class="nav_items">
                    <a href="acerca_de.php" class="nav_links">Acerca de</a>
                </li>
                <img src="./images/close.svg" class="nav_close">
            </ul>
            <div class="nav_menu">
                <img src="./images/menu.svg" class="nav_img">
            </div>
        </nav>
        <section class="hero_container container">
            <h1 class="hero_title">Acerca de Nosotros</h1>
            <p class="hero_paragraph">Conoce más sobre nuestra misión, visión y el equipo detrás de este proyecto.</p>
        </section>
    </header>

    <main>
        <section class="container about">
            <h2 class="subtitle">Nuestra Misión</h2>
            <p class="about_paragraph">Nuestra misión es proporcionar información precisa y actualizada sobre las temperaturas de diferentes tipos de plantas para ayudar a mejorar el cuidado y mantenimiento de estas.</p>

            <h2 class="subtitle">Nuestro Equipo</h2>
            <p class="about_paragraph">Contamos con un equipo de expertos en botánica y tecnología que trabajan juntos para recopilar, analizar y presentar los datos de manera accesible y útil.</p>

            <h2 class="subtitle">Contáctanos</h2>
            <p class="about_paragraph">Si tienes alguna pregunta o comentario, por favor llena el siguiente formulario:</p>

            <form action="send.php" method="POST" class="contact_form">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>

                <label for="phone">Teléfono:</label>
                <input type="text" id="phone" name="phone" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="message">Mensaje:</label>
                <textarea id="message" name="message" required></textarea>

                <input type="submit" name="send" value="Enviar">
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Comparador. Todos los derechos reservados.</p>
    </footer>


    <?php
        include("send.php");
    ?>
        

    
    <script>  
        function myfunction(){
                window.location.href="http://localhost/fips"
            }
    </script>
    
</body>

</html>

