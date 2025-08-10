-- Crear tabla de reservaciones
CREATE TABLE reservaciones (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_atleta VARCHAR(20) NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    duracion DECIMAL(2,1) NOT NULL,
    comentarios TEXT,
    fecha_creacion DATETIME NOT NULL,
    estado VARCHAR(20) DEFAULT 'activa',
    FOREIGN KEY (id_atleta) REFERENCES atletas(id)
);

-- Crear índice para mejorar el rendimiento de las consultas
CREATE INDEX idx_reservaciones_fecha_hora ON reservaciones(fecha, hora, servicio);
CREATE INDEX idx_reservaciones_atleta ON reservaciones(id_atleta);

-- Insertar algunos datos de ejemplo (opcional)
-- INSERT INTO reservaciones (id_atleta, servicio, fecha, hora, duracion, comentarios, fecha_creacion, estado)
-- VALUES 
-- ('123456789', 'zona_funcionales', '2025-01-15', '08:00:00', 1.0, 'Primera sesión de entrenamiento', GETDATE(), 'activa'),
-- ('987654321', 'zona_cardio', '2025-01-15', '09:00:00', 1.5, 'Entrenamiento cardiovascular', GETDATE(), 'activa');
