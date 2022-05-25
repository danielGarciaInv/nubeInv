<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminar Registro | InvirtuaCloud</title>
    <link rel="stylesheet" href="<?= base_url() ?>estilosPiola/estilosSignUp.css">
    <link rel="shortcut icon" href="<?=base_url('assets/media/logo.png')?>">
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
                        <form action="<?= base_url() ?>index.php/Login/actualizarContrasena/<?= $id?>" method="POST">
                            <input type="password" name="pwd" id="pwd" placeholder="Contraseña">
                            <button type="button" name="verPwd" id="verPwd" class="iconoFA btnVerPwd"></button>
                            <input type="password" name="pwdc" id="pwdc" placeholder="Repetir Contraseña">
                            <button type="button" name="verPwdc" id="verPwdc" class="iconoFA btnVerPwd"></button>
                            <input type="submit" value="Enviar">
                        </form>
                    </div>
                    <?php if(isset($errores)){?>
                    <div class="contenedorError">
                        <p><?= $errores ?></p>
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

    <script>
        document.addEventListener('DOMContentLoaded',()=>{
            verPwd.addEventListener('click',()=>{
                pwd.type = (pwd.type == "password") ? "text" : "password";
            });

            verPwdc.addEventListener('click',()=>{
                pwdc.type = (pwdc.type == "password") ? "text" : "password";
            });
        });
    </script>
</body>
</html>