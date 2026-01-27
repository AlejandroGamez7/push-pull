<?php
    include('conexion.php');
    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'];
        $pw = $_POST['pw'];

        $sql = "SELECT ID, USERNAME, PW FROM users WHERE USERNAME = '$username' AND PW = '$pw'";
        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            $fila = $result->fetch_assoc();
            $id = $fila['ID'];
            $_SESSION['ID'] = $id;
            $_SESSION['USERNAME'] = $username;
            header('Location: catalogo.php');
            exit();
        } else{
            $error = "Usuario o Contraseña incorrectas";
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login RetroGroove</title>
      <link rel="icon" type="/image/png" href="img/favicon_o.svg">

    <style>
        /* --- ESTILOS GENERALES (Igual que antes) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #0a0a0a; color: #ffffff; height: 100vh; display: flex; justify-content: center; align-items: center; }
        
        .login-container {
            background-color: #161616;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
            border: 1px solid #333;
        }

        .brand-title { font-size: 2rem; font-weight: 800; letter-spacing: 2px; margin-bottom: 0.5rem; text-transform: uppercase; }
        .brand-subtitle { color: #ccc; font-size: 0.9rem; margin-bottom: 2rem; }

        form { display: flex; flex-direction: column; gap: 1.5rem; }

        input[type="text"],
        input[type="password"] {
            background-color: #222;
            border: 1px solid #444;
            color: white;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            outline: none;
            width: 100%; /* Importante para que llene el contenedor */
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #e85d04;
        }

        /* --- NUEVO: CONTENEDOR DEL PASSWORD --- */
        .password-wrapper {
            position: relative; /* Necesario para posicionar el ojito */
            width: 100%;
        }

        /* El icono del ojo */
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%); /* Centrado vertical exacto */
            cursor: pointer;
            color: #888;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: #e85d04; /* Se pone naranja al pasar el mouse */
        }
        
        /* Ajuste para que el texto no se monte encima del icono */
        input[name="pw"] {
            padding-right: 45px; 
        }

        /* --- BOTÓN --- */
        input[type="submit"] {
            background-color: transparent;
            color: white;
            border: 1px solid white;
            padding: 12px;
            border-radius: 50px;
            font-size: 0.9rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            font-weight: bold;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #e85d04;
            border-color: #e85d04;
            transform: translateY(-2px);
        }

        .error-msg { color: #ff4d4d; font-size: 0.9rem; margin-top: 1rem; }
    </style>
</head>
<body>

    <div class="login-container">
        <h1 class="brand-title">RETROGROOVE</h1>
        <p class="brand-subtitle">Revive el sonido del pasado.</p>
        

        <form action="" method="post">
            <input type="text" placeholder="Username" name="username" required autocomplete="off">
            
            <div class="password-wrapper">
                <input type="password" placeholder="Password" name="pw" id="passwordInput" required>
                
                <span class="toggle-password" id="togglePassword">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </span>
            </div>

            <input type="submit" value="Ingresar">
        </form>

        <?php if(isset($error)): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <script>
        // Lógica Javascript para cambiar el tipo de input
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');

        togglePassword.addEventListener('click', function () {
            // Alternar el atributo type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Alternar el icono (Opcional: cambiar el dibujo del ojo si está abierto o cerrado)
            // Aquí cambiamos el color o añadimos una clase si quisieras, 
            // pero funcionalmente ya sirve para ver/ocultar.
            this.style.color = type === 'text' ? '#e85d04' : '#888';
        });
    </script>

</body>
</html>