<div class="container my-3">
    <div class="d-flex align-items-center">
        <span class="me-2">Filtrar por:</span>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Categorías
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <!-- Opción de 'Todo' -->
                <li><a class="dropdown-item" href="/products?category=all" category="all">Todo</a></li>
                
                <!-- Opciones dinámicas de categorías -->
                <?php if (!empty($categorias)) : ?>
                    <?php foreach ($categorias as $categoria) : ?>
                        <li><a class="dropdown-item" href="/products?category=<?php echo htmlspecialchars($categoria['id_categoria'], ENT_QUOTES, 'UTF-8'); ?>" category="<?php echo htmlspecialchars($categoria['id_categoria'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($categoria['nombre_categoria'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li><a class="dropdown-item" href="#">No hay categorías disponibles</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- Productos -->

<div class="container d-flex gap-5 justify-content-center flex-wrap" id="lista-productos">
    <?php if (isset($productos) && $productos): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="card card--product border shadow-sm mb-4" style="width: 18rem;" data-id="<?php echo htmlspecialchars($producto['id_producto']); ?>">
                <div class="card-img-top-wrapper" style="height: 200px; overflow: hidden;">
                    <img
                        src="<?php echo htmlspecialchars($producto['imagen_url']); ?>"
                        class="card-img-top img-fluid"
                        alt="Imagen del Producto"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <hr>

                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <span class="d-block mb-1">Precio: <span class="card-price"><?php echo htmlspecialchars($producto['precio']); ?></span> USD</span>
                    <span class="d-block mb-1">Impuesto: <span class="card-taxes"><?php echo htmlspecialchars($producto['impuesto']); ?></span> %</span>
                    <span class="d-block mb-1">Cantidad en Bodega: <span class="card-stock"><?php echo htmlspecialchars($producto['stock']); ?></span></span>
                    
                    <!-- Sección de calificación de estrellas -->
                    <div class="rating mb-3">
                        <?php
                        $rating = isset($producto['rating']) ? htmlspecialchars($producto['rating']) : 0; // Asegurarse de que $producto['rating'] existe
                        for ($i = 1; $i <= 5; $i++):
                        ?>
                            <span class="fa fa-star <?php echo $i <= $rating ? 'checked' : ''; ?>"></span>
                        <?php endfor; ?>
                    </div>

                    <button class="btn btn-primary add-to-cart">
                        Agregar al Carrito
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="alert alert-light" role="alert">No hay datos para mostrar</p>
    <?php endif; ?>
</div>
