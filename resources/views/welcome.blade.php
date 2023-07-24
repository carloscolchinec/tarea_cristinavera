<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoftLife - Sistema de Facturación</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
 body {
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        .landing-page {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .logo {
            display: block; /* Agregamos esta propiedad */
        }

        .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .description {
            font-size: 18px;
            max-width: 600px;
            text-align: center;
            margin-bottom: 2rem;
        }

        .cta-btn {
            background-color: #007bff;
            color: #fff;
            padding: 12px 24px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="landing-page">
        <img src="{{asset('img/logo.png')}}" style="width: 300px;" alt="Logo" class="logo">
        <h1 class="title">SoftLife - Sistema de Facturación</h1>
        <p class="description">¡Bienvenido al mejor sistema de facturación para tu negocio! Con SoftLife, podrás
            gestionar de manera eficiente las facturas de tus clientes, registrar pagos, generar reportes y mucho más.
            ¡Simplifica tu administración y ahorra tiempo!</p>
        <a href="{{route('dashboard.index')}}" class="cta-btn">Entrar al Panel</a>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
