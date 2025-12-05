<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva - Admin BlueWave</title>
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
            line-height: 1.6;
        }

        /* Estils de la Capçalera (Reutilitzats per consistència) */
        header {
            background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .logo::before {
            content: '🌊';
            margin-right: 0.5rem;
        }

        /* ---------------------------------- */
        /* Contingut Principal i Formulari */
        /* ---------------------------------- */
        .container {
            max-width: 800px;
            margin: 80px auto 40px;
            /* Margen superior para el header fijo */
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0066cc;
            font-size: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #475569;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #0066cc;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.2);
            outline: none;
        }

        /* Estil per al camp de l'ID, que generalment és de només lectura */
        #reserva_id {
            background-color: #e2e8f0;
            cursor: not-allowed;
            font-weight: bold;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-submit,
        .btn-cancel {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
            text-decoration: none;
            text-align: center;
        }

        .btn-submit {
            background: #10b981;
            /* Verd per guardar */
            color: white;
        }

        .btn-submit:hover {
            background: #059669;
        }

        .btn-cancel {
            background: #cbd5e1;
            /* Gris per cancel·lar */
            color: #333;
        }

        .btn-cancel:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">BlueWave Hotels - Admin</div>
            <a href="../admin_panel.php" class="btn-cancel" style="padding: 0.5rem 1rem; margin-top:0;">Tornar al Panell</a>
        </nav>
    </header>

    <div class="container">
        <h1>Edició de Reserva - BW-4589</h1>

        <form action="./reserves_update.php" method="POST">

            <div class="form-group">
                <label for="reserva_id">ID de la Reserva</label>
                <input type="text" id="reserva_id" name="reserva_id" value="BW-4589" readonly>
            </div>

            <div class="form-group">
                <label for="client_name">Nom del Client</label>
                <input type="text" id="client_name" name="client_name" value="Anna Lòpez" required>
            </div>

            <div class="form-group">
                <label for="habitacio_num">Número d'Habitació</label>
                <input type="text" id="habitacio_num" name="habitacio_num" value="203 (Suite Mar)" required>
            </div>

            <div class="form-group">
                <label for="data_entrada">Data d'Entrada</label>
                <input type="date" id="data_entrada" name="data_entrada" value="2025-12-05" required>
            </div>

            <div class="form-group">
                <label for="data_sortida">Data de Sortida</label>
                <input type="date" id="data_sortida" name="data_sortida" value="2025-12-08" required>
            </div>

            <div class="form-group">
                <label for="estat">Estat de la Reserva</label>
                <select id="estat" name="estat" required>
                    <option value="confirmada" selected>Confirmada</option>
                    <option value="pendent">Pendent de Pagament</option>
                    <option value="cancelada">Cancel·lada</option>
                    <option value="checkin">Check-in Realitzat</option>
                </select>
            </div>

            <div class="form-actions">
                <a href="./admin_panel.html" class="btn-cancel">Cancel·lar</a>
                <button type="submit" class="btn-submit">💾 Guardar Canvis</button>
            </div>

        </form>
    </div>
</body>

</html>