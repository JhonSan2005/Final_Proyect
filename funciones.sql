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
