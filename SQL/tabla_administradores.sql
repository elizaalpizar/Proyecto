-- Crear tabla de administradores
CREATE TABLE administradores (
    id_admin INT IDENTITY(1,1) PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    rol VARCHAR(50) DEFAULT 'admin',
    fecha_creacion DATETIME DEFAULT GETDATE(),
    ultimo_acceso DATETIME NULL,
    activo BIT DEFAULT 1
);

-- Insertar administrador por defecto (contraseña: admin123)
-- La contraseña debe estar hasheada con password_hash() de PHP
INSERT INTO administradores (usuario, contrasena, nombre, apellido, correo, rol) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', 'admin@energym.com', 'super_admin');

-- Crear índices para mejorar el rendimiento
CREATE INDEX idx_admin_usuario ON administradores(usuario);
CREATE INDEX idx_admin_correo ON administradores(correo);
CREATE INDEX idx_admin_activo ON administradores(activo);
