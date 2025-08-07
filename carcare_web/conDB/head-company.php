<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not a company user
if (!isset($_SESSION['company_user'])) {
    header("Location: login_company.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - Motor Car Care</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #026dfe;
            --secondary-color: #6c757d;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Navbar Styles */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            padding: 1rem;
        }

        .navbar-brand {
            color: #333 !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-brand span {
            color: var(--primary-color);
        }

        .nav-link {
            color: #555 !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0.5rem 0;
            min-width: 200px;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            color: #555;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: var(--primary-color);
        }

        /* User Profile */
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            padding: 5px 15px;
            border-radius: 25px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .user-dropdown-toggle:hover {
            background-color: #e9ecef;
        }

        .user-dropdown-toggle i {
            font-size: 20px;
            margin-right: 8px;
            color: var(--primary-color);
        }

        .company-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 8px;
        }

        .nav-icon {
            margin-right: 8px;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .navbar-nav {
                padding-top: 1rem;
            }
            
            .user-dropdown-toggle {
                padding: 0.5rem 0;
                background: none;
            }
        }
    </style>

    <!-- Firebase App (the core Firebase SDK) -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

        const firebaseConfig = {
            apiKey: "AIzaSyC2KfKctrAoVzCBQBjAqXQw1Uc_1_V8i5U",
            authDomain: "carcare-app-52eb1.firebaseapp.com",
            databaseURL: "https://carcare-app-52eb1-default-rtdb.firebaseio.com",
            projectId: "carcare-app-52eb1",
            storageBucket: "carcare-app-52eb1.firebasestorage.app",
            messagingSenderId: "732379485235",
            appId: "1:732379485235:web:37818daef5f175db0ebf46",
            measurementId: "G-MFRCW8L835"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        // Check authentication state
        onAuthStateChanged(auth, (user) => {
            if (!user) {
                window.location.href = 'login_company.php';
            }
        });
    </script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="company_dashboard.php">Car<span>Care</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'company_dashboard.php' ? 'active' : ''; ?>" href="company_dashboard.php">
                            <i class="fas fa-tachometer-alt nav-icon"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'view_appointments.php' ? 'active' : ''; ?>" href="view_appointments.php">
                            <i class="fas fa-calendar-alt nav-icon"></i> Appointments
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_service.php' ? 'active' : ''; ?>" href="add_service.php">
                            <i class="fas fa-plus-circle nav-icon"></i> Add Service
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'manage_services.php' ? 'active' : ''; ?>" href="manage_services.php">
                            <i class="fas fa-cogs nav-icon"></i> Manage Services
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link user-dropdown-toggle dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            <span><?php echo htmlspecialchars($_SESSION['company_user']['username']); ?></span>
                            <span class="company-badge">Company</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="company_profile.php">
                                <i class="fas fa-building"></i> Company Profile
                            </a>
                            <a class="dropdown-item" href="company_settings.php">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
