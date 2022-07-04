document.addEventListener('DOMContentLoaded',()=>{
    const url = "http://localhost/invirtualCloud/";
    
    var inpArchivo = document.getElementById('inpArchivo');
    var inpCarpeta = document.getElementById('inpCarpeta');
    var filaProgresoSubida = document.getElementById('filaProgresoSubida');
    var btnCancelar = document.getElementById('btnCancelar');
    var puntoRojo = document.getElementById('puntoRojo');
    var puntoRojoC = document.getElementById('puntoRojoC');
    var btnsPrev = document.getElementsByClassName('btnPrev');
    var preVisualizador = document.getElementById('preVisualizador');
    var btnCerrarPrev = document.getElementById('btnCerrarPrev');
    var btnSubir = document.getElementById('btnSubir');
    var btnCrearCarpeta = document.getElementById('btnCrearCarpeta');
    var indicadorPorc = document.getElementById('indicadorPorc');
    var infosArchivo = document.getElementsByClassName('infoArchivo');
    var derPrev = document.getElementById('derPrev');
    var izqPrev = document.getElementById('izqPrev');
    var datosArchivo;
    var imagenes = document.getElementsByClassName('archivoImagen');
    var contenedorMedia = document.getElementById('contenedorMedia');

    var nombreNuevo = document.getElementById('nombreNuevo');
    var correoNuevo = document.getElementById('correoNuevo');
    var pwdNuevo = document.getElementById('pwdNuevo');
    var selectRol = document.getElementById('selectRol');
    var succMsg = document.getElementById('succMsg');

    const checkNotificar = document.getElementById('checkNotificar');
    const checkNotTodos = document.getElementById('checkNotTodos');
    const checksCorreos = document.getElementsByClassName('checkCorreo');
    const usrsNotificar = document.getElementById('usrsNotificar');
    const ulCorreosNot = document.getElementById('ulCorreosNot');

    var pos = 0;
    var correosNotificar = [];

    /* ------------------------------------- Puestas en marcha cuando carga la página ------------------------------------------ */

    // Llamada asincrona para obtener los datos de los archivos de BD
    /* fetch(`${url}index.php/Dashboard/mostrarArchivosAs`,{
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then((respuesta)=>{
        return respuesta.json();
    }).then((res)=>{
        datosArchivo = res;
    }); */


    /* ------------------------------------- Funciones ------------------------------------------ */


    // ----------------------- Para el slider
    // Función setPos: establece la posicion actual para el slider una vez que se dió click a una imagen
    function setPos(num){
        pos = num-1;
    }

    // ----------------------- Para la subida "Asincrona" (En realidad la página si se va a recargar xd, pero es para la barra de progreso)
    // Función subirArchivos: hace la petición asincrona para subir y monitoriza el progreso
    function subirArchivos(){
        if(inpArchivo.files.length > 0){
            var checksCorreosArr = new Array();
            var formData = new FormData();
            var archivos = inpArchivo.files;
            for (let ite = 0; ite < archivos.length; ite++) {
                formData.append('archivo'+ite,archivos[ite]);
            }

            for (const check of checksCorreos) {
                if(check.checked){
                    checksCorreosArr.push(check.value);
                }
            }
            checksCorreosArr = JSON.stringify(correosNotificar);
            formData.append('checksCorreosArr',checksCorreosArr);
            formData.append('checkNotificar',checkNotificar.checked);
            formData.append('categoria',slctCat.value);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener('progress',mostrarProgreso,false);
            ajax.addEventListener('load',mostrarCompleto,false);
            ajax.open('POST',`${url}index.php/Dashboard/subirArchivo`);
            ajax.send(formData);
            filaProgresoSubida.style.display = "flex";
    
            btnCancelar.addEventListener('click',()=>{
                ajax.abort();
                indicadorPorc.style.background = '#c73434';
                window.location.reload();
            });

        }else if(inpCarpeta.files.length > 0){
            var formData = new FormData();
            var archivos = inpCarpeta.files;

            var file_name = "file[]";
            var folder_name = "folder[]";
            for (let ite = 0; ite < archivos.length; ite++) {
                let archivo = archivos[ite];
                if(archivo.name != ""){
                    formData.append(file_name,archivo);
                    formData.append(folder_name,archivo.webkitRelativePath);
                }
            }

            var checksCorreosArr = new Array();
            for (const check of checksCorreos) {
                if(check.checked){
                    checksCorreosArr.push(check.value);
                }
            }
            checksCorreosArr = JSON.stringify(checksCorreosArr);
            formData.append('checksCorreosArr',checksCorreosArr);
            formData.append('checkNotificar',checkNotificar.checked);
            formData.append('categoria',slctCat.value);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener('progress',mostrarProgreso,false);
            ajax.addEventListener('load',mostrarCompleto,false);
            ajax.open('POST',`${url}index.php/Dashboard/subirCarpeta`);
            ajax.send(formData);
            filaProgresoSubida.style.display = "flex";
    
            btnCancelar.addEventListener('click',()=>{
                ajax.abort();
                indicadorPorc.style.background = '#c73434';
                window.location.reload();
            });
        }else{
            alert('Ningun archivo se ha seleccionado!!!');
        }
    }

    function crearCarpeta(){
        let formData = new FormData();
        formData.append('nombreNuevaCarpeta',nombreNuevaCarpeta.value);
        fetch(`${url}index.php/Dashboard/nuevaCarpeta`,{
            method: 'POST',
            body: formData
        }).then(r => r.text()).then(res => console.log(res)/*window.location.reload()*/);
    }

    // Función mostrarProgreso: recibe el estatus del progreso y en base a ello modifica el ancho de la barra
    function mostrarProgreso(event){
        var porcentaje = parseInt(event.loaded / event.total * 100);
        indicadorPorc.innerHTML = porcentaje + "%";
        indicadorPorc.style.width = porcentaje + "%";
    }

    // Función mostrarCompleto: muestra el mensaje de completado. Se llama cuando la subida se completó
    function mostrarCompleto(event){
        indicadorPorc.innerHTML = "Completado";
        window.location.reload();
    }

    function crearTag(){
        ulCorreosNot.querySelectorAll('li').forEach(li => li.remove());
        correosNotificar.forEach(correo => {
            let tag = `<li class="list-group-item d-flex">${correo}<p class="tagCorreoNot m-0 ms-2 px-2 text-white bg-secondary rounded-circle" style="cursor:pointer;">x</p></li>`;
            ulCorreosNot.insertAdjacentHTML('afterbegin', tag);
            document.querySelector('.tagCorreoNot').addEventListener('click',(e)=>{
                eliminarTag(e.target, correo);
            });        
        });
    }
    
    function eliminarTag(element, correo){
        let index = correosNotificar.indexOf(correo);
        correosNotificar = [...correosNotificar.slice(0,index), ...correosNotificar.slice(index + 1)];
        element.parentElement.remove();
    }
    /* ------------------------------------- Eventos y Declaraciones ------------------------------------------ */


    // ----------------------- Para el slider
    // Crea una copia de las imagenes y las incrusta en el contenedorMedia del previsualizador
    for (let imagen of imagenes) {
        var nuevaImg = document.createElement('img');
        nuevaImg.src = imagen.src;
        nuevaImg.setAttribute('style','display: none;');
        contenedorMedia.appendChild(nuevaImg);
    }

    // Escucha a cada boton de previsualizacion para activar el prev y establecer la posición inicial con la funcion setPos
    /* for (let btnPre of btnsPrev) {
        btnPre.addEventListener('click',()=>{
            for (let elemento of datosArchivo) {
                if(elemento.id == btnPre.id && elemento.tipo == '1'){
                    var posPrev = 0;
                    for (let imgPrev of contenedorMedia.children) {
                        posPrev++;
                        if(imgPrev.src.replace(url,'') == elemento.ruta){
                            imgPrev.style.display = 'block';
                            setPos(posPrev);
                        }
                    }
                    preVisualizador.style.display = 'block';
                }
            }
        });
    } */

    // Escucha a los trigers de derecha e izquierda respectivamente para hacer el desplazamiento
    /* derPrev.addEventListener('click',()=>{
        for (let imgPrev of contenedorMedia.children) {
            imgPrev.style.display = 'none';
        }
        if(pos+1 > contenedorMedia.children.length-1){
            contenedorMedia.children[0].style.display = 'block';
            pos = 0;
        }else{
            contenedorMedia.children[pos+1].style.display = 'block';
            pos++;
        }
    });
    izqPrev.addEventListener('click',()=>{
        for (let imgPrev of contenedorMedia.children) {
            imgPrev.style.display = 'none';
        }
        if(pos-1 < 0){
            contenedorMedia.children[contenedorMedia.children.length-1].style.display = 'block';
            pos = contenedorMedia.children.length-1;
        }else{
            contenedorMedia.children[pos-1].style.display = 'block';
            pos--;
        }
    }); */

    // Escucha al botón para cerrar el previsualizador, lo oculta y a todas sus imagenes hijas tambien
    /* btnCerrarPrev.addEventListener('click',()=>{
        for (let imgPrev of contenedorMedia.children) {
            imgPrev.style.display = 'none';
        }
        preVisualizador.style.display = "none";
    }); */

    // ----------------------- Para la subida "Asincrona"
    // Escucha al input de tipo file, cuando tenga archivos seleccionados se mostrará el punto rojo.
    inpArchivo.addEventListener('input',()=>{
        puntoRojo.style.display = (inpArchivo.files.length > 0) ? "block" : "none";
        inpCarpeta.disabled = true;
    });
    inpCarpeta.addEventListener('input',()=>{
        puntoRojoC.style.display = (inpCarpeta.files.length > 0) ? "block" : "none";
        inpArchivo.disabled = true;
    });
    
    // Llamada a la función subirArchivos
    btnSubir.addEventListener('click',()=>{
        subirArchivos();
    });

    // Llamada a la función crearCarpeta
    btnCrearCarpeta.addEventListener('click',()=>{
        crearCarpeta();
    });

    // Escucha al input checkNotTodos para marcar todos los correos como seleccionados
    /* checkNotTodos.addEventListener('click',()=>{
        if(checkNotTodos.checked){
            for (const check of checksCorreos) {
                check.checked = true;
            }
        }else{
            for (const check of checksCorreos) {
                check.checked = false;
            }
        }
    }); */

    // Escucha al checkNotificar para habilitar la selección de los correos que serán notificados
    checkNotificar.addEventListener('click',()=>{
        if(checkNotificar.checked){
            // for (const check of checksCorreos) {
                usrsNotificar.disabled = false;
            // }
        }else{
            // for (const check of checksCorreos) {
                usrsNotificar.disabled = true;
            // }
        }
    });

    usrsNotificar.addEventListener('change',(e)=>{
        if(!correosNotificar.includes(e.target.value)){
            correosNotificar.push(e.target.value);
            crearTag();
        }
        usrsNotificar.value = "";
    });

});

