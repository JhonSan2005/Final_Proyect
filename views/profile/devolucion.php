<form action="/procesarDevolucion" method="POST">
    <div class="form-group">
        <label for="id_factura">ID de la Factura</label>
        <input type="text" class="form-control" id="id_factura" name="id_factura" required>
    </div>

    <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
</form>

<?php if (isset($mensaje)): ?>
    <div class="alert alert-info">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>
