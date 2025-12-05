<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserves - BlueWave Hotels</title>
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
            /* Eliminem el padding del body per permetre que el header s'estengui */
        }

        /* Nou contenidor per al contingut principal (sota el header fix) */
        .main-content {
            padding: 2rem;
            padding-top: 6rem;
            /* Afegim padding superior per evitar que el header el tapi */
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Estils del Header de navegació (FIXED) */
        header {
            background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Estils de la barra de navegació interna */
        nav {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            /* Color canviat a blanc per contrastar amb el fons blau del header */
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: '🌊';
            font-size: 2rem;
        }

        /* Estils per a la llista de navegació */
        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
            font-weight: 500;
        }

        nav a:hover {
            opacity: 0.8;
        }

        /* Estils per als botons d'autenticació */
        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-login,
        .btn-register {
            background: white;
            color: #0066cc;
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-register {
            background: transparent;
            border: 2px solid white;
            color: white;
        }

        .btn-login:hover,
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Estils de les seccions de contingut (sense canvis) */
        .search-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .search-section h2 {
            color: #0066cc;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0066cc;
        }

        .btn-search {
            grid-column: 1 / -1;
            background: #0066cc;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-search:hover {
            background: #004999;
            transform: translateY(-2px);
        }

        .rooms-section {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .room-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .room-image {
            height: 200px;
            background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            position: relative;
        }

        .room-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #0066cc;
        }

        .room-badge.disponible {
            background: #dcfce7;
            color: #16a34a;
        }

        .room-content {
            padding: 1.5rem;
        }

        .room-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .room-title {
            font-size: 1.5rem;
            color: #0066cc;
            font-weight: bold;
        }

        .room-price {
            font-size: 1.8rem;
            color: #16a34a;
            font-weight: bold;
        }

        .room-price-label {
            font-size: 0.9rem;
            color: #64748b;
        }

        .room-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-size: 0.95rem;
        }

        .room-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .room-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-tag {
            background: #e0f2ff;
            color: #0066cc;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.85rem;
        }

        .btn-reserve {
            width: 100%;
            background: #0066cc;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-reserve:hover {
            background: #004999;
        }

        @media (max-width: 900px) {
            nav ul {
                display: none;
                /* Amaguem els enllaços de navegació principals en pantalles mitjanes/petites */
            }

            nav {
                max-width: 100%;
                padding: 0 1rem;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 0;
            }

            .main-content {
                padding: 1rem;
                padding-top: 5rem;
            }

            header {
                /* Per a mòbils, la capçalera pot seguir sent una sola línia */
                padding: 0.5rem 0;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .rooms-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include('./header.php') ?>

    <div class="main-content">
        <div class="search-section">
            <h2>Troba la teva habitació perfecta</h2>
            <form class="search-form" action="#" method="POST">
                <div class="form-group">
                    <label for="checkIn">Data d'entrada</label>
                    <input type="date" id="checkIn" name="checkIn" required>
                </div>
                <div class="form-group">
                    <label for="checkOut">Data de sortida</label>
                    <input type="date" id="checkOut" name="checkOut" required>
                </div>
                <div class="form-group">
                    <label for="roomType">Tipus d'habitació</label>
                    <select id="roomType" name="roomType">
                        <option value="">Totes</option>
                        <option value="individual">Individual</option>
                        <option value="doble">Doble</option>
                        <option value="suite">Suite</option>
                        <option value="familiar">Familiar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="guests">Hostes</label>
                    <select id="guests" name="guests">
                        <option value="1">1 hoste</option>
                        <option value="2" selected>2 hostes</option>
                        <option value="3">3 hostes</option>
                        <option value="4">4 hostes</option>
                        <option value="5">5+ hostes</option>
                    </select>
                </div>
                <button type="submit" class="btn-search">🔍 Buscar habitacions</button>
            </form>
        </div>

        <div class="rooms-section">
            <div class="room-card">
                <div class="room-image">
                    🛏️
                    <span class="room-badge disponible">Disponible</span>
                </div>
                <div class="room-content">
                    <div class="room-header">
                        <h3 class="room-title">Habitació Individual</h3>
                        <div>
                            <div class="room-price">75€</div>
                            <div class="room-price-label">per nit</div>
                        </div>
                    </div>
                    <div class="room-details">
                        <div class="detail-item">👥 1 persona</div>
                        <div class="detail-item">📏 20 m²</div>
                    </div>
                    <p class="room-description">Perfecta per a viatges de negocis o escapades en solitari. Espai
                        acollidor i funcional.</p>
                    <div class="room-features">
                        <span class="feature-tag">WiFi gratuït</span>
                        <span class="feature-tag">Escriptori</span>
                        <span class="feature-tag">Bany privat</span>
                        <span class="feature-tag">TV</span>
                    </div>
                    <a href="hacer_reserva.php?id=" class="btn-reserve">📅 Reservar ara</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>