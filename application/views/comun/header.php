
<!-- Modal para el formulario de nuevos usuarios  -->
<?php if($this->session->userdata('rol') === '1'):?>
<div class="modal fade" id="modalNuevoUsr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear Nuevo Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="contenedorFormNuevoUsr">
            <p>Se enviará un solicitud a la dirección de correo proporcionada para crear el nuevo Usuario</p>
            <form action="<?= base_url('index.php/Dashboard/nuevoUsuario') ?>"  method="post">
            <div class="form-group">
                <label for="nombreNuevo">Nombre:</label>
                <input type="text" class="form-control" id="nombreNuevo" name="nombreNuevo" placeholder="Nombre" required>
            </div>
            <div class="form-group">
                <label for="correoNuevo">Correo:</label>
                <input type="email" class="form-control" id="correoNuevo" name="correoNuevo" placeholder="Correo" required>
            </div>
            <div class="form-group">
                <label for="pwdNuevo">Contraseña:</label>
                <input type="password" class="form-control" id="pwdNuevo" name="pwdNuevo" placeholder="Contraseña" required>
            </div>
            <div class="form-group">
                <label for="selectRol">Rol</label>
                <select class="form-control" id="selectRol" name="selectRol">
                    <?php while($filaRol = mysqli_fetch_array($roles)){?>
                        <option value="<?= $filaRol['id']?>"><?= $filaRol['descr']?></option>
                    <?php }?>
                </select>
                <div class="d-flex my-2 flex-row align-items-center">
                    <input type="checkbox" class="checkCat" value="subir"  id="checkSubir" name="checkSubir">
                    <label class="mx-2" for="checkSubir">Permiso para subir archivos</label>
                </div>
                <div class="d-flex my-2 flex-row align-items-center">
                    <input type="checkbox" class="checkCat" value="eliminar" id="checkEliminar" name="checkEliminar">
                    <label class="mx-2" for="checkEliminar">Permiso para eliminar archivos</label>
                </div>
                <a href="<?= base_url('index.php/Dashboard/roles'); ?>" class="link-primary">Gestionar Roles</a>
            </div>
            <div class="form-group">
                <input class="form-check-input" type="checkbox" id="tmpUsr" name="tmpUsr">
                <label class="form-check-label" for="tmpUsr">Usuario Temporal</label>
                <select class="form-control" id="selectTiempo" name="selectTiempo" disabled>
                  <option value="24">24 Hrs</option>
                  <option value="48">48 Hrs</option>
                  <option value="72">72 Hrs</option>
                </select>
            </div>
            
            <button type="button" id="btnCrearUsr" class="btn btn-primary mt-2">Enviar</button>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <div class="bg-success w-100 rounded text-center" id="succMsg" style="display: none;">
            <p class="text-white m-2">Usuario creado con exito!!</p>
        </div>
        <div class="bg-danger w-100 rounded text-center" id="errMsg" style="display: none;">
            <p class="text-white m-2">Error: Este correo ya ha sido Registrado!!</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>

<header id="header">
        <div class="fila cabeceraLogin">
            <div class="columna titulo">
                <a href="<?= base_url('index.php/Dashboard')?>"><h2>Invirtual Cloud</h2></a>
            </div>
            <div class="columna columnaNav">
                <nav>
                    <?php if($this->session->userdata('rol') === '1'):?>
                        <a class="icono" id="btnNuevoUsr" data-bs-toggle="modal" data-bs-target="#modalNuevoUsr"></a>
                    <?php endif; ?>
                    <?php if($this->session->userdata('correo')):?>
                        <div>
                            <button class="icono btn" type="button" id="header-toggle"></button>
                        </div>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
</header>
<div class="l-navbar" id="nav-bar">
    <nav class="nav">
        <div class="nav_list fw-bold">
            <a class="nav_link font-weight-bold m-0"><p class="iconoFA"></p><p><?= $this->session->userdata('nombre') ?></p></a>
            <a href="<?= base_url('index.php/Dashboard')?>" class="nav_link active">Mi Dashboard</a>
            <a data-bs-toggle="collapse" href="#collapseCategorias" aria-controls="collapseCategorias" class="nav_link mb-1">Categorías</a>
            <div class="collapse mb-4 ps-2" id="collapseCategorias">
                <div class="card bg-transparent border-0 fw-normal">
                    <?php foreach($categorias as $filaCat){ if(in_array($filaCat['id'],$this->session->userdata('permisos'))) {?>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?= base_url('index.php/Dashboard/categoria/'.$filaCat['id'].'/'.$filaCat['descripcion'])?>"><?= $filaCat['descripcion']?></a>
                            </li>
                        </ul>
                    <?php } }?>
                    <?php if($this->session->userdata('rol') === '1'){?>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="<?= site_url('Dashboard/categorias') ?>">Categorías</a>
                            </li>
                        </ul>
                    <?php }?>
                </div>
            </div>
            <?php if($this->session->userdata('rol') === '1'){?>
            <a data-bs-toggle="collapse" href="#collapseUsuarios" aria-controls="collapseUsuarios" class="nav_link mb-1">Usuarios</a>
            <div class="collapse mb-4 ps-2" id="collapseUsuarios">
                <div class="card bg-transparent border-0 fw-normal">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= base_url('index.php/Dashboard/roles')?>">Roles</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?= base_url('index.php/Dashboard/usuarios')?>">Usuarios</a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php }?>
        </div>
        <a href="<?= site_url('Login/cerrarSesion') ?>" class="nav_link">Cerrar Sesión</a>
    </nav>
</div>

<style>
    :root {
      --nav-width: 32px;
      --first-color: #159eee;
      --first-color-light: #E6E6E6;
      --white-color: #FFFFFF;
      --normal-font-size: 1rem;
      --z-fixed: 100
    }

    *,
    ::before,
    ::after {
      box-sizing: border-box
    }

    body {
      position: relative;
      transition: .5s
    }

    a {
      text-decoration: none
    }

    .header {
      width: 100%;
      height: var(--header-height);
      position: fixed;
      top: 0;
      right: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      background-color: var(--white-color);
      z-index: var(--z-fixed);
      transition: .5s
    }

    .header_toggle {
      color: var(--first-color);
      font-size: 1.5rem;
      cursor: pointer
    }

    .header_img {
      width: 35px;
      height: 35px;
      display: flex;
      justify-content: center;
      border-radius: 50%;
      overflow: hidden
    }

    .header_img img {
      width: 40px
    }

    .l-navbar {
        visibility: hidden;
      position: fixed;
      top: 0;
      right: 0;
      width: var(--nav-width);
      height: 100vh;
      background-color: var(--first-color);
      padding: .5rem 1rem 0 0;
      transition: .5s;
      z-index: var(--z-fixed)
    }

    .nav {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden
    }

    .nav_logo,
    .nav_link {
      display: grid;
      grid-template-columns: max-content max-content;
      align-items: center;
      column-gap: 1rem;
      padding: .5rem 0 .5rem 1.5rem
    }

    .nav_logo {
      margin-bottom: 2rem
    }

    .nav_logo-icon {
      font-size: 1.25rem;
      color: var(--white-color)
    }

    .nav_logo-name {
      color: var(--white-color);
      font-weight: 700
    }

    .nav_link {
      position: relative;
      color: var(--first-color-light);
      margin-bottom: 1.5rem;
      transition: .3s
    }

    .showNv {
      visibility: visible;
      right: 0;
      width: calc(var(--nav-width) + 156px)
    }

    .body-pd {
       padding-right: calc(var(--nav-width) + 22px);
    }

    .active {
      color: var(--white-color);
    }

    .active::before {
      content: '';
      position: absolute;
      right: 0;
      width: 2px;
      height: 32px;
      background-color: var(--white-color)
    }

    .height-100 {
      height: 100vh
    }

</style>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        const url = '<?= base_url()?>';
        const btnCrearUsr = document.getElementById('btnCrearUsr');
        const tmpUsr = document.getElementById('tmpUsr');
        const selectTiempo = document.getElementById('selectTiempo');
        
        function habilitarSelectTmp(){
          selectTiempo.disabled = selectTiempo.disabled == true ? false : true;
        }
        function limpiarFormulario(){
          nombreNuevo.value = "";
          correoNuevo.value = "";
          pwdNuevo.value = "";
          tmpUsr.checked = false;
          checkSubir.checked = false;
          checkEliminar.checked = false;
          selectTiempo.disabled = true;
        }

        // Función crearUsuario: crear nuevo usuario Asincronamente xd
        function crearUsuario(){
            let datos = new FormData();
            datos.append('nombreNuevo',nombreNuevo.value);
            datos.append('correoNuevo',correoNuevo.value);
            datos.append('pwdNuevo',pwdNuevo.value);
            datos.append('rolNuevo',selectRol.value);
            datos.append('tmpUsr',tmpUsr.checked); 
            datos.append('checkSubir',checkSubir.checked);
            datos.append('checkEliminar',checkEliminar.checked);
            datos.append('selectTiempo',selectTiempo.value);

            fetch(`${url}index.php/Dashboard/nuevoUsuario`,{
                method: 'POST',
                body: datos
            }).then((respuesta)=>{
                return respuesta.text();
            }).then((res)=>{
                if(res == 'true'){
                    succMsg.style.display = 'block';
                    limpiarFormulario();
                }else{
                    errMsg.style.display = 'block';
                }
            });

            setTimeout(()=>{
                succMsg.style.display = 'none';
                errMsg.style.display = 'none';
            },5000);
        }

        const showNavbar = (toggleId, navId, bodyId, headerId) =>{
        const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId)

        // Validate that all variables exist
        if(toggle && nav && bodypd && headerpd){
            toggle.addEventListener('click', ()=>{
            // show navbar
            nav.classList.toggle('showNv')
            // change icon
            toggle.classList.toggle('bx-x')
            // add padding to body
            bodypd.classList.toggle('body-pd')
            // add padding to header
            headerpd.classList.toggle('body-pd')
            })
        }else{
            console.log('Nel Pastel');
        }
        }

        showNavbar('header-toggle','nav-bar','body-pd','header')

        const linkColor = document.querySelectorAll('.nav_link')

        function colorLink(){
        if(linkColor){
        linkColor.forEach(l=> l.classList.remove('active'))
        this.classList.add('active')
        }
        }
        
        linkColor.forEach(l => l.addEventListener('click', colorLink))
        if(btnCrearUsr != null && tmpUsr != null){ // +++++++++++++++++++++++++++++++++++++++++++++++++++++
          btnCrearUsr.addEventListener('click',crearUsuario);
          tmpUsr.addEventListener('click',habilitarSelectTmp);
        }

    });
</script>