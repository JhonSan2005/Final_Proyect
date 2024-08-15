<?php
$conexion = Conexion::conectar();
// Consulta para obtener todos los usuarios
$query = "SELECT * FROM usuario";
$result_usuarios = $conexion->query($query);

if (!$result_usuarios) {
    die("Error en la consulta: " . $conexion->error);
}
?>
<!-- Contenedor para la tabla de usuarios -->
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-danger rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-danger">Usuarios Registrados</h2>
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Buscar..." aria-label="Buscar usuarios">
                    <div id="noResults" class="alert alert-danger d-none" role="alert">No se encontraron resultados.</div>
                    <div class="table-responsive">
                        <table id="userTable" class="table table-hover table-striped table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Documento</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>ID Rol</th>
                                    <th>Acciones</th> <!-- Nueva columna para acciones -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result_usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['documento']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                        <td><?php echo htmlspecialchars($row['id_rol']); ?></td>
                                        <td>
                                            <!-- Cambiamos el id por documento en el enlace -->
                                            <a href="/admin/tablaUser?documento=<?php echo htmlspecialchars($row['documento']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <nav id="paginationNav" aria-label="Navegación de paginación" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <!-- Botones de paginación serán añadidos dinámicamente aquí -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        padding: 30px;
    }
    .card {
        border-radius: 15px;
        overflow: hidden;
    }
    .card-body {
        padding: 25px;
    }
    .table {
        margin-top: 20px;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: bold;
    }
    .table tbody tr {
        background-color: #ffffff;
        transition: background-color 0.3s;
    }
    .table tbody tr:hover {
        background-color: #f8d7da; /* Color danger en hover */
    }
    .table tbody td {
        vertical-align: middle;
    }
    .form-control {
        border-radius: 20px;
        box-shadow: inset 0 0 5px rgba(0,0,0,0.1);
    }
    .pagination {
        margin: 0;
    }
    .pagination .page-item.disabled .page-link {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const userTable = document.getElementById('userTable');
    const rows = userTable.querySelectorAll('tbody tr');
    const noResults = document.getElementById('noResults');
    const paginationNav = document.getElementById('paginationNav');
    
    let currentPage = 1;
    const rowsPerPage = 5;
    let totalPages = Math.ceil(rows.length / rowsPerPage);

    function paginate() {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.forEach((row, index) => {
            row.style.display = index >= start && index < end ? '' : 'none';
        });
        updatePagination();
    }

    function updatePagination() {
        paginationNav.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', function (e) {
                e.preventDefault();
                currentPage = i;
                paginate();
            });
            paginationNav.appendChild(pageItem);
        }
        noResults.classList.toggle('d-none', rows.length > 0);
    }

    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        let matchCount = 0;
        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let match = false;
            for (let i = 0; i < cells.length; i++) {
                if (cells[i].textContent.toLowerCase().includes(filter)) {
                    match = true;
                    break;
                }
            }
            row.style.display = match ? '' : 'none';
            if (match) matchCount++;
        });
        noResults.classList.toggle('d-none', matchCount > 0);
        totalPages = Math.ceil(matchCount / rowsPerPage);
        currentPage = 1;
        paginate();
    });

    paginate();
});
</script>
