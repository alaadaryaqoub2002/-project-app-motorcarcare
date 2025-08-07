<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarCare</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/custom.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/alerts.css">


    <style>
        .header {
            background-color: #ffffff;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 24px;
            color: #000;
            font-weight: bold;
        }

        .navbar-brand span {
            color: #026dfe;
        }

        .navbar-nav .nav-link {
            color: #666;
            font-size: 16px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #026dfe;
        }

        .nav-item.active .nav-link {
            color: #026dfe;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 0.5rem 0;
            min-width: 200px;
            margin-top: 10px;
        }

        .dropdown-item {
            color: #666;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #026dfe;
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: #026dfe;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #eee;
        }

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
            color: #026dfe;
        }

        .user-dropdown-toggle span {
            font-weight: 500;
            color: #333;
        }

        .btn-login, .btn-signup {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .btn-login {
            background-color: transparent;
            border: 2px solid #026dfe;
            color: #026dfe;
        }

        .btn-signup {
            background-color: #026dfe;
            border: 2px solid #026dfe;
            color: white;
        }

        .btn-login:hover {
            background-color: #026dfe;
            color: white;
        }

        .btn-signup:hover {
            background-color: #0056cc;
            border-color: #0056cc;
        }

        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1rem 0;
            }
            .dropdown-menu {
                border: none;
                box-shadow: none;
                padding-left: 1rem;
            }
            .user-dropdown-toggle {
                background: none;
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
<header id="header" class="header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Motor<span>CarCare</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="Services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="contact_form.php" class="nav-link">Contact</a></li>

                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="nav-item"><a href="user-appointment.php" class="nav-link">My Appointments</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link user-dropdown-toggle dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                <span><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>

                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="update_profile.php">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="user-appointment.php">
                                    <i class="fas fa-calendar-check"></i> My Appointments
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="SelectionPage.php" class="btn btn-login">Login</a>
                        </li>
                        
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

