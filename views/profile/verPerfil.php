<div class="container-xl px-4 mt-4">
    <nav class="nav nav-borders">
        <a class="nav-link active ms-0" href="/profile">Perfil</a>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Foto de Perfil</div>
                <div class="card-body text-center">
                    <img id="profileImage" class="img-account-profile mb-2 img-thumbnail" 
                         src="<?php echo htmlspecialchars($profile['foto_perfil'] ?: 'http://bootdey.com/img/Content/avatar/avatar1.png'); ?>" 
                         alt="Foto de perfil">
                    <div id="previewContainer" class="mb-3" style="display: none;">
                        <img id="previewImage" class="img-thumbnail" src="#" alt="Vista previa">
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
                    
                    <form action="/profile/verPerfil" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="small mb-1" for="documento">Documento</label>
                            <input class="form-control" id="documento" name="documento" type="text" 
                                placeholder="Ingresa tu cédula" value="<?php echo htmlspecialchars($documento); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="nombre">Nombre</label>
                            <input class="form-control" id="nombre" name="nombre" type="text" 
                                placeholder="Ingresa tu nombre" value="<?php echo htmlspecialchars($nombre); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="correo">Correo</label>
                            <input class="form-control" id="correo" name="correo" type="text" 
                                placeholder="Ingresa tu correo" value="<?php echo htmlspecialchars($correo); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="nueva_contrasena">Nueva Contraseña</label>
                            <input class="form-control" id="nueva_contrasena" name="nueva_contrasena" type="password" 
                                placeholder="Ingresa tu nueva contraseña">
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1" for="confirmar_contrasena">Confirmar Contraseña</label>
                            <input class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" type="password" 
                                placeholder="Confirma tu nueva contraseña">
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                    </form>

                    <!-- Formulario para eliminar la cuenta -->
                    <form id="deleteAccountForm" action="/profile/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">Eliminar Cuenta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm("¿Estás seguro de que quieres eliminar tu cuenta? Esta acción es irreversible.")) {
        document.getElementById('deleteAccountForm').submit();
    }
}
</script>
