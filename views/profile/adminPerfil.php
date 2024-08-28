<div class="container-xl px-4 mt-4">
    <hr class="mt-0 mb-4">
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Foto de Perfil</div>
                <div class="card-body text-center">
                    <?php
                    // Establece la URL por defecto
                    $defaultImage = 'http://bootdey.com/img/Content/avatar/avatar1.png';
                    
                    // Verifica si $profile['foto_perfil'] tiene un valor y es una URL válida
                    $profileImage = !empty($profile['foto_perfil']) ? htmlspecialchars($profile['foto_perfil']) : $defaultImage;
                    ?>
                    <img id="profileImage" class="img-account-profile mb-2 img-thumbnail" 
                         src="<?php echo $profileImage; ?>" 
                         alt="Foto de perfil" style="max-width: 150px;">

                    

                    <div id="previewContainer" class="mb-3" style="display: none;">
                        <img id="previewImage" class="img-thumbnail" src="#" alt="Vista previa" style="max-width: 150px;">
                        <button type="button" class="btn btn-danger mt-2" onclick="cancelUpload()">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Detalles de la cuenta</div>
                <div class="card-body">
                    <?php 
                    $alertas = Alerta::obtenerAlertas();
                    $successMessages = array_filter($alertas, fn($alerta) => $alerta['type'] === 'success');
                    $dangerMessages = array_filter($alertas, fn($alerta) => $alerta['type'] === 'danger');
                    ?>

                    <?php if (!empty($successMessages)): ?>
                        <div class="alert alert-success">
                            <?php foreach ($successMessages as $mensaje): ?>
                                <?php echo htmlspecialchars($mensaje['msg']); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($dangerMessages)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($dangerMessages as $mensaje): ?>
                                <?php echo htmlspecialchars($mensaje['msg']); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="/admin/adminPerfil" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label" for="documento">Documento</label>
                            <input class="form-control" id="documento" name="documento" type="text" 
                                placeholder="Ingresa tu cédula" value="<?php echo htmlspecialchars($documento); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nombre">Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" 
                                placeholder="Ingresa tu nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="correo">Correo</label>
                            <input class="form-control" id="correo" name="correo" type="email" 
                                placeholder="Ingresa tu correo" value="<?php echo htmlspecialchars($correo); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nueva_contrasena">Nueva Contraseña</label>
                            <input class="form-control" id="nueva_contrasena" name="nueva_contrasena" type="password" 
                                placeholder="Ingresa tu nueva contraseña">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="confirmar_contrasena">Confirmar Contraseña</label>
                            <input class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" type="password" 
                                placeholder="Confirma tu nueva contraseña">
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

