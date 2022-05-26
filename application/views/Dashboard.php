<body id="body-pd">
    <?php include('comun/previsualizador.php');?>
    <?php include('comun/header.php');?>

    <main>
        <div class="contenedorPrincipal">
            <section class="seccionDash">
                <div class="fila">
                    <div class="columna menuDash">
                        <div>
                            <h1><?= $tituloPagina?></h1>
                        </div>
                        <?php if($this->session->userdata('subir') === '1'){?>
                            <div class="d-flex align-items-center">
                                <a class="iconoFA fs-3" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalNuevaCarpeta"></a>
                                <div class="modal fade" id="modalNuevaCarpeta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Nueva Carpeta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="contFormSubida">
                                                    <form method="POST" class="flex-column">
                                                        <div class="d-flex flex-column justify-content-around mb-4">
                                                            <label>Nombre: </label>
                                                            <input type="text" class="form-control" id="nombreNuevaCarpeta" name="nombreNuevaCarpeta" required>
                                                        </div>
                                                        <input class="btnSubir mb-2" type="button" id="btnCrearCarpeta" name="btnCrearCarpeta" value="Crear">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#modalSubir">
                                    Subir Archivo
                                </button>
                                <div class="modal fade" id="modalSubir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Subir Archivo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="contFormSubida">
                                                    <form method="POST" enctype="multipart/form-data" class="flex-column">
                                                        <div class="d-flex flex-column flex-sm-row justify-content-around align-items-center">
                                                            <div class="btnCargar d-inline-block">
                                                                <input type="file" class="inpArchivo" id="inpArchivo" name="archivos[]" multiple required>
                                                                <div class="puntoRojo" id="puntoRojo" style="display:none;"></div>
                                                                <p class="m-0">Seleccionar Archivos</p>
                                                            </div>
                                                            <label class="">Ó</label>
                                                            <div class="btnCargar d-inline-block">
                                                                <input type="file" class="inpArchivo mt-2" id="inpCarpeta" name="carpetas[]" webkitdirectory required>
                                                                <div class="puntoRojo" id="puntoRojoC" style="display:none;"></div>
                                                                <p class="m-0">Seleccionar Carpeta</p>
                                                            </div>
                                                        </div>
                                                        <label for="slctCat" class="btnCargar mb-2" >Categoria de Archivos:</label>
                                                        <select class="form-select mb-4" aria-label="Default select example" name="slctCat" id="slctCat">
                                                            <?php foreach($categorias as $filaCat){ if(in_array($filaCat['id'],$this->session->userdata('permisos'))){?> 
                                                            <option value="<?= $filaCat['id']?>"><?= $filaCat['descripcion']?></option>
                                                            <?php }}?>
                                                        </select>
    
                                                        <div class="btnNotificar mb-4">
                                                            <div class="d-flex flex-row align-items-center">
                                                                <input type="checkbox" id="checkNotificar" name="checkNotificar" checked >
                                                                <label for="checkNotificar" class="mx-2">Notificar a los Usuarios por Correo</label>
                                                            </div>
                                                            <div class="bg-secondary bg-opacity-10 p-2 d-flex flex-column">
                                                                <?php foreach($usuarios as $usuario){?>
                                                                    <div class="d-flex flex-row align-items-center">
                                                                        <input class="checkCorreo" type="checkbox" id="checkNot<?= $usuario['idusuario']?>" name="checkNot<?= $usuario['idusuario']?>" value="<?= $usuario['correo'] ?>">
                                                                        <label for="checkNot<?= $usuario['idusuario']?>" class="mx-2"><?= $usuario['correo'] ?></label>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="d-flex flex-row align-items-center mt-2">
                                                                    <input type="checkbox" id="checkNotTodos" name="checkNotTodos">
                                                                    <label for="checkNotTodos" class="mx-2">Enviar a Todos</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <input class="btnSubir mb-2" type="button" id="btnSubir" name="btnSubir" value="Subir">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                    </div>
                </div>
                <?php if($this->session->userdata('subir') === '1'){?>
                    <div class="fila filaProgresoSubida" id="filaProgresoSubida" style="display: none">
                        <div class="columna colBtnCancelar">
                            <input type="button" id="btnCancelar" name="btnCancelar" value="Cancelar">
                        </div>
                        <div class="columna progresoSubida">
                            <div class="contenedorProgreso">
                                <span id="indicadorPorc"></span>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <div class="fila">
                    <div class="columna contenedorPanelDeArchivos">
                        <div class="contenedorNavegador">
                            <div class="contenedorPadding">
                                <div class="contenedorPath d-flex flex-row flex-wrap">
                                    <?php if(isset($navegador)) {foreach($navegador as $folder){?>
                                        <a href="<?= base_url('index.php/Dashboard/folder/'.$folder['id']) ?>">/<?= $folder['nombre']?></a>
                                    <?php }}?>
                                </div>
                            </div>
                        </div>


                        <div class="contenedorCarpetas">
                            <?php if(isset($folders)) {foreach($folders as $folder){?>
                                <div class="contenedorPadding">
                                    <div class="contenedorCarpeta" data-info-dropdown>
                                        <a href="<?= base_url('index.php/Dashboard/folder/'.$folder['id']) ?>" class="contInfoCarpeta">
                                            <div class="miniaturaCarpeta">
                                                <p class="iconoFA"></p>
                                            </div>
                                            <div class="infoCarpeta">
                                                <p><?= $folder['nombre'] ?></p>
                                            </div>
                                        </a>
                                        <div class="btnOpcionesCarpeta">
                                            <p class="iconoFA" data-info-button></p>
                                        </div>
                                        <div class="contOpciones" data-info-menu>
                                            <?php if($this->session->userdata('eliminar') === '1'):?>
                                                <a href="<?= base_url()."index.php/Dashboard/confirmarEliminarCarpeta/".$folder['id']?>" class="btnEli">
                                                    <p class="iconoFA"></p><p>Eliminar</p>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php }}?>
                        </div>
                        
                        <div class="contenedorArchivos">
                            <?php foreach($archivos as $fila){?>
                                <div class="contenedorPadding">
                                    <div class="contenedorArchivo">
                                        <div class="miniaturaArchivo btnPrev" id="<?=$fila['id']?>">
                                            <?php if($fila['tipo'] == '1') {?>
                                                <img src="<?= base_url($fila['ruta'])?>" class="archivoImagen">
                                            <?php }else if($fila['tipo'] == '11'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '2'){?>
                                                <video src="<?= base_url($fila['ruta'])?>" controls>
                                            <?php }else if($fila['tipo'] == '3'){?>
                                                <p></p>
                                            <?php } else if($fila['tipo'] == '41'){?>
                                                <a href="<?= base_url($fila['ruta']) ?>" target="blank"><p></p></a>
                                            <?php }else if($fila['tipo'] == '42'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '43'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '44'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '45'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '46'){?>
                                                <p></p>
                                            <?php }else if($fila['tipo'] == '5'){?>
                                                <p></p>
                                            <?php }else{?>
                                                <p></p>
                                            <?php }?>
                                        </div>
                                        <div class="infoArchivo">
                                            <div class="contNombre" data-info-dropdown>
                                                <p class="nombreArchivo"><?= $fila['nombre']?></p>
                                                <p class="iconoFA" data-info-button></p>
                                                <div class="contOpciones" data-info-menu>
                                                    <a href="<?= base_url()."index.php/Dashboard/descargarArchivo/".$fila['id']?>" class="btnDes">
                                                        <p></p><p>Descargar</p>
                                                    </a>
                                                    <?php if($this->session->userdata('eliminar') === '1'):?>
                                                        <a href="<?= base_url()."index.php/Dashboard/confirmarEliminar/".$fila['id']?>" class="btnEli">
                                                            <p></p><p>Eliminar</p>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="contInfo" style="display:flex;flex-direction:row;">
                                                <p class="tamanoArchivo"><?= $fila['tamano']?> - <?= $fila['fecha']?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script>
        document.addEventListener('click',e => {
            const esInfoButton = e.target.matches("[data-info-button]");
            if(!esInfoButton && e.target.closest("[data-info-dropdown]") == null) {
                document.querySelectorAll("[data-info-menu]").forEach(menu => {
                    menu.classList.remove("mostrarMenu");
                });
                return;
            }

            let dropdownActual;
            if(esInfoButton){
                dropdownActual = e.target.closest("[data-info-dropdown]").children;
                dropdownActual = dropdownActual[dropdownActual.length - 1];
                dropdownActual.classList.toggle("mostrarMenu");
            }
        });

    </script>
    <?php if($this->session->userdata('subir') === '1'):?>
        <script src="<?= base_url('assets/js/fnDashboard.js')?>"></script>
    <?php else: ?>
        <script src="<?= base_url('assets/js/fnDashboardUsr.js')?>"></script>
    <?php endif; ?>