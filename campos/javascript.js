function registrar_campo(){
    document.getElementById('alta_campo').style.display="flex";
    document.getElementById('modificar_campo').style.display="none";
    document.getElementById('eliminar_campo').style.display="none";
}

function modificar_campo(){
    document.getElementById('alta_campo').style.display="none";
    document.getElementById('modificar_campo').style.display="flex";
    document.getElementById('eliminar_campo').style.display="none";
}

function eliminar_campo(){
    document.getElementById('alta_campo').style.display="none";
    document.getElementById('modificar_campo').style.display="none";
    document.getElementById('eliminar_campo').style.display="flex";
}