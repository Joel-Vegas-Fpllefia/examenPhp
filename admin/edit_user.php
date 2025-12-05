<?php
session_start();
require_once('../db/config.php');

if(!isset($_SESSION['id_user'])){
    header('Location: ../index.php');
    exit();
}
if($_SESSION['rol'] != 'Administrador'){
    header('Location: ../no_permisos.php');
    exit();
}
$id_user = $_GET['id'];
$roles_tipos = $mysqli -> query("SELECT * FROM ROLES");
$roles = $roles_tipos -> fetch_all(MYSQLI_ASSOC);
$datos_user = $mysqli -> query("SELECT * FROM USUARIS WHERE id_usuari = ".$id_user);
$usuario = $datos_user -> fetch_assoc();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $profilePhotoUrl = $_POST['profilePhotoUrl'];
    $id_rol = $_POST['id_rol'];
    
    $password_hasheada = password_hash($password,PASSWORD_DEFAULT);

    $stmt = $mysqli -> prepare("UPDATE USUARIS SET nom=?, cognoms = ?,email  = ?,contrasenya_hash = ?, foto_perfil = ?, id_rol = ? WHERE id_usuari  = ?");
    if(!$stmt){
        die("ERROR AL PREPARAR LA QUERY");
    }
    $stmt -> bind_param("sssssii",$firstName,$lastName,$email,$password_hasheada,$profilePhotoUrl,$id_rol,$id_user);
    if($stmt -> execute()){
        header('Location: ../admin_panel.php');
        exit();
    }else{
        echo $mysqli -> error;
    }

}


?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Client - BlueWave Hotels</title>
    <style>
        /* Estils Base i Layout (Heretats del panell d'administració) */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f9;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* 1. Barra Lateral (Sidebar) */

        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #004999 0%, #002e5c 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100%;
            overflow-y: auto;
            transition: width 0.3s;
        }

        .logo-admin {
            text-align: center;
            padding: 1rem 0;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-admin::before {
            content: '🌊 Admin';
            color: #60a5fa;
        }

        .nav-menu ul {
            list-style: none;
            padding: 0;
        }

        .nav-menu li a {
            display: block;
            padding: 1rem 1.5rem;
            color: #c9e0ff;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
            font-weight: 500;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background: #0066cc;
            color: white;
            border-left: 5px solid #60a5fa;
            padding-left: 1rem;
        }

        /* 2. Contingut Principal */

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 2rem;
            transition: margin-left 0.3s;
        }

        .header-admin {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-admin h1 {
            color: #0066cc;
            font-size: 2rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
        }

        .btn-logout-admin {
            background: #ff5252;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .btn-logout-admin:hover {
            background: #cc0000;
        }

        /* ---------------------------------- */
        /* 3. Estils del Formulari d'Editar Client */
        /* ---------------------------------- */

        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 900px;
            margin: 0 auto;
        }

        .form-header h2 {
            color: #004999;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0066cc;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
        }

        /* Camp d'adreça/text sencer */
        .form-group-full {
            grid-column: 1 / -1;
        }

        /* Accions del formulari */
        .form-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-save,
        .btn-cancel {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-save {
            background: #0066cc;
            /* Blau de BlueWave */
            color: white;
        }

        .btn-save:hover {
            background: #004999;
        }

        .btn-cancel {
            background: #e2e8f0;
            /* Gris */
            color: #333;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
        }

        /* Adaptabilitat per a mòbils */
        @media (max-width: 900px) {
            .sidebar {
                width: 80px;
            }

            .logo-admin {
                font-size: 0;
            }

            .logo-admin::before {
                content: '🌊';
                font-size: 2rem;
            }

            .nav-menu li a {
                padding: 1rem 0.5rem;
                text-align: center;
                font-size: 0;
            }

            .nav-menu li a::before {
                content: '•';
                font-size: 1.5rem;
            }

            .main-content {
                margin-left: 80px;
                padding: 1rem;
            }

            .header-admin h1 {
                font-size: 1.5rem;
            }

            .form-actions {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- BARRA LATERAL -->
    <div class="sidebar">
        <div class="logo-admin">BlueWave CMS</div>
        <nav class="nav-menu">
            <ul>
                <li><a href="../admin_panel.php">📊 Panell</a></li>
                <li><a href="#">🏨 Reserves</a></li>
                <!-- ACTIVE: Clients -->
                <li><a href="#" class="active">👥 Clients</a></li>
                <li><a href="#">🚪 Habitacions</a></li>
                <li><a href="#">⚙️ Configuració</a></li>
            </ul>
        </nav>
    </div>

    <!-- CONTINGUT PRINCIPAL -->
    <div class="main-content">
        <!-- CAPÇALERA DE PÀGINA -->
        <div class="header-admin">
            <h1>Editar Client</h1>
            <div class="user-info">
                <span>Hola, Administrador!</span>
                <!-- RUTA: Torna a l'index.php (al mateix directori /admin) -->
                <a href="../index.php" class="btn-logout-admin">Tornar a l'Inici</a>
            </div>
        </div>

        <!-- SECCIÓ DEL FORMULARI -->
        <div class="form-section">
            <div class="form-header">
                <h2>Informació de l'Usuari</h2>
            </div>
            
            <!-- FORMULARI - ACCIÓ ACTUALITZADA: Utilitzem clients_edit.php i mètode POST -->
            <form id="editClientForm" action="#" method="post">
                <!-- CAMP OCULT PER IDENTIFICAR EL CLIENT A LA BASE DE DADES -->
                <input type="hidden" name="clientId" value="CL-004589">
                <div class="form-group">
                <label for="roomType">Tipus Role</label>
                <!-- DADES SIMULADES -->
                <select id="roomType" name="id_rol" required>
                    <? foreach($roles as $role): ?>
                        <option value="<?= $role['id_rol'] ?>"><?= $role['nom_rol'] ?></option>
                    <? endforeach ?>
                </select>
            </div>
                <div class="form-grid">
                    <!-- Fila 1: Nom, Cognoms -->
                    <div class="form-group">
                        <label for="firstName">Nom</label>
                        <!-- SIMULACIÓ DE DADA CARREGADA -->
                        <input type="text" id="firstName" name="firstName" value="<?= $usuario['nom'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Cognoms</label>
                        <!-- SIMULACIÓ DE DADA CARREGADA -->
                        <input type="text" id="lastName" name="lastName" value="<?= $usuario['cognoms'] ?>" required>
                    </div>

                    <!-- Fila 2: Correu Electrònic, Contrasenya -->
                    <div class="form-group">
                        <label for="email">Correu Electrònic</label>
                        <!-- SIMULACIÓ DE DADA CARREGADA -->
                        <input type="email" id="email" name="email" value="<?= $usuario['email'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contrasenya</label>
                        <!-- NOTA: Es deixa buit. S'introdueix només si es vol canviar. -->
                        <input type="password" id="password" name="password" placeholder="Deixa buit per no canviar la contrasenya">
                    </div>

                    <!-- Fila 3: Data Naixement, Foto de Perfil (URL) -->
                    <div class="form-group">
                        <label for="profilePhotoUrl">Foto de Perfil (URL)</label>
                        <!-- SIMULACIÓ DE DADA CARREGADA -->
                        <input type="url" id="profilePhotoUrl" name="profilePhotoUrl" value="<?= $usuario['foto_perfil'] ?>" placeholder="https://exemple.com/imatge.jpg">
                    </div>
                </div>

                <!-- Botons d'Acció -->
                <div class="form-actions">
                    <!-- RUTA: Torna al panell d'administració principal -->
                    <a href="../admin_panel.php" class="btn-cancel">Cancel·lar</a>
                    <!-- TEXT DEL BOTÓ ACTUALITZAT -->
                    <button type="submit" class="btn-save">Actualitzar Client</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>