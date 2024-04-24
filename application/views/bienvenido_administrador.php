<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenido</title>
  <?php include('./assets/incluir/estilos.php');  ?>
  <!-- Agregar enlaces a las bibliotecas de DevExtreme -->
  <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/21.2.5/css/dx.common.css" />
  <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/21.2.5/css/dx.light.css" />
</head>
<body>
  <header class="header navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="#">Bienvenido</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Perfil</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Mensajes</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Ajustes</a></li>
          <li class="nav-item"><a class="nav-link" href="#" onclick="cerrarSesion();">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </header>

  <main class="main container">
    <section class="welcome mt-5">
      <h1 class="h1">Bienvenido, <?php echo $usuario['nombre_usuario']; ?>!</h1>
      <p class="lead">Aquí puede acceder a las funciones disponibles para su rol de <?php echo $usuario['rol']; ?>.</p>
    </section>

    <section class="user-info mt-5">
      <h2 class="h2">Información de usuario</h2>
      <ul class="list-group">
        <li class="list-group-item">Nombre: <?php echo $usuario['nombres']; ?></li>
        <li class="list-group-item">Apellido: <?php echo $usuario['apellidos']; ?></li>
        <li class="list-group-item">Correo electrónico: <?php echo $usuario['correo_electronico']; ?></li>
        <li class="list-group-item">Estado: <?php echo $usuario['estado'] === 'activo' ? 'Activo' : 'Inactivo'; ?></li>
        <li class="list-group-item">Rol: <?php echo $usuario['rol']; ?></li>
      </ul>
    </section>

    <section class="user-income mt-5">
      <h2 class="h2">Usuarios <button type="button" onclick="obtenerFormulario();" class="btn btn-info">Nuevo</button></h2>
      <div id="gridContainer"></div>
    </section>
    <div id="modal" class="modal fade" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <!-- Contenido del modal aquí -->
    </div>
  </main>

  <?php include('./assets/incluir/guion.php');  ?>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Asegúrate de cargar jQuery antes de DevExtreme -->
  <script src="https://cdn3.devexpress.com/jslib/21.2.5/js/dx.all.js"></script>
</body>
</html>
<script>
    function cerrarSesion() {
        fetch('<?php echo base_url('login/cerrar_sesion'); ?>', {
            method: 'GET'
        })
        .then(response => {
            // Redirigir a la página de inicio de sesión después de cerrar sesión
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function obtenerHistorialUsuarios(){
      // Supongamos que tienes los datos de ingresos de pagos en un array llamado 'ingresosPagos'
      $.ajax({
        url : '<?php echo base_url('login/obtenerHistorialUsuarios'); ?>',
        dataType : 'json',
        success : ( respuesta ) =>{

          // Configurar el dxDataGrid
          $("#gridContainer").dxDataGrid({
              dataSource: respuesta, // Usar los datos de ingresosPagos como fuente de datos
              columnAutoWidth: true, // Permite que las columnas se ajusten automáticamente al contenido
                // Configuración para adaptar el ancho de las columnas en caso de cambio de tamaño de la ventana del navegador
                columnResizingMode: "widget",
              columns: [
                  { dataField: "nombres", caption: "Nombres" },
                  { dataField: "apellidos", caption: "Apellidos" },
                  { dataField: "nombre_usuario", caption: "Usuario" },
                  { dataField: "estado", caption: "Estado" },
                  { dataField: "rol", caption: "ROL" },
                  {
                    caption: "Acciones",
                    width: 150,
                    cellTemplate: function(container, options) {
                        var estado = options.data.estado;
                        if (estado === 'activo') {
                        $("<div>")
                            .append(
                                $("<button>")
                                    .text("Pagos")
                                    .addClass("btn btn-primary mr-2")
                                    .on("click", function() {
                                        // Acción a realizar al hacer clic en el botón "Editar"
                                        var rowData = options.data; // Datos de la fila
                                        // Aquí puedes acceder a los datos de la fila y realizar la acción de edición
                                        console.log("Editar fila:", rowData.id_usuario);

                                        verHistorial(rowData.id_usuario);
                                    })
                            )
                            .append(
                                $("<button>")
                                    .text("Eliminar")
                                    .addClass("btn btn-danger")
                                    .on("click", function() {
                                        var rowData = options.data; // Datos de la fila
                                        console.log(rowData.id_usuario);
                                        desactivarUsuario(rowData.id_usuario);
                                    })
                            )
                            .appendTo(container);
                        }
                    }
                }
              ],
              // Otras configuraciones opcionales como paginación, ordenación, filtros, etc.
              paging: {
                  pageSize: 10
              },
              sorting: {
                  mode: "multiple"
              },
              filterRow: {
                  visible: true
              },
              // Personalización adicional según tus necesidades
          });
        }
      });
    }

    function verHistorial(id = 0){
        location.href = '<?php echo base_url('login/miHistorial'); ?>?id='+id;
    }

    function desactivarUsuario(id = 0){
        $.ajax({
            url: '<?php echo base_url('login/desactivarUsuario');?>', // Reemplaza con la URL correcta de tu controlador
            type: 'POST',
            dataType: 'JSON',
            data: {id},
            success: function(response) {
                // Manejar la respuesta del controlador si es necesario
                // console.log("Respuesta del controlador:", response);
                mostrarMensaje(response.mensaje, response.tipo,'gridContainer');
                obtenerHistorialUsuarios();
                // Cerrar el modal después de enviar el formulario
                setTimeout(() => {
                  cerrarModal();
                }, 2500);
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                // console.error("Error en la solicitud:", status, error);
                mostrarMensaje("¡Ocurrió un error al enviar la peticion!", "alert-danger",'gridContainer');
            }
        });
    }

    obtenerHistorialUsuarios();

    function obtenerFormulario(){
      $.ajax({
        url : '<?php echo base_url('login/obtenerFormulario'); ?>',
        type : 'POST',
        dataType: 'html',
        beforeSend: ()=>{
          var modal = document.getElementById('modal');
          modal.classList.add('show'); // Añadir la clase 'show' para mostrar el modal
          modal.style.display = 'block'; // Asegurar que el modal sea visible
        },
        success : (respuesta)=>{
          $('#modal').html(respuesta);
          crearFormulario();
        }
      });
    }

    function cerrarModal() {
        var modal = document.getElementById('modal');
        modal.innerHTML = ''; // Limpiar el contenido del modal
        modal.classList.remove('show'); // Remover la clase 'show'
        modal.style.display = 'none'; // Ocultar el modal
    }

    function crearFormulario() {
        // Configuración del formulario de registro con DevExtreme
        var formFields = [{
            dataField: "nombres",
            label: { text: "Nombres" },
            editorType: "dxTextBox"
        }, {
            dataField: "apellidos",
            label: { text: "Apellidos" },
            editorType: "dxTextBox"
        }, {
            dataField: "nombre_usuario",
            label: { text: "Nombre de Usuario" },
            editorType: "dxTextBox"
        }, {
            dataField: "contrasena",
            label: { text: "Contraseña" },
            editorType: "dxTextBox",
            editorOptions: {
                mode: "password" // Configurar el campo de contraseña para ocultar la entrada
            }
        }, {
            dataField: "correo_electronico",
            label: { text: "Correo Electrónico" },
            editorType: "dxTextBox"
        }, {
            dataField: "rol",
            label: { text: "Rol" },
            editorType: "dxSelectBox",
            editorOptions: {
                dataSource: ["administrador", "usuario"]
            }
        }];

        // Crear el formulario utilizando dxForm
        $("#form-container").dxForm({
            formData: {},
            items: formFields,
            colCount: 2,
            labelLocation: "top",
            onFieldDataChanged: function(e) {
                console.log(e.dataField + ": " + e.value);
            }
        });
    }

    function enviarFormulario() {
        // Obtener la instancia del formulario DevExtreme
        var formulario = $("#form-container").dxForm("instance");

        // Capturar los valores del formulario
        var nombres = formulario.getEditor("nombres").option("value");
        var apellidos = formulario.getEditor("apellidos").option("value");
        var nombre_usuario = formulario.getEditor("nombre_usuario").option("value");
        var contrasena = formulario.getEditor("contrasena").option("value");
        var correo_electronico = formulario.getEditor("correo_electronico").option("value");
        var rol = formulario.getEditor("rol").option("value");

        // Crear objeto con los datos del formulario
        var formData = {
            nombres: nombres,
            apellidos: apellidos,
            nombre_usuario: nombre_usuario,
            contrasena: contrasena,
            correo_electronico: correo_electronico,
            rol: rol
        };
      
        // Enviar los datos del formulario al controlador a través de una solicitud AJAX
        $.ajax({
            url: '<?php echo base_url('login/guardarUsuario');?>', // Reemplaza con la URL correcta de tu controlador
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            success: function(response) {
                // Manejar la respuesta del controlador si es necesario
                // console.log("Respuesta del controlador:", response);
                mostrarMensaje(response.mensaje, response.tipo);
                obtenerHistorialUsuarios();
                // Cerrar el modal después de enviar el formulario
                setTimeout(() => {
                  cerrarModal();
                }, 2500);
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX
                // console.error("Error en la solicitud:", status, error);
                mostrarMensaje("¡Ocurrió un error al enviar el formulario!", "alert-danger");
            }
        });
    }

    function mostrarMensaje(mensaje, tipo,id = 'form-container') {
        // Crear el elemento de la alerta
        var alerta = document.createElement("div");
        alerta.classList.add("alert", tipo);
        alerta.textContent = mensaje;

        // Insertar la alerta antes del formulario (o en otro lugar de tu elección)
        var formulario = document.getElementById(id);
        formulario.parentNode.insertBefore(alerta, formulario);

        // Ocultar la alerta después de unos segundos
        setTimeout(function() {
            alerta.remove();
        }, 5000);
    }
</script>
