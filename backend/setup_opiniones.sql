-- Crear la tabla de opiniones si no existe
CREATE TABLE IF NOT EXISTS opiniones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    idVinilo INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100),
    comentario TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertar datos de prueba (Asegúrate de que existan vinilos con ID 1, 2, etc. en tu tabla 'vinilos', si no, cambia los IDs)
INSERT INTO opiniones (idVinilo, nombre, ciudad, comentario) VALUES 
(1, 'Carlos García', 'Madrid', '¡Increíble sonido! Llegó en perfectas condiciones.'),
(2, 'Laura M.', 'Barcelona', 'Me encanta este álbum, el envío fue rápido.'),
(1, 'Pedro Sanchez', 'Valencia', 'Buen servicio, aunque la caja venía un poco golpeada.'),
(3, 'Ana Torres', 'Sevilla', 'Una joya para mi colección. Recomendado.');
