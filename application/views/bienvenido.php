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
      <h2 class="h2">Pagos registrados <button <?=(($this->session->userdata('usuario')['rol']=='administrador')?"":'style="display: none;"')?> type="button" onclick="obtenerFormulario();" class="btn btn-info">Nuevo</button></h2>
      <div id="gridContainer"></div>

    </section>
    <div class="text-center mt-5">
        <?php
          if( $this->session->userdata('usuario')['rol']=='administrador' ){
            echo '<button type="button" onclick="javascript: history.back();" class="btn btn-warning btn-block">Volver</button>';
          }
        ?>
    </div>
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
    let idusuario = <?=$usuario['id_usuario']?>;
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

    function obtenerHistorialPagos(){
      // Supongamos que tienes los datos de ingresos de pagos en un array llamado 'ingresosPagos'
      $.ajax({
        url : '<?php echo base_url('login/obtenerHistorialPagos'); ?>',
        type : 'POST',
        dataType : 'json',
        data : { id : idusuario},
        success : ( respuesta ) =>{

          // Configurar el dxDataGrid
          $("#gridContainer").dxDataGrid({
              dataSource: respuesta, // Usar los datos de ingresosPagos como fuente de datos
              columns: [
                  { dataField: "id", caption: "ID" },
                  { dataField: "tipo_pago", caption: "Tipo de Pago" },
                  { dataField: "cantidad", caption: "Cantidad" },
                  { 
                    dataField: "monto_pagar", 
                    caption: "Monto a Pagar",
                    dataType: "number", // Especifica el tipo de dato como "number"
                    format: {
                        type: "currency", // Utiliza el tipo de formato de moneda
                        precision: 2, // Especifica la cantidad de decimales
                        currency: "USD" // Define la moneda como dólar estadounidense
                    },
                    alignment: "right" 
                  },
                  { 
                    dataField: "fecha_pagar", 
                    caption: "Fecha a Pagar", 
                    dataType: "date", 
                    format: "dd-MM-yyyy",
                    alignment: "center" 
                  },
                  { dataField: "comentarios", caption: "Comentarios" }
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

    obtenerHistorialPagos();

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
        // Configuración del formulario de registro de pagos utilizando DevExtreme
        var formFields = [{
            dataField: "tipoPago",
            label: { text: "Tipo de Pago" },
            editorType: "dxSelectBox",
            editorOptions: {
                dataSource: ["Bonos", "Horas extras"]
            }
        }, {
            dataField: "cantidad",
            label: { text: "Cantidad" },
            editorType: "dxNumberBox"
        }, {
            dataField: "montoPagar",
            label: { text: "Monto por Pagar" },
            editorType: "dxNumberBox"
        }, {
            dataField: "fechaPagar",
            label: { text: "Fecha a Pagar" },
            editorType: "dxDateBox",
            editorOptions: {
                displayFormat: "dd-MM-yyyy" // Formato de fecha: día-mes-año
            }
        }, {
            dataField: "comentarios",
            label: { text: "Comentarios" },
            editorType: "dxTextArea"
        }];
      
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
        var tipoPago = formulario.getEditor("tipoPago").option("value");
        var cantidad = formulario.getEditor("cantidad").option("value");
        var montoPagar = formulario.getEditor("montoPagar").option("value");
        var fecha = formulario.getEditor("fechaPagar").option("value");
        // Formatear la fecha en el formato deseado ("d-m-Y")
        var fechaFormateada = fecha.getDate() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getFullYear();

        var comentarios = formulario.getEditor("comentarios").option("value");

        // Crear objeto con los datos del formulario
        var formData = {
            tipoPago: tipoPago,
            cantidad: cantidad,
            montoPagar: montoPagar,
            fechaPagar: fechaFormateada,
            comentarios: comentarios,
            id_usuario: idusuario,
        };
      
        // Enviar los datos del formulario al controlador a través de una solicitud AJAX
        $.ajax({
            url: '<?php echo base_url('login/guardarPagos');?>', // Reemplaza con la URL correcta de tu controlador
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            success: function(response) {
                // Manejar la respuesta del controlador si es necesario
                // console.log("Respuesta del controlador:", response);
                mostrarMensaje(response.mensaje, response.tipo);
                obtenerHistorialPagos();
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

    function mostrarMensaje(mensaje, tipo) {
        // Crear el elemento de la alerta
        var alerta = document.createElement("div");
        alerta.classList.add("alert", tipo);
        alerta.textContent = mensaje;

        // Insertar la alerta antes del formulario (o en otro lugar de tu elección)
        var formulario = document.getElementById("form-container");
        formulario.parentNode.insertBefore(alerta, formulario);

        // Ocultar la alerta después de unos segundos
        setTimeout(function() {
            alerta.remove();
        }, 5000);
    }
</script>
