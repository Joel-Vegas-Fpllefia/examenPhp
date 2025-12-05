<?php
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
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

        nav {
            /* CANVI CLAU: Utilitza tota l'amplada de la finestra per maximitzar l'espai */
            max-width: 100%;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            /* Redueix el padding horitzontal per guanyar espai */
            padding: 0 1rem;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: '🌊';
            font-size: 2rem;
        }

        nav ul {
            list-style: none;
            display: flex;
            /* Redueix l'espai entre ítems de menú */
            gap: 1.2rem;
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

        /* Contenedor principal de la dreta per a totes les opcions */
        .right-nav-container {
            display: flex;
            align-items: center;
            /* Redueix l'espaiat entre els dos grans grups de la dreta */
            gap: 1rem;
        }

        /* Contenedores para la derecha del nav */
        .auth-buttons,
        .user-section {
            display: flex;
            align-items: center;
            /* Redueix l'espaiat intern dels botons/links */
            gap: 0.8rem;
        }

        /* Estilos de la Foto de Perfil y Enlace */
        .profile-pic {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            transition: border-color 0.3s;
        }

        .user-link:hover .profile-pic {
            border-color: #f0f0f0;
        }

        .user-link {
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .btn-login,
        .btn-register {
            background: white;
            color: #0066cc;
            /* Redueix el padding per estalviar espai */
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            transition: transform 0.3s;
            text-decoration: none;
            white-space: nowrap;
            font-size: 0.95rem;
            /* Fa la lletra una mica més petita */
        }

        .btn-logout {
            background: #ff5252;
            color: white;
            /* Redueix el padding per estalviar espai */
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            transition: transform 0.3s;
            text-decoration: none;
            white-space: nowrap;
            font-size: 0.95rem;
        }

        .btn-login:hover,
        .btn-register:hover,
        .btn-logout:hover {
            transform: translateY(-2px);
        }

        /* --- Contenido Principal (sense canvis significatius aquí) --- */

        .hero {
            background: linear-gradient(rgba(0, 102, 204, 0.8), rgba(0, 73, 153, 0.9)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23e0f2ff" width="1200" height="600"/><path fill="%23bfdbfe" d="M0 300L50 283C100 267 200 233 300 233C400 233 500 267 600 283C700 300 800 300 900 283C1000 267 1100 233 1150 217L1200 200V600H1150C1100 600 1000 600 900 600C800 600 700 600 600 600C500 600 400 600 300 600C200 600 100 600 50 600H0V300Z"/></svg>');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 2rem;
            margin-top: 70px;
        }

        .hero-content {
            max-width: 800px;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            cursor: pointer;
        }

        .btn-primary {
            background: white;
            color: #0066cc;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #0066cc;
        }

        .features {
            padding: 6rem 2rem;
            background: #f8fafc;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #0066cc;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: #0066cc;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .about {
            padding: 6rem 2rem;
            background: white;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text h2 {
            color: #0066cc;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .about-text p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 1rem;
        }

        .about-image {
            background: linear-gradient(135deg, #0066cc 0%, #004999 100%);
            height: 400px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8rem;
        }

        footer {
            background: #1e293b;
            color: white;
            padding: 3rem 2rem 1rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            margin-bottom: 1rem;
            color: #60a5fa;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #334155;
            color: #94a3b8;
        }

        /* Media Queries per a Adaptabilitat */
        @media (max-width: 1100px) {

            /* Per quan l'espai comença a ser limitat, però no és mòbil */
            nav {
                flex-wrap: wrap;
                gap: 1rem;
            }

            /* Si no caben, les opcions de la dreta baixaran */
            .right-nav-container {
                margin-left: auto;
            }
        }

        @media (max-width: 900px) {

            /* Oculta la navegació principal en pantalles petites per guanyar espai */
            nav ul {
                display: none;
            }

            .right-nav-container {
                width: auto;
                justify-content: flex-end;
                /* A la dreta */
            }

            .user-section,
            .auth-buttons {
                gap: 0.5rem;
            }
        }

        @media (max-width: 600px) {

            /* En pantalles molt petites, les opcions de la dreta ocupen tota la línia */
            .right-nav-container {
                width: 100%;
                justify-content: space-between;
                order: 3;
                /* Força a baixar a la tercera línia, sota el logo */
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">BlueWave Hotels</div>
            <ul>
                <li><a href="./index.php#inici">Inici</a></li>
                <li><a href="./index.php#serveis">Serveis</a></li>
                <li><a href="./habitaciones.php">Habitacions</a></li>
                <li><a href="./index.php#sobre-nosaltres">Sobre Nosaltres</a></li>
                <li><a href="./index.php#contacte">Contacte</a></li>
                <? if (isset($_SESSION['id_user'])): ?>
                <? if($_SESSION['rol'] === 'Administrador'): ?>
                    <li><a href="./admin_panel.php">Admin Panel</a></li>
                <? endif ?>
                <? endif ?>
            </ul>

            <div class="right-nav-container">
                <? if(!isset($_SESSION['id_user'])): ?>
                    <div class="auth-buttons">
                        <a href="./login.php" class="btn-login">Iniciar sessió</a>
                        <a href="./register.php" class="btn-register">Registrar-se</a>
                    </div>
                <? endif ?>
                
                <? if(isset($_SESSION['id_user'])): ?>
                    <div class="user-section">
                        <a href="./perfil.php" class="user-link" title="El meu perfil">
                            <img src="<?= $_SESSION['foto_perfil'] ?>" alt="Foto de perfil" class="profile-pic">
                        </a>
                        <a href="./logout.php" class="btn-logout">Tancar sessió</a>
                    </div>
                <? endif ?>
            </div>
        </nav>
    </header>
</body>

</html>