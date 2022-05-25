<body id="body-pd">
    <div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-labelledby="modalNuevoRolLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Crear Un Nuevo Rol</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row d-flex flex-row align-items-center" method="POST">
                <div class="col-12 d-flex flex-column">
                    <label for="nombreRolNuevo" class="form-label">Nombre</label>
                    <input type="text" placeholder="Nombre:" id="nombreRolNuevo" name="nombreRolNuevo" class="form-control">
                </div>
                <div class="col-12 my-2 d-flex flex-column">
                    <p>Permisos de Lectura:</p>
                    <?php foreach($categorias as $categoria){?>
                        <div class="d-flex flex-row align-items-center">
                            <input type="checkbox" class="checkCat" value="<?= $categoria['id']?>" checked >
                            <label class="mx-2"><?= $categoria['descripcion']?></label>
                        </div>
                    <?php }?>
                </div>
                <div class="col-12">
                    <input type="button" id="guardarRol" value="Guardar" class="btn btn-primary" onClick="crearRol()">
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>
    
    <main>
        <div class="contenedorPrincipal">
            <section class="seccionDash">
                <div class="fila">
                    <div class="column">
                        <div class="contenedorAdminRoles">
                            <h2><?= $tituloPagina ?></h2>
                            
                            <div class="container m-0 p-0 d-flex flex-row flex-wrap">
                                <?php while($filaRol = mysqli_fetch_array($roles)){?>
                                    <?php if($filaRol['id'] !== '1'): ?>
                                  <div class="col-12 col-md-4 ps-md-4 row border-bottom p-4">
                                    <div class="col-12">
                                        <form class="row d-flex flex-row align-items-center" method="POST">
                                            <div class="col-12 d-flex flex-column flex-md-row align-items-md-center">
                                                <label for="nombreRolNuevo<?= $filaRol['id'] ?>" class="form-label">Nombre:</label>
                                                <input type="text" value="<?= $filaRol['descr'] ?>" name="nombreRolNuevo<?= $filaRol['id'] ?>" id="nombreRolNuevo<?= $filaRol['id'] ?>" class="form-control ms-md-2">
                                            </div>

                                            <div class="col-12 my-2 d-flex flex-column">
                                                <p>Permisos de Lectura:</p>
                                                <?php foreach($categorias as $categoria){?>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <input type="checkbox" class="checkCatEdi<?= $filaRol['id'] ?>" value="<?= $categoria['id']?>" <?php foreach($permisosRoles as $permiso){ if($permiso['id_rol'] == $filaRol['id'] && $permiso['id_cat'] == $categoria['id']){?> checked <?php } }?>>
                                                        <label for="checkCatEdi" class="mx-2"><?= $categoria['descripcion']?></label>
                                                    </div>
                                                <?php }?>
                                            </div>

                                            <div class="col-12">
                                                <a onCLick="eliminarRol('<?= base_url('index.php/Dashboard/eliminarRol/'.$filaRol['id']); ?>')" class="btn">Eliminar</a>
                                                <input type="button" id="guardarRol<?= $filaRol['id'] ?>" value="Guardar" class="btn btn-primary botonEditarRol" onClick="editarRol('<?= $filaRol['id'] ?>')">
                                            </div>
                                        </form>
                                    </div>
                                  </div>
                                  <?php endif ?>
                                <?php }?>
                            </div>
                            
                            <div class="container my-4 mx-0">
                                <button class="link-primary border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">+ Crear Nuevo Rol</button>
                            </div>
                        
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <script>
        
        const editarRol = (id) => {
            const checksEdi = document.getElementsByClassName('checkCatEdi'+id);
            const nombreRolEdi = document.getElementById('nombreRolNuevo'+id);
            let datosFormEditRol = new FormData();
            datosFormEditRol.append('nombreRolNuevo',nombreRolEdi.value);
            let checksCat = [];
            for (let ite = 0; ite < checksEdi.length; ite++) {
                if(checksEdi[ite].checked){
                    checksCat[ite] = checksEdi[ite].value;
                }
            }
            
            checksCat = JSON.stringify(checksCat);
            datosFormEditRol.append('checksCat',checksCat);
            
            fetch('<?= base_url('index.php/Dashboard/editarRol/'); ?>'+id,{
                method: 'POST',
                body: datosFormEditRol
            }).then(res => window.location.reload());
        }

        const crearRol = () => {
            const checks = document.getElementsByClassName('checkCat');
            let datosFormNuevoRol = new FormData();
            datosFormNuevoRol.append('nombreRolNuevo',nombreRolNuevo.value);
            let checksCat = [];
            for (let ite = 0; ite < checks.length; ite++) {
                if(checks[ite].checked){
                    checksCat[ite] = checks[ite].value;
                }
            }
            
            checksCat = JSON.stringify(checksCat);
            datosFormNuevoRol.append('checksCat',checksCat);
            
            fetch('<?= base_url('index.php/Dashboard/nuevoRol'); ?>',{
                method: 'POST',
                body: datosFormNuevoRol
            }).then(res => window.location.reload());
        }

        const eliminarRol = (urlEliminar) => {
            let confirmar = confirm('¿Desea eliminar este Rol?');
            if(confirmar){
                fetch(urlEliminar,{
                    method: 'GET',
                }).then(res => res.text()).then((respuesta) => {
                    if(respuesta == 'true'){
                        window.location.reload();
                    }else if(respuesta == 'false'){
                        alert('¡No puedes eliminar este rol porque hay usuarios asignados al mismo!\nPor favor reasigna a los usuarios e intenta de nuevo');
                        return;
                    }
                });
            }else{
                return;
            }
        }
    </script>
