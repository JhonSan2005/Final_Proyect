-- Crear procedimiento para registrar intentos fallidos
CREATE PROCEDURE `registrar_intento_fallido` (IN p_correo VARCHAR(100))
BEGIN
    DECLARE p_intentos INT DEFAULT 0;
    DECLARE p_ultimo_intento DATETIME DEFAULT NULL;
    
    -- Obtener el número de intentos fallidos y el último intento
    SELECT intentos, ultimo_intento INTO p_intentos, p_ultimo_intento
    FROM intentos_fallidos
    WHERE correo = p_correo;
    
    -- Si el usuario no tiene intentos fallidos registrados, inicializar las variables
    IF p_intentos IS NULL THEN
        INSERT INTO intentos_fallidos (correo, intentos, ultimo_intento)
        VALUES (p_correo, 1, NOW());
    ELSE
        -- Incrementar el número de intentos fallidos
        UPDATE intentos_fallidos
        SET intentos = intentos + 1, ultimo_intento = NOW()
        WHERE correo = p_correo;
        
        -- Si se alcanzan 3 intentos fallidos, bloquear al usuario
        IF p_intentos + 1 >= 3 THEN
            UPDATE usuario
            SET bloqueado = 1
            WHERE correo = p_correo;
        END IF;
    END IF;
END;

-- Crear procedimiento para restablecer intentos fallidos
CREATE PROCEDURE `restablecer_intentos` (IN p_correo VARCHAR(100))
BEGIN
    -- Restablecer intentos fallidos y eliminar el registro
    DELETE FROM intentos_fallidos
    WHERE correo = p_correo;
END;

-- Crear trigger para actualizar stock después de una venta
CREATE TRIGGER actualizar_stock
AFTER INSERT ON ventas
FOR EACH ROW
BEGIN
    UPDATE productos
    SET stock = stock - NEW.cantidad
    WHERE id_producto = NEW.id_producto;
END;

-- Crear trigger para validar el stock antes de una venta
CREATE TRIGGER validar_stock
BEFORE INSERT ON ventas
FOR EACH ROW
BEGIN
    DECLARE stock_disponible INT;
    
    SELECT stock INTO stock_disponible
    FROM productos
    WHERE id_producto = NEW.id_producto;
    
    IF stock_disponible < NEW.cantidad THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock insuficiente para la venta';
    END IF;
END;

-- Crear trigger para actualizar el estado de la factura después de eliminar una venta
CREATE TRIGGER actualizar_estado_factura
AFTER DELETE ON ventas
FOR EACH ROW
BEGIN
    DECLARE productos_restantes INT;
    
    SELECT COUNT(*) INTO productos_restantes
    FROM ventas
    WHERE id_factura = OLD.id_factura;
    
    IF productos_restantes = 0 THEN
        UPDATE factura
        SET id_estado = (SELECT id FROM estado_factura WHERE estado = 'Cancelada')
        WHERE id = OLD.id_factura;
    END IF;
END;
