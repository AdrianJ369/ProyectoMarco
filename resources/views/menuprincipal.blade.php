<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        }

        .btn-custom {
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .card-body-custom {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
        }

        .row-custom {
            justify-content: center;
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .navbar-text-custom {
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
        }
        
        .main-content {
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .title-container {
            margin-bottom: 3rem;
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cotizaciones</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text navbar-text-custom me-3">
                    Bienvenido, {{ Auth::user()->nombre }}
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="container-custom py-5">
            <div class="title-container">
                <h1 class="fw-bold">Menú Principal</h1>
            </div>

            <div class="row row-custom g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body card-body-custom text-center d-flex flex-column">
                            <h5 class="card-title">Nueva Cotización</h5>
                            <p class="card-text">Crear una nueva cotización para tu cliente.</p>
                            <a href="{{ route('cotizaciones.create') }}" class="btn btn-custom btn-outline-primary mt-auto py-3">Crear</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body card-body-custom text-center d-flex flex-column">
                            <h5 class="card-title">Ver Categorías</h5>
                            <p class="card-text">Explora o crea categorías de servicios.</p>
                            <a href="{{ route('categorias.create') }}" class="btn btn-custom btn-outline-success mt-auto py-3">Ver categorías</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body card-body-custom text-center d-flex flex-column">
                            <h5 class="card-title">Perfil</h5>
                            <p class="card-text">Edita tu información personal.</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-custom btn-outline-dark mt-auto py-3">Ver perfil</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>