<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url() ?>estilosPiola/estilosLogin.css">
    <link rel="shortcut icon" href="<?=base_url('assets/media/logo.png')?>">
</head>
<body>
    <main>
        <div class="contenedorPrincipal">
            <div class="columnaForm columna">
                <section class="seccionLogin">
                    <div class="fila cabeceraLogin">
                        <h1><?= $titulo?></h1>
                    </div>
        
                    <div class="fila contenido">
                        <div class="contenedorFormulario">
                            <form action="<?= base_url() ?>index.php/login/iniciarSesion" method="POST">
                                <input type="email" name="correo" id="correo" placeholder="Correo Electronico:">
                                <input type="password" name="pwd" id="pwd" placeholder="Contraseña">
                                <button type="button" name="verPwd" id="verPwd" class="iconoFA btnVerPwd"></button>
                                <input type="submit" value="Iniciar Sesión">
                            </form>
                            <a href="<?= base_url('index.php/Login/recuperarContrasena') ?>">Olvidé mi Contraseña</a>
                        </div>
                        <?php if(isset($errores)){?>
                        <div class="contenedorError">
                            <p><?= $errores ?></p>
                        </div>
                        <?php } ?>
                    </div>
                </section>
            </div>

            <div class="columnaContent columna">
                <section class="seccionContent">
                    <div class="fila cabeceraContent">
                        <h2 class="h1">INVIRTUAL WEB</h2>
                    </div>

                    <div class="fila contenido w-100">

                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <p class="h-100 text-center text-light fs-5">
                                        Comparte recursos multimedia con tu equipo de forma facil y sencilla
                                    </p>
                                </div>
                                <div class="carousel-item">
                                    <p class="h-100 text-center text-light fs-5">
                                        Descarga tus archivos desde cualquier dispositivo, en cualquier momento
                                    </p>
                                </div>
                                <div class="carousel-item">
                                    <p class="h-100 text-center text-light fs-5">
                                        Controla quienes pueden ver, descargar y eliminar tus archivos
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    <video muted autoplay loop>
        <source src="<?=base_url('assets/media/bg-video.mp4')?>" type="video/mp4">
    </video>
    <div class="overlay"></div>
    </main>
    <footer>
        <div class="fila">
            <p>Desarrollado por invirtual | Todos los derechos reservados 2022</p>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded',()=>{
            verPwd.addEventListener('click',()=>{
                pwd.type = (pwd.type == "password") ? "text" : "password";
            });
        });
    </script>
</body>
</html>