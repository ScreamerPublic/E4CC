<?php

// $password = 'Diego1234';
// $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// echo $hashedPassword;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php include('./assets/incluir/estilos.php');  ?>
</head>

<body>
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
					<div class="text-center my-5">
						<img src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="logo" width="100">
					</div>
					<div class="card shadow-lg">
						<div class="card-body p-5">
							<h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
							<form method="POST" id="loginForm" class="needs-validation" novalidate="" autocomplete="off">
                            <div id="alertContainer"></div>
                                <div class="mb-3">
									<label class="mb-2 text-muted" for="email">Usuario</label>
									<input id="nombreUsuario" type="text" class="form-control" name="nombreUsuario" value="" required autofocus>
									<div class="invalid-feedback">
										
									</div>
								</div>

								<div class="mb-3">
									<div class="mb-2 w-100">
										<label class="text-muted" for="contrasena">Contraseña</label>
										
									</div>
									<input id="contrasena" type="password" class="form-control" name="contrasena" required>
								    <div class="invalid-feedback">
								    	Es requerido
							    	</div>
								</div>

								<div class="d-flex align-items-center">
									<div class="form-check">
										<input type="checkbox" name="remember" id="remember" class="form-check-input">
										<label for="remember" class="form-check-label">Remember Me</label>
									</div>
									<button type="button" onclick="login()" class="btn btn-primary ms-auto">
										Ingresar
									</button>
								</div>
							</form>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Don't have an account? <a href="register.html" class="text-dark">Create One</a>
							</div>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						Copyright &copy; 2017-2021 &mdash; Your Company 
					</div>
				</div>
			</div>
		</div>
	</section>
    <?php include('./assets/incluir/guion.php');  ?>
</body>
</html>
<script>
        function login() {
            var username = document.getElementById('nombreUsuario').value;
            var password = document.getElementById('contrasena').value;

            var data = {
                username: username,
                password: password
            };
        
            fetch('<?php echo base_url('login/autenticar'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    // Redirige a la página de inicio
                    location.reload();
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function showAlert(type, message) {
            var alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
        }

    </script>