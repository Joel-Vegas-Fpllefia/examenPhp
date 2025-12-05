<?php
session_start();
require_once('./db/config.php');

if(!isset($_SESSION['id_user'])){
    header('Location: ./index.php');
    exit();
}

if($_SESSION['rol'] != 'Administrador'){
    header('Location: ./no_permisos.php');
    exit();
}

$datos_habitaciones = $mysqli -> query("SELECT * FROM HABITACIONS hb JOIN ESTATS_HABITACIO eh ON hb.id_estat_hab = eh.id_estat_hab JOIN TIPUS_HABITACIO tph ON tph.id_tipus = hb.id_tipus");
$datos_usuarios = $mysqli -> query("SELECT * FROM USUARIS us JOIN ROLES rl ON us.id_rol = rl.id_rol");
$reservas = $mysqli -> query("SELECT * FROM RESERVES rs JOIN USUARIS us ON rs.id_client = us.id_usuari JOIN HABITACIONS hb ON hb.id_habitacio = rs.id_habitacio JOIN ESTATS_RESERVA ers ON rs.id_estat = ers.id_estat");
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panell d'Administració - BlueWave Hotels</title>
    <style>
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

        /* ---------------------------------- */
        /* 1. Barra Lateral (Sidebar) */
        /* ---------------------------------- */

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

        /* ---------------------------------- */
        /* 2. Contingut Principal */
        /* ---------------------------------- */

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
        /* 3. Estils de Taulers (Dashboard) */
        /* ---------------------------------- */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #0066cc;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .stat-card .value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #004999;
        }

        /* ---------------------------------- */
        /* 4. Secció de Taules i CRUD (Global) */
        /* ---------------------------------- */

        .table-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 3rem;
            /* Espai entre les seccions de taula */
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 0.5rem;
        }

        .table-header h2 {
            color: #0066cc;
            margin: 0;
            font-size: 1.8rem;
            /* Ajustat per coherència amb el dashboard */
        }

        .btn-add {
            background: #10b981;
            /* Verd per Crear */
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-add:hover {
            background: #059669;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th,
        td {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-actiu {
            background: #dcfce7;
            color: #15803d;
        }

        .status-pendent {
            background: #fef9c3;
            color: #a16207;
        }

        /* Nou estil per a l'estat 'Cancel·lada' */
        .status-cancelada {
            background: #fee2e2;
            color: #991b1b;
        }

        /* Estils dels Botons d'Acció */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete,
        .btn-view {
            padding: 0.5rem 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background 0.3s;
        }

        .btn-edit {
            background: #fcd34d;
            /* Groc per Editar (Nou Estil) */
            color: #78350f;
        }

        .btn-edit:hover {
            background: #fbbf24;
        }

        .btn-delete {
            background: #f87171;
            /* Vermell clar per Esborrar (Nou Estil) */
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #ef4444;
        }

        /* Afegim l'estil de 'Veure' que faltava al nou CSS */
        .btn-view {
            background: #e2e8f0;
            /* Gris clar */
            color: #333;
        }

        .btn-view:hover {
            background: #cbd5e1;
        }


        /* ---------------------------------- */
        /* 5. Adaptabilitat */
        /* ---------------------------------- */

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
        }
    </style>
</head>

<body>
    <!-- BARRA LATERAL -->
    <div class="sidebar">
        <div class="logo-admin">BlueWave CMS</div>
        <nav class="nav-menu">
            <ul>
                <li><a href="#" class="active">📊 Panell</a></li>
                <li><a href="#">🏨 Reserves</a></li>
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
            <h1>Panell de Control</h1>
            <div class="user-info">
                <span>Hola, Administrador!</span>
                <a href="./../index.php" class="btn-logout-admin">Tornar a l'Inici</a>
            </div>
        </div>

        <!-- TAULER D'ESTADÍSTIQUES (DASHBOARD) -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>Reserves Aquesta Setmana</h3>
                <div class="value">45</div>
            </div>
            <div class="stat-card">
                <h3>Clients Registrats </h3>
                <div class="value">1.280</div>
            </div>
            <div class="stat-card">
                <h3>Habitacions Ocupades</h3>
                <div class="value">12 / 20</div>
            </div>
            <div class="stat-card">
                <h3>Ingressos (Mes)</h3>
                <div class="value">24.500€</div>
            </div>
        </div>

        <!-- SECCIÓ: GESTIÓ DE RESERVES -->
        <div class="table-section">
            <div class="table-header">
                <h2>Gestió de Reserves</h2>
                <a href="./admin/reserves_add.php" class="btn-add">➕ Afegir Nova Reserva</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Client</th>
                        
                        <th>data_sortida</th>
                        <th>Data Entrada</th>

                        <th>Estat</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($reservas as $reserva): ?>
                        <tr>
                            <td><?= $reserva['id_reserva'] ?></td>
                            <td><?= $reserva['nom'] ?><?= $reserva['cognoms'] ?></td>
                            <td><?= $reserva['data_entrada'] ?></td>
                            <td><?= $reserva['data_sortida'] ?></td>
                            <td><span class="status-badge status-actiu"><?= $reserva['nom_estat'] ?></span></td>
                        </tr>
                    <? endforeach ?>
                    
                </tbody>
            </table>
        </div>

        <!-- SECCIÓ: GESTIÓ DE CLIENTS -->
        <div class="table-section">
            <div class="table-header">
                <h2>Gestió de Clients</h2>
                <a href="./admin/add_user.php" class="btn-add">➕ Afegir Nou Client</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID Client</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>
                        <th>Darrer Allotjament</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Client 1 -->
                    <?php foreach($datos_usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_usuari'] ?></td>
                            <td><?= $usuario['nom'] ?> <?= $usuario['cognoms'] ?></td>
                            <td><?= $usuario['email'] ?></td>
                            <td><?= $usuario['contrasenya_hash'] ?></td>
                            <td><?= $usuario['nom_rol'] ?></td>
                            <td><?= $usuario['data_registre'] ?></td>
                            <td class="action-buttons">
                                <a href="./admin/edit_user.php?id=<?= $usuario['id_usuari'] ?>" class="btn-edit">✏️ Editar</a>
                            </td>
                        </tr>
                    <? endforeach ?>
                </tbody>
            </table>
        </div>

        <!-- SECCIÓ: GESTIÓ D'HABITACIONS -->
        <div class="table-section">
            <div class="table-header">
                <h2>Gestió d'Habitacions</h2>
                <a href="./admin/add_habitacion.php" class="btn-add">➕ Afegir Nova Habitació</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Núm.</th>
                        <th>Tipus</th>
                        <th>Estat</th>
                        <th>Preu/Nit (€)</th>
                        <th>Capacitat</th>
                        <th>Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <? foreach($datos_habitaciones as $habitacion): ?>
                        <!-- Habitació 1 -->
                        <tr>
                            <td><?= $habitacion['numero_habitacio'] ?></td>
                            <td><?= $habitacion['nom_tipus'] ?></td>
                            <!-- Utilitzo un span genèric ja que no hi ha una classe badge específica per a 'Neteja Feta' -->
                            <td><span style="color: #16a34a; font-weight: bold;"><?= $habitacion['nom_estat_hab'] ?></span></td>
                            <td><?= $habitacion['preu_base'] ?></td>
                            <td><?= $habitacion['capacitat'] ?></td>
                            <td class="action-buttons">
                                <a href="./admin/edit_habitacion.php?id=<?= $habitacion['id_habitacio'] ?>" class="btn-edit">✏️ Editar</a>
                                <a href="./admin/delete_habitacion.php?id=<?= $habitacion['id_habitacio'] ?>" class="btn-delete" onclick="console.log('Esborrar client CL-009903?')">🗑️ Esborrar</a>
                            </td>
                        </tr>
                    <? endforeach ?>
                </tbody>
            </table>

        </div>

        <!-- NOU SECCIÓ: INFORMES D'OCUPACIÓ -->
        <div class="table-section">
            <div class="table-header">
                <h2>📊 Informes d'Ocupació</h2>
                <a href="./admin/informe_nou.php" class="btn-add">➕ Generar Nou Informe</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Període</th>
                        <th>Nits Disponibles</th>
                        <th>Nits Reservades</th>
                        <th>Percentatge Ocupació</th>
                        <th>Ingressos Mitjans/Nit (€)</th>
                    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Desembre 2025</td>
                        <td>620</td>
                        <td>450</td>
                        <td>72.58%</td>
                        <td>105.30</td>

                    </tr>
                    <tr>
                        <td>Novembre 2025</td>
                        <td>600</td>
                        <td>380</td>
                        <td>63.33%</td>
                        <td>98.75</td>

                    </tr>
                    <tr>
                        <td>Octubre 2025</td>
                        <td>620</td>
                        <td>550</td>
                        <td>88.71%</td>
                        <td>112.50</td>

                    </tr>
                </tbody>
            </table>
        </div>

        <!-- NOVA SECCIÓ: HISTÒRIC DE RESERVES -->
        <div class="table-section">
            <div class="table-header">
                <h2>📜 Històric de Reserves</h2>
                <!-- No hi ha un botó 'Afegir' ja que és un històric -->
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Client</th>
                        <th>Data Sortida</th>
                        <th>Preu Total (€)</th>
                        <th>Estat</th>
                  
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>BW-4501</td>
                        <td>Carla Puig</td>
                        <td>01/11/2025</td>
                        <td>255.00</td>
                        <td><span class="status-badge status-actiu">Finalitzada</span></td>

                    </tr>
                    <tr>
                        <td>BW-4450</td>
                        <td>Ramon Ferrer</td>
                        <td>20/10/2025</td>
                        <td>510.50</td>
                        <td><span class="status-badge status-actiu">Finalitzada</span></td>

                    </tr>
                    <tr>
                        <td>BW-4405</td>
                        <td>Laura Pons</td>
                        <td>15/09/2025</td>
                        <td>90.00</td>
                        <td><span class="status-badge status-cancelada">Cancel·lada</span></td>

                    </tr>
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>