<?php
session_start();
require_once('../db/config.php');
if(!$_SESSION['id_user']){
    header('Location: ../index.php');
    exit();
}

if($_SESSION['rol'] != 'Administrador'){
    header('Location: ../no_permisos.php');
    exit();
}

$tipus_habitacions = $mysqli -> query("SELECT tp.id_tipus,tp.nom_tipus FROM TIPUS_HABITACIO tp");
$tipos = $tipus_habitacions -> fetch_all(MYSQLI_ASSOC);
$estados_hb = $mysqli -> query("SELECT et.id_estat_hab, et.nom_estat_hab FROM ESTATS_HABITACIO et ");
$estados = $estados_hb -> fetch_all(MYSQLI_ASSOC);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $roomNumber = $_POST['roomNumber'];
    $capacity = $_POST['capacity'];
    $pricePerNight = $_POST['pricePerNight'];
    $roomType = $_POST['roomType'];
    $roomStatus  = $_POST['roomStatus'];

    $exist_number_room = $mysqli -> query("SELECT hb.numero_habitacio FROM HABITACIONS hb WHERE hb.numero_habitacio = ".$roomNumber);
    if($exist_number_room -> num_rows > 0){
        echo "EL NUMERO DE LA HABITACION YA EXISTE";
    }else{
        $stmt = $mysqli -> prepare("INSERT INTO HABITACIONS (numero_habitacio,capacitat,preu_base,id_tipus,id_estat_hab) VALUES (?,?,?,?,?)");
        if(!$stmt){
            die("ERROR AL PREPARAR LA QUERY". $mysqli -> error);
        }
        $stmt -> bind_param("siiii",$roomNumber,$capacity,$pricePerNight,$roomType,$roomStatus);
        if($stmt -> execute()){
            header('Location: ../admin_panel.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afegir Nova Habitació - BlueWave CMS</title>
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
        /* 3. Estils del Formulari d'Afegir Habitació */
        /* ---------------------------------- */

        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 700px;
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

        /* Camp d'amplada completa per notes o descripcions */
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
            background: #22c55e;
            /* Verd per guardar */
            color: white;
        }

        .btn-save:hover {
            background: #15803d;
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
                <li><a href="./admin_panel.php">📊 Panell</a></li>
                <li><a href="#">🏨 Reserves</a></li>
                <li><a href="#">👥 Clients</a></li>
                <!-- ACTIVE: Habitacions -->
                <li><a href="#" class="active">🚪 Habitacions</a></li>
                <li><a href="#">⚙️ Configuració</a></li>
            </ul>
        </nav>
    </div>

    <!-- CONTINGUT PRINCIPAL -->
    <div class="main-content">
        <!-- CAPÇALERA DE PÀGINA -->
        <div class="header-admin">
            <h1>Afegir Nova Habitació</h1>
            <div class="user-info">
                <span>Hola, Administrador!</span>
                <a href="../index.php" class="btn-logout-admin">Tornar a l'Inici</a>
            </div>
        </div>

        <!-- SECCIÓ DEL FORMULARI -->
        <div class="form-section">
            <div class="form-header">
                <h2>Detalls de l'Habitació</h2>
            </div>

            <!-- FORMULARI - ACCIÓ: Utilitzem rooms_add_process.php i mètode POST -->
            <form id="addRoomForm" action="#" method="post">
                <div class="form-grid">

                    <!-- Fila 1: Número Habitació, Tipus -->
                    <div class="form-group">
                        <label for="roomNumber">Número d'Habitació</label>
                        <input type="text" id="roomNumber" name="roomNumber" placeholder="P. ex.: 101, 203A" required>
                    </div>

                    <div class="form-group">
                        <label for="roomType">Tipus d'Habitació</label>
                        <select id="roomType" name="roomType" required>
                            <? foreach($tipos as $tipo): ?>
                                <option value="<?= $tipo['id_tipus'] ?>"><?= $tipo['nom_tipus'] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>

                    <!-- Fila 2: Preu/Nit, Capacitat -->
                    <div class="form-group">
                        <label for="pricePerNight">Preu per Nit (€)</label>
                        <input type="number" id="pricePerNight" name="pricePerNight" placeholder="P. ex.: 120.00" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="capacity">Capacitat Màxima (Persones)</label>
                        <input type="number" id="capacity" name="capacity" placeholder="P. ex.: 2, 4" min="1" required>
                    </div>

                    <!-- Fila 3: Estat (Es pot canviar manualment, però per defecte hauria de ser "Disponible" o "Neteja Pendent") -->
                    <div class="form-group">
                        <label for="roomStatus">Estat Inicial</label>
                        <select id="roomStatus" name="roomStatus" required>
                            <? foreach($estados as $estado): ?>
                                <option value="<?=  $estado['id_estat_hab']?>" selected><?= $estado['nom_estat_hab'] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>


                </div>

                <!-- Botons d'Acció -->
                <div class="form-actions">
                    <!-- RUTA: Torna a la pàgina de gestió d'habitacions -->
                    <a href="./rooms_management.html" class="btn-cancel">Cancel·lar</a>
                    <button type="submit" class="btn-save">Guardar Habitació</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>