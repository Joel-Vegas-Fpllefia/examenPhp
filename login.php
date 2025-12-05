<?php
    session_start();
    require_once('./db/config.php');
    if(isset($_SESSION['id_user'])){
        header('Location: ./index.php');
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user_exist = $mysqli -> query("SELECT us.id_usuari,us.nom,us.cognoms,us.email, us.contrasenya_hash,us.foto_perfil,rl.nom_rol FROM USUARIS us JOIN ROLES rl ON us.id_rol = rl.id_rol WHERE us.email = '".$email."'");
        if($user_exist -> num_rows > 0){
            $datos = $user_exist -> fetch_assoc();
            if(password_verify($password,$datos['contrasenya_hash'])){
                $_SESSION['id_user'] = $datos['id_usuari'];
                $_SESSION['nom'] = $datos['nom'];
                $_SESSION['cognoms'] = $datos['cognoms'];
                $_SESSION['email'] = $datos['email'];
                $_SESSION['contrasenya_hash'] = $datos['contrasenya_hash'];
                $_SESSION['foto_perfil'] = $datos['foto_perfil'];
                $_SESSION['rol'] = $datos['nom_rol'];

                header('Location: ./index.php');
                exit();
            }else{
                echo "LA PASSWORD ES INCORECTA";
            }
        }else{
            echo "EL USUARIO NO EXISTE";
        }
    }
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessió - BlueWave Hotels</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Fondo degradado suave */
            background: linear-gradient(135deg, #e0f2ff 0%, #bfdbfe 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #0066cc;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }

        .logo::before {
            content: '🌊';
            font-size: 2.5rem;
        }

        .login-container h2 {
            font-size: 1.8rem;
            color: #004999;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #475569;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        }

        .btn-login {
            width: 100%;
            background: #0066cc;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: #004999;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .link-text {
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: #64748b;
        }

        .link-text a {
            color: #0066cc;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .link-text a:hover {
            color: #004999;
            text-decoration: underline;
        }

        /* Media Queries para adaptabilidad */
        @media (max-width: 500px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo">BlueWave Hotels</div>
        <h2>Inicia la teva sessió</h2>

        <form action="#" method="POST">
            <div class="form-group">
                <label for="email">Correu electrònic</label>
                <input type="email" id="email" name="email" placeholder="example@bluewave.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contrasenya</label>
                <input type="password" id="password" name="password" placeholder="********" required>
            </div>

            <button type="submit" class="btn-login">Accedir</button>
        </form>

        <div class="link-text">
            No tens un compte? <a href="./register.php">Registra't aquí</a>
        </div>
        <div class="link-text">
            <a href="./index.php">Tornar a l'inici</a>
        </div>
    </div>
</body>

</html>