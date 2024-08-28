<!-- Suponiendo que la variable $categoria contiene la categoría a editar -->
<div class="container">
    <h1 class="mt-5"><?php echo htmlspecialchars($title); ?></h1>

    <?php if ($resultado): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($resultado); ?>
        </div>
    <?php endif; ?>

    <form action="/admin/actualizarCategoria?id=<?php echo htmlspecialchars($categoria['id_categoria']); ?>" method="POST">
        <div class="mb-3">
            <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
            <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" value="<?php echo htmlspecialchars($categoria['nombre_categoria']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
    </form>
</div>
