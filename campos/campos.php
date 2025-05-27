<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambosoft: Iniciar sesión</title>
  <link rel="stylesheet" href="estilos.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head> 

<body class="container ">
  <div class="container row" id="contenedor_principal">
    <div class="container col-2 " id="contenedor_menu">
      <input class="btn btn-primary botones" type="button" value="Campos">
      <input class="btn btn-primary botones" type="button" value="Animales">
      <input class="btn btn-primary botones" type="button" value="Alimentos">
    </div>
    <div class="col-10" id="contenedor_gestiones">
      <div class="row titulos"><h1>Campos</h1></div>
      <div class="row contenedor_campo_potrero ">         <!--Gestion campos -->
        <div class="col-3 contenedor_botones align-self-center">
          <input class="btn btn-primary botones" type="button" value="Registrar" onclick="registrar_campo()">
          <input class="btn btn-primary botones" type="button" value="Modificar" onclick="modificar_campo()">
          <input class="btn btn-primary botones" type="button" value="Consultar">
          <input class="btn btn-primary botones" type="button" value="Eliminar" onclick="eliminar_campo()">
        </div>
        <div class="col-9 contenedor_formulario">
          <form class="row h-50 justify-content-center align-items-center" action="" id="alta_campo">  <!-- FORMULARIO REGISTRO CAMPO -->
            <div class="row w-75">
              <label>Nombre de campo</label>
              <input type="text" placeholder="Nombre">
            </div>
            <div class="row w-75">
              <label>Superficie de Campo</label>
              <input type="text" placeholder="Hectareas">
            </div>
            <div class="row w-50 align-self-end">
              <button>Registrar campo</button>
            </div>
          </form>
          <form class="row h-50 justify-content-center align-items-center" action="" id="modificar_campo">  <!-- FORMULARIO MODIFICAR CAMPO -->
            <div class="row w-75">
              <label>Campo</label>
              <select name="opciones_campo" id="opciones_campo">
                <option value="rey">Rey</option>
                <option value="el escondido">El escondido</option>
                <option value="el vasco">El Vasco</option>
                <option value="don chelo">Don Chelo</option>
                <option value="las violetas">Las violetas</option>
                <option value="blanca">Blanca</option>
              </select>
            </div>
            <div class="row w-75">
              <input type="text" placeholder="Superficie">
            </div>
            <div class="row w-50 align-self-end">
              <button>Modificar campo</button>
            </div>
          </form>
          <form class="row h-50 justify-content-center align-items-center" action="" id="eliminar_campo">  <!-- FORMULARIO ELIMINAR CAMPO -->
            <div class="row w-75">
              <label>Campo</label>
              <select name="opciones_campo" id="opciones_campo">
                <option value="rey">Rey</option>
                <option value="el escondido">El escondido</option>
                <option value="el vasco">El Vasco</option>
                <option value="don chelo">Don Chelo</option>
                <option value="las violetas">Las violetas</option>
                <option value="blanca">Blanca</option>
              </select>
            </div>
            <div class="row w-50 align-self-end">
              <button>Eliminar campo</button>
            </div>
          </form>
        </div>
      </div>
      <div class="row titulos"><h1>Potreros</h1></div>            
      <div class="row contenedor_campo_potrero">                      <!--Gestion potreros -->
        <div class="col-3 contenedor_botones align-self-center">
          <input class="btn btn-primary botones" type="button" value="Registrar">
          <input class="btn btn-primary botones" type="button" value="Modificar">
          <input class="btn btn-primary botones" type="button" value="Consultar">
          <input class="btn btn-primary botones" type="button" value="Eliminar">
        </div>
        <div class="col-9 contenedor_formulario">
          
        </div>
      </div>
    </div>
  </div>
  <script src="javascript.js"></script>
</body>

</html>