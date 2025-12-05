<?php
session_start();
require_once('./db/config.php');
if(!isset($_SESSION['id_user'])){
    header('./no_permisos.php');
    exit();
}
$datos_reservas = $mysqli -> query("SELECT * FROM RESERVES rs JOIN HABITACIONS hb ON rs.id_habitacio = hb.id_habitacio JOIN TIPUS_HABITACIO tph ON hb.id_tipus = tph.id_tipus WHERE rs.id_client = ".$_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil d'Usuari - BlueWave Hotels</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e0f2ff 0%, #bfdbfe 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #333;
        }

        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .header-perfil {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 1rem;
        }

        .header-perfil h1 {
            color: #0066cc;
            font-size: 2rem;
        }

        .btn-back,
        .btn-logout {
            background: #0066cc;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin-left: 1rem;
            display: inline-block;
        }

        .btn-logout {
            background: #ff5252;
        }

        .btn-back:hover {
            background: #004999;
            transform: translateY(-2px);
        }

        .btn-logout:hover {
            background: #cc0000;
            transform: translateY(-2px);
        }

        /* Estilos de Pestañas (Tabs) */
        .tabs {
            display: flex;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .tab-button {
            padding: 1rem 2rem;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            color: #64748b;
            border: none;
            background: transparent;
            border-bottom: 3px solid transparent;
            transition: color 0.3s, border-bottom 0.3s;
        }

        .tab-button.active,
        .tab-button:hover {
            color: #0066cc;
        }

        .tab-button.active {
            border-bottom: 3px solid #0066cc;
        }

        .tab-content {
            padding: 1rem 0;
        }

        /* Ocultar contenido inactivo (simulación JS) */
        .tab-content:not(:first-child) {
            display: none;
        }

        /* Estilos de Formularios y Datos Personales */
        .form-perfil h2,
        .reservas-list h2 {
            color: #004999;
            margin-bottom: 1.5rem;
            font-size: 1.6rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        .btn-guardar {
            background: #16a34a;
            color: white;
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 1.5rem auto 0;
        }

        .btn-guardar:hover {
            background: #108537;
            transform: translateY(-2px);
        }

        /* Estilos de la Lista de Reservas */
        .reserva-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            align-items: center;
        }

        .reserva-info {
            font-size: 1rem;
        }

        .reserva-info strong {
            display: block;
            color: #0066cc;
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .reserva-title {
            grid-column: 1 / -1;
            font-size: 1.2rem;
            font-weight: bold;
            color: #004999;
            margin-bottom: 0.5rem;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 0.5rem;
        }

        .reserva-actions {
            grid-column: 1 / -1;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 0.5rem;
        }

        .btn-cancelar,
        .btn-detall {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-cancelar {
            background: #ff5252;
            color: white;
        }

        .btn-cancelar:hover {
            background: #cc0000;
        }

        .btn-detall {
            background: #0066cc;
            color: white;
        }

        .btn-detall:hover {
            background: #004999;
        }

        .no-reservas {
            text-align: center;
            padding: 3rem;
            color: #64748b;
            background: #f8fafc;
            border-radius: 10px;
            margin-top: 2rem;
        }


        /* Media Queries */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1rem;
            }

            .header-perfil {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-actions {
                margin-top: 1rem;
                display: flex;
                width: 100%;
                justify-content: space-between;
            }

            .btn-back,
            .btn-logout {
                margin-left: 0;
            }

            .tabs {
                justify-content: space-around;
            }

            .tab-button {
                padding: 1rem;
                font-size: 1rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .reserva-card {
                grid-template-columns: 1fr;
            }

            .reserva-actions {
                justify-content: space-around;
            }
        }
    </style>
    <script>
        // Script básico para simular el cambio de pestañas (requiere JS real)
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const targetId = tab.getAttribute('data-tab');

                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    contents.forEach(content => {
                        content.style.display = 'none';
                    });

                    document.getElementById(targetId).style.display = 'block';
                });
            });
        });
    </script>
</head>

<body>
    <div class="profile-container">
        <div class="header-perfil">
            <h1>🌊 El Meu Perfil</h1>
            <div class="header-actions">
                <a href="./index.php" class="btn-back">Tornar a l'Inici</a>
                <a href="./logout.php" class="btn-logout">Tancar Sessió</a>
            </div>
        </div>

        <div class="tabs">
            <button class="tab-button active" data-tab="dades">Dades Personals</button>
            <button class="tab-button" data-tab="reservas">Les Meves Reserves</button>
        </div>

        <div id="dades" class="tab-content">
            <div class="form-perfil">
                <h2>Modificar Dades Personals</h2>

                <form action="#" method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" value="<?= $_SESSION['nom'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="cognom">Cognom</label>
                            <input type="text" id="cognom" name="cognom" value="<?= $_SESSION['cognoms'] ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Correu electrònic</label>
                        <input type="email" id="email" name="email" value="<?= $_SESSION['email'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="foto_perfil_url">URL de la Foto de Perfil</label>
                        <input type="url" id="foto_perfil_url" name="foto_perfil_url" value="<?= $_SESSION['foto_perfil'] ?>">
                    </div>

                    
                    <button type="submit" class="btn-guardar">💾 Guardar Canvis</button>
                </form>
            </div>
        </div>

        <div id="reservas" class="tab-content" style="display: none;">
            <div class="reservas-list">
                <h2>Historial de Reserves</h2>

                <div class="reserva-card">
                    <? foreach($datos_reservas as $reserva): ?>
                        <div class="reserva-title">Suite Mediterrània (Ref: BW-3054)</div>
                        <div class="reserva-info"><strong>Data Entrada:</strong> <?= $reserva['data_entrada'] ?></div>
                        <div class="reserva-info"><strong>Data Sortida:</strong> <?= $reserva['data_sortida'] ?></div>
                        <div class="reserva-info"><strong>Habitació:</strong> <?= $reserva['nom_tipus'] ?></div>
                        <div class="reserva-info"><strong>Preu Total:</strong> <?= $reserva['preu_total'] ?></div>
                    <? endforeach ?>

                </div>


            </div>
        </div>

    </div>
</body>

</html>