<body id="body-pd">
    <div class="modal fade" id="modalNuevaCat" tabindex="-1" aria-labelledby="modalNueaCatLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Crear Una Nueva Categoria</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row d-flex flex-row align-items-center" action="<?= base_url('index.php/Dashboard/nuevaCategoria'); ?>" method="POST">
                <div class="col-12 d-flex flex-column my-4">
                    <label for="nombreCatNueva" class="form-label">Nombre</label>
                    <input type="text" placeholder="Nombre:" id="nombreCatNueva" name="nombreCatNueva" class="form-control">
                </div>
                <div class="col-12">
                    <input type="submit" id="guardarCat" value="Guardar" class="btn btn-primary">
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
                            
                            <div class="container my-4 mx-0">
                                <?php while($filaCat = mysqli_fetch_array($categorias)){?>
                                
                                  <div class="row border-bottom p-1">
                                    <div class="col-12">
                                        <form class="row d-flex flex-row align-items-center" action="<?= base_url('index.php/Dashboard/editarCategoria'); ?>" method="POST">
                                            <div class="col-12 col-md-3 d-flex flex-column flex-md-row align-items-md-center">
                                                <label for="nombreNuevoCat<?= $filaCat['id'] ?>" class="form-label">Nombre:</label>
                                                <input type="text" value="<?= $filaCat['descripcion'] ?>" name="nombreNuevoCat<?= $filaCat['id'] ?>" id="nombreNuevoCat<?= $filaCat['id'] ?>" class="form-control ms-md-2">
                                                <input type="hidden" id="catEditId" name="catEditId" value="<?= $filaCat['id'] ?>">
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <a class="btn" id="btnEliminarCat" onClick="eliminarCat('<?= base_url('index.php/Dashboard/eliminarCategoria/'.$filaCat['id']); ?>')">Eliminar</a>
                                                <input type="submit" id="guardarCat<?= $filaCat['id'] ?>" value="Guardar" class="btn btn-primary botonEditarRol">
                                            </div>
                                        </form>
                                    </div>
                                  </div>
                                
                                <?php }?>
                            </div>
                            <div class="container my-4 mx-0">
                                <button class="link-primary border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#modalNuevaCat">+ Crear Nueva Categoría</button>
                            </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    
    <script>
        
        const eliminarCat = (urlEliminar) => {
            let confirmar = confirm('¿Desea eliminar esta Categoría?');
            if(confirmar){
                fetch(urlEliminar,{
                    method: 'GET',
                }).then(res => res.text()).then((respuesta) => {
                    if(respuesta == 'true'){
                        window.location.reload();
                    }else if(respuesta == 'false'){
                        alert('¡No puedes eliminar esta categoría porque hay archivos asignados a ella!');
                        return;
                    }
                });
            }else{
                return;
            }
        }
    </script>