<?php include_once 'auth_session.php'; date_default_timezone_set('America/Guayaquil'); ?>
<?php $year = date("Y"); ?>
<!doctype html>
<html lang="es" data-bs-theme="auto">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de Facturación</title>
        <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/dist/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <script src="assets/dist/js/color-modes.js"></script>
        <script src="assets/dist/js/jquery-3.7.1.min.js"></script>
        <script src="assets/dist/js/jquery.dataTables.min.js"></script>
        <script src="assets/dist/js/dataTables.bootstrap5.min.js"></script>
        <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

        <style>
            body {
                background-color: var(--bs-body-bg);
                display: flex;
                flex-direction: column; /* Changed to column to stack header, main, footer */
                min-height: 100vh;
                transition: background-color 0.3s ease;
                font-family: 'Mont', sans-serif;
                animation: fadeIn 1s ease-out;
                overflow-x: hidden; /* Prevent horizontal scrollbar during animation */
            }

            .card.login-card {
                background-color: var(--bs-body-bg);
                color: var(--bs-body-color);
                border: none;
                border-radius: 14px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                padding: 2rem 2rem;
                transition: box-shadow 0.3s ease, transform 0.3s ease;
                animation: slideFade 0.8s ease-out;
            }

            .card.login-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
            }

            h3 {
                font-weight: 600;
                margin-bottom: 1.5rem;
                text-align: center;
            }

            .form-control {
                border-radius: 8px;
                padding: 0.65rem;
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }

            .form-control:focus {
                border-color: #6f42c1;
                box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.2);
            }

            .login100-form-btn {
                background-color: #6f42c1;
                border: none;
                border-radius: 8px;
                padding: 0.6rem;
                font-weight: 500;
                transition: background-color 0.2s ease, transform 0.2s ease;
            }

            .login100-form-btn:hover {
                background-color: #59359c;
                transform: translateY(-1px);
            }

            .alert {
                border-radius: 6px;
                animation: fadeIn 0.5s ease;
                font-size: 0.95rem;
            }

            small {
                color: var(--bs-secondary-color);
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes slideFade {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }          
        </style>
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }

            .b-example-divider {
                width: 100%;
                height: 2rem;
                background-color: rgba(0, 0, 0, .1);
                border: solid rgba(0, 0, 0, .15);
                border-width: 1px 0;
                box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
            }

            .b-example-vr {
                flex-shrink: 0;
                width: 1.5rem;
                height: 100vh;
            }

            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }

            .nav-scroller {
                position: relative;
                z-index: 2;
                height: 2.75rem;
                overflow-y: hidden;
            }

            .nav-scroller .nav {
                display: flex;
                flex-wrap: nowrap;
                padding-bottom: 1rem;
                margin-top: -1px;
                overflow-x: auto;
                text-align: center;
                white-space: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .btn-bd-primary {
                --bd-violet-bg: #712cf9;
                --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

                --bs-btn-font-weight: 600;
                --bs-btn-color: var(--bs-white);
                --bs-btn-bg: var(--bd-violet-bg);
                --bs-btn-border-color: var(--bd-violet-bg);
                --bs-btn-hover-color: var(--bs-white);
                --bs-btn-hover-bg: #6528e0;
                --bs-btn-hover-border-color: #6528e0;
                --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
                --bs-btn-active-color: var(--bs-btn-hover-color);
                --bs-btn-active-bg: #5a23c8;
                --bs-btn-active-border-color: #5a23c8;
            }

            .bd-mode-toggle {
                z-index: 1500;
            }

            .bd-mode-toggle .dropdown-menu .active .bi {
                display: block !important;
            }
        </style> 
        <style>
            @keyframes shake {
                0% { transform: translateX(0); }
                25% { transform: translateX(-4px); }
                50% { transform: translateX(4px); }
                75% { transform: translateX(-4px); }
                100% { transform: translateX(4px); }
            }

            .list-group-item {
                transition: all 0.3s ease;
            }

            .list-group-item:hover {
                animation: shake 0.5s;
                color: #007bff;
                background-color: #f8f9fa;
                cursor: pointer;
            }

            .list-group-item.active {
                color: white;
                background-color: #007bff !important;
            }

            /* Main layout container */
            .main-layout {
                display: flex;
                flex-grow: 1; /* Allows it to take remaining vertical space */
                width: 100%;
            }

            /* Desktop Sidebar (visible on md and up) */
            .sidebar-desktop {
                flex-shrink: 0; /* Prevent shrinking */
                width: 250px; /* Fixed width */
                border-right: 1px solid var(--bs-border-color);
                padding: 0; /* Adjust as needed */
                display: none; /* Hidden by default on mobile */
            }

            /* Offcanvas for mobile (hidden on md and up) */
            .offcanvas-mobile {
                width: 250px; /* Consistent width */
            }

            /* Main content area */
            .main-content-area {
                flex-grow: 1; /* Takes all available space */
                padding: 1rem; /* Example padding */
                /* For desktop, padding-left will be handled by col-md-9 */
            }

            /* Media query for medium and larger screens */
            @media (min-width: 768px) {
                .sidebar-desktop {
                    display: block; /* Show desktop sidebar */
                }
                .offcanvas-mobile {
                    display: none !important; /* Hide offcanvas completely */
                }
                .main-content-area {
                    /* Remove extra padding if col-md-9 handles it */
                    padding: 1rem 0; /* Adjust horizontal padding */
                }
            }
        </style>
    </head>
    <body class="d-flex flex-column min-vh-100">
        <svg xmlns="#" class="d-none">
            <symbol id="check2" viewBox="0 0 16 16">
                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
            </symbol>
            <symbol id="circle-half" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
            </symbol>
            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
            </symbol>
            <symbol id="sun-fill" viewBox="0 0 16 16">
                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
            </symbol>
        </svg>
        <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
            <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
                <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
                    Claro
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
                    Oscuro
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
                <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                    <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
                    Automático
                    <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
                </button>
                </li>
            </ul>
        </div>
        <header class="navbar navbar-expand-lg bg-body-tertiary position-relative w-100">
            <div class="container-fluid">
                <button class="navbar-toggler me-2 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateralMobile" aria-controls="menuLateralMobile" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>   
                <a class="navbar-brand" href="index.php">
                    <img src="assets/img/logo.jpeg" alt="Inicio" style="height:36px;vertical-align:middle;">
                </a>
                <?php include 'navbar.php'; ?>
            </div>
        </header>

        <div class="offcanvas offcanvas-start offcanvas-mobile d-md-none" tabindex="-1" id="menuLateralMobile" aria-labelledby="menuLateralMobileLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="menuLateralMobileLabel">Menú</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
            </div>
            <div class="offcanvas-body p-0">
                <?php include 'menu.php'; ?>
            </div>
        </div>

        <div class="main-layout">
            <nav class="sidebar-desktop d-none d-md-block border-end p-0">
                <?php include 'menu.php'; ?>
            </nav>

            <main class="main-content-area container-fluid py-3">