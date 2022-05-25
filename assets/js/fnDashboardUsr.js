document.addEventListener('DOMContentLoaded',()=>{
    const url = "http://localhost/invirtualCloud/";
    
    var btnsPrev = document.getElementsByClassName('btnPrev');
    var preVisualizador = document.getElementById('preVisualizador');
    var btnCerrarPrev = document.getElementById('btnCerrarPrev');
    var infosArchivo = document.getElementsByClassName('infoArchivo');
    var derPrev = document.getElementById('derPrev');
    var izqPrev = document.getElementById('izqPrev');
    var datosArchivo;
    var imagenes = document.getElementsByClassName('archivoImagen');
    var contenedorMedia = document.getElementById('contenedorMedia');
    var pos = 0;

    /* ------------------------------------- Puestas en marcha cuando carga la página ------------------------------------------ */

    // Llamada asincrona para obtener los datos de los archivos de BD
    fetch(`${url}index.php/Dashboard/mostrarArchivosAs`,{
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then((respuesta)=>{
        return respuesta.json();
    }).then((res)=>{
        datosArchivo = res;
    });


    /* ------------------------------------- Funciones ------------------------------------------ */


    // ----------------------- Para el slider
    // Función setPos: establece la posicion actual para el slider una vez que se dió click a una imagen
    function setPos(num){
        pos = num-1;
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
    for (let btnPre of btnsPrev) {
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
                }/*else{
                    fuenteImg.style.display = "none";
                    reprouctor.style.display = "none";
                    prevArchivo.style.display = "block";
                    preVisualizador.style.display = 'block';
                }*/
            }
        });
    }

    // Escucha a los trigers de derecha e izquierda respectivamente para hacer el desplazamiento
    derPrev.addEventListener('click',()=>{
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
    });

    // Escucha al botón para cerrar el previsualizador, lo oculta y a todas sus imagenes hijas tambien
    btnCerrarPrev.addEventListener('click',()=>{
        for (let imgPrev of contenedorMedia.children) {
            imgPrev.style.display = 'none';
        }
        preVisualizador.style.display = "none";
    });

});

