<?php
session_start();
require_once('./db/config.php');

if(isset($_SESSION['id_user'])){
    header('Location: ./index.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nom = $_POST['nom'];
    $cognom = $_POST['cognom'];
    $email = $_POST['email'];
    $foto_perfil_url = $_POST['foto_perfil_url'];
    $password = $_POST['password'];

    $user_exist = $mysqli -> query("SELECT us.email FROM USUARIS us where us.email = '".$email."'");
    if($user_exist -> num_rows > 0){
        echo "EL USUARIO YA EXISTE";
    }else{
        $password_hasheada = password_hash($password,PASSWORD_DEFAULT);
        $stmt = $mysqli -> prepare("INSERT INTO USUARIS (nom,cognoms,email,contrasenya_hash,foto_perfil,id_rol) VALUES (?,?,?,?,?,?)");
        if(!$stmt){
            die("ERROR AL PREPARAR LA QUERY". $mysqli -> error);
        }
        $id_role = 2;
        $stmt -> bind_param("sssssi",$nom,$cognom,$email,$password_hasheada,$foto_perfil_url,$id_role);
        if($stmt -> execute()){
            header('Location: ./login.php');
            exit();
        }else{
            echo "ERROR AL EJECUTAR".$mysqli -> error;
        }
    }


}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre - BlueWave Hotels</title>
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
            padding: 3rem 1rem;
            color: #333;
        }

        .register-container {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
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

        .register-container h2 {
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        }

        /* Diseño para columnas en pantallas grandes */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .btn-register {
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

        .btn-register:hover {
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
        @media (max-width: 550px) {
            .register-container {
                margin: 0;
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                /* Una columna en móviles */
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="logo">BlueWave Hotels</div>
        <h2>Crea el teu compte d'usuari</h2>

        <form action="#" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" placeholder="El teu nom" required>
                </div>

                <div class="form-group">
                    <label for="cognom">Cognom</label>
                    <input type="text" id="cognom" name="cognom" placeholder="El teu cognom" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correu electrònic</label>
                <input type="email" id="email" name="email" placeholder="correu@exemple.com" required>
            </div>

            <div class="form-group">
                <label for="foto_perfil_url">URL de la Foto de Perfil (Opcional)</label>
                <input type="url" id="foto_perfil_url" name="foto_perfil_url" placeholder="https://example.com/meva-foto.jpg">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Contrasenya</label>
                    <input type="password" id="password" name="password" placeholder="Mínim 8 caràcters" required>
                </div>
            </div>

            

            <button type="submit" class="btn-register">Registrar-se</button>
        </form>

        <div class="link-text">
            Ja tens un compte? <a href="./login.php">Inicia sessió</a>
        </div>
        <div class="link-text">
            <a href="./index.php">Tornar a l'inici</a>
        </div>
    </div>
</body>

</html>