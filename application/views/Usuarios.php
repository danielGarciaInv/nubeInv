<body id="body-pd">
    
    <main>
        <div class="contenedorPrincipal">
            <section class="seccionDash">
                <div class="fila">
                    <div class="column">
                        <div class="contenedorAdminRoles">
                            <h2><?= $tituloPagina ?></h2>
                            
                            <div class="container my-4 mx-0">
                                <?php while($filaUsuario = mysqli_fetch_array($usuarios)){?>
                                <?php if($filaUsuario['idusuario'] !== '1'): ?>
                                  <div class="row border-bottom p-1">
                                    <div class="col-12">
                                        <form class="row d-flex flex-row align-items-center" action="<?= base_url('index.php/Dashboard/editarUsuario'); ?>" method="POST">
                                            <div class="col-12 col-md-3 d-flex flex-column flex-md-row align-items-md-center">
                                                <label for="nombreNuevo<?= $filaUsuario['idusuario'] ?>" class="form-label">Nombre:</label>
                                                <input type="text" value="<?= $filaUsuario['nombre'] ?>" name="nombreNuevo<?= $filaUsuario['idusuario'] ?>" id="nombreNuevo<?= $filaUsuario['idusuario'] ?>" class="form-control ms-md-2">
                                                <input type="hidden" id="usrEditId" name="usrEditId" value="<?= $filaUsuario['idusuario'] ?>">
                                            </div>
                                            <div class="col-12 col-md-3 my-2 my-md-0 d-flex flex-column flex-md-row align-items-md-center">
                                                <label for="correo<?= $filaUsuario['idusuario'] ?>" class="form-label">Correo:</label>
                                                <input type="text" value="<?= $filaUsuario['correo'] ?>" id="correo<?= $filaUsuario['idusuario'] ?>" class="form-control ms-md-2" disabled>
                                            </div>
                                            <div class="col-12 col-md-3 my-2 my-md-0 d-flex flex-column flex-md-row align-items-md-center">
                                                <label for="usrRolNuevo<?= $filaUsuario['idusuario'] ?>" class="form-label">Rol:</label>
                                                <select class="form-select ms-md-2" aria-label="Default select example" id="usrRolNuevo<?= $filaUsuario['idusuario'] ?>" name="usrRolNuevo<?= $filaUsuario['idusuario'] ?>">
                                                    <?php foreach($roles as $rol){?>
                                                        <option value="<?= $rol['id'] ?>" <?php if($filaUsuario['id_rol'] == $rol['id']): ?> selected <?php endif?> ><?= $rol['descr']?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <a class="btn" id="btnEliminarUsr" onClick="eliminarUsr('<?= base_url('index.php/Dashboard/eliminarUsuario/'.$filaUsuario['idusuario']); ?>')">Eliminar</a>
                                                <input type="submit" id="guardarRol<?= $filaUsuario['idusuario'] ?>" value="Guardar" class="btn btn-primary botonEditarRol">
                                            </div>
                                        </form>
                                    </div>
                                  </div>
                                <?php endif?>
                                <?php }?>
                            </div>
                            
                        
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <script>
        
        const eliminarUsr = (urlEliminar) => {
            let confirmar = confirm('¿Desea eliminar este Usuario?');
            if(confirmar){
                fetch(urlEliminar,{
                    method: 'GET',
                }).then(res => window.location.reload());
            }else{
                return;
            }
        }
    </script>