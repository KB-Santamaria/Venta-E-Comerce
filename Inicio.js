const btnMenu = document.getElementById("btnMenu");
const sidebar = document.getElementById("sidebar");

btnMenu.addEventListener("click", function(){
    sidebar.classList.toggle("activo");
});

const dropdownBtns = document.querySelectorAll(".btnCategoria, .btnAdministracion");

dropdownBtns.forEach(function(btn){
    btn.addEventListener("click", function(){
        const contenido = this.nextElementSibling;
        contenido.classList.toggle("activo");
        this.classList.toggle("activo");
    });
});

function mostrarCategoria(categoria){
    document.getElementById("inicio").style.display = "none";

    const secciones = document.querySelectorAll(".categoria-productos");

    secciones.forEach(function(seccion){
        seccion.classList.remove("activo");
    });

    document.getElementById(categoria).classList.add("activo");
}

function mostrarInicio(){
    document.getElementById("inicio").style.display = "block";

    const secciones = document.querySelectorAll(".categoria-productos");

    secciones.forEach(function(seccion){
        seccion.classList.remove("activo");
    });
}

function mostrarCategoria(categoria){
    document.getElementById("inicio").style.display = "none";

    const secciones = document.querySelectorAll(".categoria-productos");

    secciones.forEach(function(seccion){
        seccion.classList.remove("activo");
    });

    document.getElementById(categoria).classList.add("activo");
}