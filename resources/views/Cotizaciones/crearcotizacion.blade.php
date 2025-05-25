<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cotización</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #vista-previa table tbody tr {
            background-color: #f8f9fa;
        }

        #vista-previa {
            background-color: white;
            padding: 30px;
            border: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            width: 794px;
            margin: auto;
            margin-bottom: 20px;
            /* Elimina min-height para permitir crecimiento dinámico */
        }

        .pdf-export {
            padding: 0 !important;
            margin: 0 auto !important;
            box-shadow: none !important;
            border: none !important;
            width: 100% !important;
            min-height: auto !important;
        }

        .pdf-export table th:last-child,
        .pdf-export table td:last-child {
            display: none !important;
        }



        .encabezado-cotizacion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        @media (max-width: 820px) {
            #vista-previa {
                width: 100%;
                min-height: auto;
                padding: 20px;
            }

            .encabezado-cotizacion {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        /* Nuevos estilos para el PDF */
        .pdf-content {
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .pdf-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .pdf-table th {
            background-color: #343a40;
            color: white;
            padding: 8px;
            text-align: left;
        }

        .pdf-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .pdf-total {
            font-weight: bold;
            background-color: #f8f9fa;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5">
        <h2>Crear Cotización</h2>

        <form id="form-cotizacion" method="POST" action="{{ route('cotizaciones.store') }}">
            @csrf

            <div class="mb-3">
                <label for="titulo" class="form-label">Título de la cotización</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>

            <div class="mb-4">
                <h4>Agregar Categoría y Conceptos</h4>

                <div class="mb-2">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" class="form-select" required>
                        <option value="">Selecciona una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row align-items-end g-2">
                    <div class="col-md-3">
                        <label for="concepto_nombre" class="form-label">Nombre del concepto</label>
                        <input type="text" id="concepto_nombre" class="form-control" placeholder="Ej: Excavación">
                    </div>
                    <div class="col-md-4">
                        <label for="concepto_descripcion" class="form-label">Descripción del concepto</label>
                        <input type="text" id="concepto_descripcion" class="form-control"
                            placeholder="Ej: Remoción de tierra vegetal">
                    </div>
                    <div class="col-md-3">
                        <label for="concepto_precio" class="form-label">Precio</label>
                        <input type="number" id="concepto_precio" class="form-control" placeholder="Ej: 500">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success" id="btn-agregar-concepto">Agregar
                            concepto</button>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Vista Previa</h4>
            <div id="vista-previa" class="mt-3">
                <div class="encabezado-cotizacion">
                    <h5>Cotización: <span id="titulo-cotizacion"></span></h5>
                    <h5>Cliente: {{ Auth::user()->nombre }}</h5>
                </div>
                <!-- El contenido dinámico se insertará aquí -->
            </div>

            <div class="d-flex align-items-start gap-2 mt-4 mb-4">
                <button type="button" class="btn btn-danger" id="btn-guardar-pdf">Guardar como PDF</button>
                <button type="button" class="btn btn-warning text-white" id="btn-borrar-cotizacion">Borrar
                    Cotización</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        let cotizacionTemp = [];
        let ordenCategorias = [];

        $('#btn-agregar-concepto').click(function () {
            const categoriaId = $('#categoria').val();
            const categoriaNombre = $('#categoria option:selected').text();
            const conceptoNombre = $('#concepto_nombre').val();
            const conceptoDescripcion = $('#concepto_descripcion').val();
            const precio = parseFloat($('#concepto_precio').val());
            const tituloCotizacion = $('#titulo').val();

            if (!tituloCotizacion || !categoriaId || !conceptoNombre || !conceptoDescripcion || isNaN(precio)) {
                alert('Completa todos los campos para agregar un concepto.');
                return;
            }

            if (!ordenCategorias.includes(categoriaId)) {
                ordenCategorias.push(categoriaId);
            }

            cotizacionTemp.push({
                id: Date.now(),
                categoriaId: categoriaId,
                categoria: categoriaNombre,
                nombre: conceptoNombre,
                descripcion: conceptoDescripcion,
                precio: precio
            });

            $('#concepto_nombre').val('');
            $('#concepto_descripcion').val('');
            $('#concepto_precio').val('');
            $('#categoria').val('');

            renderVistaPrevia(tituloCotizacion);
        });

        function eliminarConcepto(id) {
            cotizacionTemp = cotizacionTemp.filter(item => item.id !== id);
            const categoriasRestantes = cotizacionTemp.map(item => item.categoriaId);
            ordenCategorias = ordenCategorias.filter(catId => categoriasRestantes.includes(catId));
            const titulo = $('#titulo').val();
            renderVistaPrevia(titulo);
        }

        function renderVistaPrevia(titulo) {
            let total = cotizacionTemp.reduce((sum, item) => sum + item.precio, 0);

            // Actualiza el título en el encabezado
            $('#titulo-cotizacion').text(titulo || '');

            let html = `<div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Categoría</th>
                                        <th>Concepto</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            if (cotizacionTemp.length === 0) {
                html += `<tr>
                            <td colspan="5" class="text-center text-muted">Sin conceptos aún</td>
                        </tr>`;
            } else {
                const agrupado = {};
                cotizacionTemp.forEach(item => {
                    if (!agrupado[item.categoriaId]) {
                        agrupado[item.categoriaId] = {
                            nombre: item.categoria,
                            conceptos: []
                        };
                    }
                    agrupado[item.categoriaId].conceptos.push(item);
                });

                for (const categoriaId of ordenCategorias) {
                    const grupo = agrupado[categoriaId];
                    if (!grupo) continue;

                    grupo.conceptos.forEach((item, index) => {
                        html += `
                            <tr>
                                <td>${index === 0 ? grupo.nombre : ''}</td>
                                <td>${item.nombre}</td>
                                <td>${item.descripcion}</td>
                                <td>$${item.precio.toFixed(2)}</td>
                                <td><button class="btn btn-danger btn-sm" onclick="eliminarConcepto(${item.id})">Eliminar</button></td>
                            </tr>`;
                    });
                }
            }

            html += `
    <tr class="table-info">
    <td></td> <!-- Categoría -->
    <td></td> <!-- Concepto -->
    <td class="text-end fw-bold">Total</td> <!-- Descripción -->
    <td class="fw-bold">$${total.toFixed(2)}</td> <!-- Precio -->
    <td></td> <!-- Acción -->
</tr>
`;

            html += `</tbody></table></div>`;

            // Insertamos después del encabezado
            $('#vista-previa').find('.table-responsive').remove();
            $('#vista-previa .encabezado-cotizacion').after(html);
        }

        $('#btn-borrar-cotizacion').click(function () {
            if (confirm('¿Estás seguro de que deseas borrar toda la cotización?')) {
                cotizacionTemp = [];
                ordenCategorias = [];
                $('#titulo').val('');
                renderVistaPrevia('');
                window.scrollTo(0, 0);
            }
        });

        $(document).ready(function () {
            renderVistaPrevia('');
        });


        $('#btn-guardar-pdf').click(function () {
            const titulo = $('#titulo').val().trim();
            if (!titulo) {
                alert("Agrega un título para la cotización.");
                return;
            }

            // Crear un elemento temporal para el PDF
            const pdfElement = document.createElement('div');
            pdfElement.className = 'pdf-content';

            // Construir el contenido del PDF
            let html = `
        <div class="pdf-header">
            <h3>Cotización: ${titulo}</h3>
            <h3>Cliente: {{ Auth::user()->nombre }}</h3>
        </div>
        <table class="pdf-table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Concepto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
    `;

            // Agrupar por categorías
            const agrupado = {};
            cotizacionTemp.forEach(item => {
                if (!agrupado[item.categoriaId]) {
                    agrupado[item.categoriaId] = {
                        nombre: item.categoria,
                        conceptos: []
                    };
                }
                agrupado[item.categoriaId].conceptos.push(item);
            });

            // Agregar conceptos al PDF
            for (const categoriaId of ordenCategorias) {
                const grupo = agrupado[categoriaId];
                if (!grupo) continue;

                grupo.conceptos.forEach((item, index) => {
                    html += `
                <tr>
                    <td>${index === 0 ? grupo.nombre : ''}</td>
                    <td>${item.nombre}</td>
                    <td>${item.descripcion}</td>
                    <td>$${item.precio.toFixed(2)}</td>
                </tr>
            `;
                });
            }

            // Calcular total
            const total = cotizacionTemp.reduce((sum, item) => sum + item.precio, 0);

            html += `
            </tbody>
            <tfoot>
                <tr class="pdf-total">
                    <td colspan="2"></td>
                    <td>Total:</td>
                    <td>$${total.toFixed(2)}</td>
                </tr>
            </tfoot>
        </table>
    `;

            pdfElement.innerHTML = html;
            document.body.appendChild(pdfElement);

            // Configuración para html2pdf
            const opt = {
                margin: [20, 20, 20, 20],
                filename: `Cotización_${titulo.replace(/\s+/g, '_')}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    scrollY: 0,
                    useCORS: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                onclone: function (clonedDoc) {
                    clonedDoc.querySelectorAll('.btn').forEach(btn => {
                        btn.style.display = 'none';
                    });
                }
            };

            // Generar PDF
            html2pdf().set(opt).from(pdfElement).save().then(() => {
                // Eliminar el elemento temporal después de generar el PDF
                document.body.removeChild(pdfElement);
            });
        });


    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</body>

</html>