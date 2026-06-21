const contenedorAuth = document.getElementById("contenedorAuth");
const mostrarRegistro = document.getElementById("mostrarRegistro");
const mostrarLogin = document.getElementById("mostrarLogin");

mostrarRegistro.addEventListener("click", function(e){
    e.preventDefault();
    contenedorAuth.classList.add("activo");
});

mostrarLogin.addEventListener("click", function(e){
    e.preventDefault();
    contenedorAuth.classList.remove("activo");
});