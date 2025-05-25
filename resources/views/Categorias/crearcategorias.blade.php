<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .categorias-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
            background-color: #f8f9fa;
            margin-top: 20px;
        }

        .categoria-item {
            padding: 10px 15px;
            margin-bottom: 10px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .categoria-item:last-child {
            margin-bottom: 0;
        }

        .categoria-nombre {
            font-weight: bold;
            color: #0d6efd;
        }

        .categoria-acciones i {
            cursor: pointer;
            margin-left: 10px;
            font-size: 1.2rem;
        }

        .categoria-acciones i:hover {
            color: #dc3545;
        }

        .categoria-acciones .bi-pencil:hover {
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Crear nueva categoría</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('deleted'))
            <div class="alert alert-danger">
                {{ session('deleted') }}
            </div>
        @endif

        {{-- Formulario para crear una categoría --}}
        <form action="{{ route('categorias.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la categoría</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción (opcional)</label>
                <textarea class="form-control" name="descripcion" id="descripcion"
                    rows="3">{{ old('descripcion') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Guardar categoría</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </form>

        <hr class="my-4">


        <h3 class="mt-4">Todas las categorías</h3>
        <div class="categorias-container">
            @forelse ($categorias as $categoria)
                <div class="categoria-item">
                    <div>
                        <div class="categoria-nombre">{{ $categoria->nombre }}</div>
                        @if($categoria->descripcion)
                            <div class="categoria-descripcion mt-2">{{ $categoria->descripcion }}</div>
                        @endif
                    </div>
                    <div class="categoria-acciones">
                        <a href="{{ route('categorias.edit', $categoria->id) }}" title="Editar">
                            <i class="bi bi-pencil text-primary"></i>
                        </a>
                        <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background:none; border:none; padding:0;" title="Eliminar"
                                onclick="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-muted">No hay categorías creadas aún.</div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>