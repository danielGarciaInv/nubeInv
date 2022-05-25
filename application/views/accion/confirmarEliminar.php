<body>
    <main>
        <div class="contenedorPrincipal">
            <section class="seccionDash">
                <div class="fila">
                    <div class="columna contenidoEliminar">
                        <form class="contenedorFormEliminar" action="<?=base_url('index.php/Dashboard/eliminarArchivo/')?>" method="POST">
                            <h2>¿Eliminar el fichero "<?=$nombreArchivo?>" ?</h2>
                            <p>El archivo se eliminará permanentemente.</p>
                            <input type="hidden" name="ruta" id="ruta" value="<?=$rutaArchivo?>">
                            <div class="contenedorBotones">
                                <a href="<?=base_url()?>" class="btn">Cancelar</a>
                                <input type="submit" class="btn" value="Eliminar">
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </main>
