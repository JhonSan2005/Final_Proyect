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
                    <form action="/admin/adminPerfil" method="POST" enctype="multipart/form-data">
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
                        <button class="btn btn-primary" type="submit">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
