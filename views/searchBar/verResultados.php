<div class="container">
    <div class="d-flex flex-column">
        <h2 class="text-body-secondary text-center my-4 fw-medium">
            Resultados de la búsqueda para "<?php if (isset($parametroABuscar) && $parametroABuscar) echo htmlspecialchars($parametroABuscar); ?>"
        </h2>

        <div class="container d-flex gap-5 justify-content-center flex-wrap" id="lista-productos">
            <?php if (isset($resultadosBusqueda) && $resultadosBusqueda) : ?>
                <?php foreach ($resultadosBusqueda as $resultadoBusqueda) : ?>
                    <div class="card card--product border shadow-sm mb-4" style="width: 18rem;" data-id="<?php echo htmlspecialchars($resultadoBusqueda['id_producto']); ?>">
                        <div class="card-img-top-wrapper" style="height: 200px; overflow: hidden;">
                            <img
                                src="<?php echo htmlspecialchars($resultadoBusqueda['imagen_url']); ?>"
                                class="card-img-top img-fluid"
                                alt="Imagen del Producto"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <hr>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($resultadoBusqueda['nombre_producto']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($resultadoBusqueda['descripcion']); ?></p>
                            <span class="d-block mb-1">Precio: <span class="card-price"><?php echo htmlspecialchars($resultadoBusqueda['precio']); ?></span> USD</span>
                            <span class="d-block mb-1">Impuesto: <span class="card-taxes"><?php echo htmlspecialchars($resultadoBusqueda['impuesto']); ?></span> %</span>
                            <span class="d-block mb-1">Cantidad en Bodega: <span class="card-stock"><?php echo htmlspecialchars($resultadoBusqueda['stock']); ?></span></span>

                            <!-- Sección de calificación de estrellas -->
                            <div class="rating mb-3">
                                <?php
                                $rating = isset($resultadoBusqueda['rating']) ? htmlspecialchars($resultadoBusqueda['rating']) : 0; // Asegurarse de que $resultadoBusqueda['rating'] existe
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
            <?php else : ?>
                <p class="text-body-tertiary text-center fw-medium fs-5" role="alert">No hay datos para mostrar</p>
            <?php endif; ?>
        </div>
    </div>
</div>
