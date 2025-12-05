<?php
session_start();
require_once('../db/config.php');
if(!isset($_SESSION['id_user'])){
    header('Location: ./index.php');
    exit();
}

if($_SESSION['rol'] != 'Administrador'){
    header('Location: ./no_permisos.php');
    exit();
}
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $numGuests = $_POST['numGuests'];
    $habitacionReservada = $_POST['roomType'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];
    $status = $_POST['status'];
    // AÑADIR 
    $stmt = $mysqli -> prepare("INSERT INTO RESERVES (id_client,id_habitacio,data_entrada,data_sortida,id_estat,preu_total) VALUES (?,?,?,?,?,?)");
    if(!$stmt){
        die("ERROR AL PREPARAR".$mysqli -> error);
    }
    $id_user = $_SESSION['id_user'];
    $stmt -> bind_param("iissii",$id_user,$habitacionReservada,$checkInDate,$checkOutDate,$status,$numGuests);
    if($stmt -> execute()){
        header('Location: ../admin_panel.php');
        exit();
    }
    
}
$datos_estado = $mysqli -> query("SELECT * FROM ESTATS_RESERVA");
$habitaciones_disponibles = $mysqli -> query("SELECT * FROM HABITACIONS hb JOIN TIPUS_HABITACIO tph ON tph.id_tipus= hb.id_tipus WHERE hb.id_estat_hab = 1");

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afegir Nova Reserva - BlueWave Hotels</title>
    <style>
        /* Estils Base i Layout (Heretats de admin_panel_dashboard.html) */
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
        /* 3. Estils del Formulari d'Afegir Reserva */
        /* ---------------------------------- */

        .form-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            max-width: 900px;
            /* Limitem l'amplada per a un formulari millor */
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

            .form-grid {
                grid-template-columns: 1fr;
                /* Una sola columna en mòbils */
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
                <!-- Mantenim Reserves actiu, ja que és la secció on som -->
                <li><a href="#" class="active">🏨 Reserves</a></li>
                <li><a href="#">👥 Clients</a></li>
                <li><a href="#">🚪 Habitacions</a></li>
                <li><a href="#">⚙️ Configuració</a></li>
            </ul>
        </nav>
    </div>

    <!-- CONTINGUT PRINCIPAL -->
    <div class="main-content">
        <!-- CAPÇALERA DE PÀGINA -->
        <div class="header-admin">
            <h1>Afegir Nova Reserva</h1>
            <div class="user-info">
                <span>Hola, Administrador!</span>
                <a href="../index.php" class="btn-logout-admin">Tornar a l'Inici</a>
            </div>
        </div>

        <!-- SECCIÓ DEL FORMULARI -->
        <div class="form-section">
            <div class="form-header">
                <h2>Detalls de la Reserva</h2>
            </div>

            <!-- FORMULARI -->
            <!-- Sense acció ni mètode definits, el formulari enviarà les dades i recarregarà la pàgina actual. -->
            <form id="addReservationForm" action="#" method="POST">
                <div class="form-grid">
                    <!-- Columna 1 -->
                    <div class="column-1">
                        

                        <div class="form-group">
                            <label for="roomType">Tipus d'Habitació</label>
                            <select id="roomType" name="roomType" required>
                                <? foreach($habitaciones_disponibles as $habitacion): ?>
                                    <option value="<?= $habitacion['id_habitacion'] ?>" disabled selected><?=  $habitacion['nom_tipus']?></option>
                                <? endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="numGuests">Nombre de Persones</label>
                            <input type="number" id="numGuests" name="numGuests" value="1" min="1" max="5" required>
                        </div>
                    </div>

                    <!-- Columna 2 -->
                    <div class="column-2">
                        <div class="form-group">
                            <label for="checkInDate">Data d'Entrada</label>
                            <input type="date" id="checkInDate" name="checkInDate" required>
                        </div>

                        <div class="form-group">
                            <label for="checkOutDate">Data de Sortida</label>
                            <input type="date" id="checkOutDate" required>
                        </div>
                    </div>
                </div>

                <!-- Estat i Notes (Ocupen tota l'amplada) -->
                <div class="form-grid">
                    <div class="form-group">
                        <label for="status">Estat de la Reserva</label>
                        <select id="status" name="status" required>
                            <? foreach($datos_estado as $estado): ?>    
                                <option value="<?= $estado['id_estat'] ?>"><?= $estado['nom_estat'] ?></option>
                            <? endforeach ?>
                        </select>
                    </div>


                </div>

                <!-- Botons d'Acció -->
                <div class="form-actions">
                    <a href="./admin_panel_dashboard.html" class="btn-cancel">Cancel·lar</a>
                    <button type="submit" class="btn-save">Guardar Reserva</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>