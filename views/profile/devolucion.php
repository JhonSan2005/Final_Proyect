<div class="container mt-5">
    <h2>Política de Devoluciones</h2>
    <p>En MR Repuestos, nos comprometemos a ofrecer productos de alta calidad. Si no estás completamente satisfecho con tu compra, estamos aquí para ayudarte.</p>

    <h4>Condiciones para Devoluciones</h4>
    <ul>
        <li>El producto debe estar en su estado original y sin uso.</li>
        <li>La devolución debe realizarse dentro de las primeras 24 horas del días de la compra.</li>
        <li>Es necesario presentar el recibo o comprobante de compra.</li>
    </ul>

    <h4>Proceso de Devolución</h4>
    <ol>
        <li>Completa el formulario de solicitud de devolución a continuación.</li>
        <li>Nos pondremos en contacto contigo para coordinar la devolución.</li>
        <li>Una vez recibido y verificado el producto, procederemos con el reembolso.</li>
    </ol>

    <h4>Formulario de Solicitud de Devolución</h4>
    <br><br>


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
