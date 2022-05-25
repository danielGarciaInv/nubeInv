<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | InvirtualCloud</title>
    <link rel="stylesheet" href="<?= base_url() ?>estilosPiola/estilosSignUp.css">
    <link rel="shortcut icon" href="<?= base_url('assets/media/logo.png'); ?>">
</head>
<body>
    <main>
        <div class="contenedorPrincipal">
            <section class="seccionLogin">
                <div class="fila cabeceraLogin">
                    <h2>InvirtualCloud</h2>
                    <h1><?= $titulo ?></h1>
                    <p><?= $cont ?></p>
                </div>
    
                <div class="fila contenido">
                    <div class="contenedorFormulario">
                        <form action="<?= base_url('index.php/Login/enviarCorreoRecuperacion') ?>" method="POST">
                            <input type="email" name="correo" id="correo" placeholder="Correo Electronico:">
                            <?php if(isset($caducidad)){?>
                                <input type="hidden" name="caducidad" value="<?= $caducidad ?>">
                            <?php }?>
                            <input type="submit" value="Recuperar contraseña">
                        </form>
                    </div>
                    <?php if(isset($info)){?>
                    <div class="contenedorInfo">
                        <p><?= $info ?></p>
                    </div>
                    <?php } ?>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <div class="fila">
            <p>Desarrollado por invirtual | Todos los derechos reservados 2022</p>
        </div>
    </footer>
</body>
</html>