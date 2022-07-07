<body id="body-pd">
    <?php include('comun/previsualizador.php');?>
    <?php include('comun/header.php');?>

    <main>
        <div class="contenedorPrincipal">
            <section class="seccionDash">
                <div class="fila">
                    <div class="menuDash d-flex flex-column-reverse flex-md-row justify-content-center justify-content-md-between align-items-center">
                        <div class="d-flex align-items-center">
                            <h1 class="mt-4 mt-md-2"><?= $tituloPagina?></h1>
                            <?php if(isset($filtro)){?>
                            <a class="iconoFA fs-4 ms-2 mt-4 mt-md-0" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalFiltro"></a>
                            <div class="modal fade" id="modalFiltro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Aplicar Filtro</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="contFormSubida">
                                                <form method="GET" action="<?= base_url('index.php/Dashboard') ?>" class="flex-column">
                                                    <div class="d-flex flex-column justify-content-around mb-4">
                                                        <label>Tamaño Máximo</label>
                                                        <input type="range" min="1" max="2048" value="2048" step="4" name="tamano" id="tamano">
                                                        <label for="tamano">2048 MB</label>
                                                        <select class="form-select mt-4" name="orden">
                                                            <option value="">Ordenar Por:</option>
                                                            <option value="ASC">Más antiguos</option>
                                                            <option value="DESC">Más recientes</option>
                                                        </select>
                                                    </div>
                                                    <input class="w-100 bg-primary text-white text-bold p-2 rounded-3 border-0" type="submit" value="Aplicar">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                        <div class="d-flex align-items-center">
                            <a class="iconoFA fs-4" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalBuscar"></a>
                            <div class="modal fade" id="modalBuscar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Buscar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="contFormSubida">
                                                <form method="GET" action="<?= base_url('index.php/Dashboard/busqueda') ?>" class="flex-column">
                                                    <div class="d-flex flex-column justify-content-around mb-4">
                                                        <label>Ingresar nombre o palabra clave: </label>
                                                        <input type="text" class="form-control" id="busqueda" name="busqueda">
                                                    </div>
                                                    <input class="w-100 bg-primary text-white text-bold p-2 rounded-3 border-0" type="submit" value="Buscar">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if($this->session->userdata('subir') === '1'){?>
                            <a class="iconoFA fs-3 ms-2" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalNuevaCarpeta"></a>
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
                                                        <input type="hidden" id="idCat" value="<?php if(isset($id_categoria)){echo $id_categoria;}else{echo "NULL";} ?>">
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
                                                            <div class="d-flex flex-row flex-wrap">
                                                                <ul class="list-group list-group-horizontal d-flex flex-wrap" id="ulCorreosNot"></ul>
                                                            </div>
                                                            <select class="form-select mt-2" name="usrsNotificar" id="usrsNotificar">
                                                                <option value="">Buscar usuario para notificar</option>
                                                                <?php foreach($usuarios as $usuario){?>
                                                                    <option value="<?= $usuario['correo'] ?>"><?= $usuario['correo'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <!-- <div class="d-flex flex-row align-items-center mt-2">
                                                                <input type="checkbox" id="checkNotTodos" name="checkNotTodos">
                                                                <label for="checkNotTodos" class="mx-2">Notificar a Todos</label>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                    <input type="hidden" id="idCarp" value="<?php if(isset($id_carpeta)){echo $id_carpeta;}else{echo "NULL";} ?>">
                                                    <input class="btnSubir mb-2" type="button" id="btnSubir" name="btnSubir" value="Subir">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                        </div>
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
                                                        <a onClick="eliminarArchivo('<?= base_url()."index.php/Dashboard/eliminarArchivo/".$fila['id']?>')" class="btnEli">
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

                        <?php if(isset($pagina)){?>
                        <div class="container-fluid  col-12">
                            <ul class="pagination pg-dark justify-content-center pb-5 pt-5 mb-0" style="float: none;" >
                                <li class="page-item">
                                <?php
                                $parURL = '';
                                foreach ($parametros as $par) {
                                    $parURL .= $par['parametro'].'=';
                                    $parURL .= $par['valor'].'&';
                                }
                                if($_REQUEST["pag"] == "1" ){
                                    $_REQUEST["pag"] == "0";
                                    echo  "";
                                }else{
                                    if ($pagina>1)
                                    $ant = $_REQUEST["pag"] - 1;
                                ?>                                    
                                    <a class="page-link" aria-label="Previous" href="<?= base_url('index.php/Dashboard/'.$metodo.'?'.$parURL.'pag=1')?>"><span aria-hidden='true'>&laquo;</span></a>
                                    <li class="page-item"><a class="page-link" href="<?= base_url('index.php/Dashboard/'.$metodo.'?'.$parURL.'pag='. ($pagina-1) )?>"><?=$ant?></a></li>
                                <?php } ?>
                                <li class='page-item active'><a class='page-link'><?= $_REQUEST["pag"] ?></a></li>
                                <?php
                                $sigui = $_REQUEST["pag"] + 1;
                                $ultima = $totalArchivos / $limite;
                                if ($ultima == $_REQUEST["pag"] +1 ){
                                    $ultima == "";
                                }
                                if ($pagina<$paginas && $paginas>1){?>
                                <li class="page-item"><a class="page-link" href="<?=base_url('index.php/Dashboard/'.$metodo.'?'.$parURL.'pag='.($pagina+1))?>"><?=$sigui?></a></li>
                                <li class="page-item"><a class="page-link" aria-label="Next" href="<?=base_url('index.php/Dashboard/'.$metodo.'?'.$parURL.'pag='.ceil($ultima))?>"><span aria-hidden='true'>&raquo;</span></a></li>
                                <?php }?>
                            </ul>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
            const tamano = document.getElementById('tamano');
            const labelTamano = document.querySelector('label[for="tamano"]');
    
            if(tamano != null){
                tamano.addEventListener('change',()=>{
                    labelTamano.innerHTML = tamano.value + ' MB';
                });
            }

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
        });
        const eliminarArchivo = (urlEliminar) => {
            let confirmar = confirm('¿Desea eliminar este Archivo?');
            if(confirmar){
                fetch(urlEliminar,{
                    method: 'GET',
                }).then(res => res.text()).then((respuesta) => {
                    if(respuesta == 'true'){
                        window.location.reload();
                    }
                });
            }else{
                return;
            }
        }
    </script>
    <?php if($this->session->userdata('subir') === '1'):?>
        <script src="<?= base_url('assets/js/fnDashboard.js')?>"></script>
    <?php else: ?>
        <script src="<?= base_url('assets/js/fnDashboardUsr.js')?>"></script>
    <?php endif; ?>