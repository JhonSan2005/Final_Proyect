<div class="container">

    <div class="d-flex flex-column">

        <h2 
            class="text-body-secondary text-center my-4 fw-medium"
        >
            Resultados de la búsqueda para "<?php if( isset($parametroABuscar) && $parametroABuscar ) echo $parametroABuscar; ?>"
        </h2>

        <div class="container d-flex gap-5 justify-content-center flex-wrap">

            <?php if (isset($resultadosBusqueda) && $resultadosBusqueda) : ?>
                <?php foreach ($resultadosBusqueda as $resultadoBusqueda) : ?>
                    <div class="card card--product border">
                    <div class="card-img-top-wrapper" style="height: 200px; overflow: hidden;">
                    <img
                        src="<?php echo htmlspecialchars($resultadoBusqueda['imagen_url']); ?>"
                        class="card-img-top img-fluid"
                        alt="Imagen del Producto"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $resultadoBusqueda['nombre_producto']; ?></h5>
                            <p class="card-text"><?php echo $resultadoBusqueda['descripcion'] ?></p>
                            <a href="#" class="btn btn-primary">Agregar al Carrito</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-body-tertiary text-center fw-medium fs-5" role="alert">No hay datos para mostrar</p>
            <?php endif; ?>

        </div>



    </div>
