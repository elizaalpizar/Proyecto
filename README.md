# Sistema de Reservaciones - Energym Costa Rica

## Descripción
Sistema web para gestión de reservaciones de servicios deportivos, incluyendo registro de atletas, inicio de sesión y reservación de servicios.

## Estructura del Proyecto

### Archivos PHP (Backend)
- `Php/Registro.php` - Procesa el registro de nuevos atletas
- `Php/login.php` - Maneja la autenticación de usuarios
- `Php/procesar_reservacion.php` - Procesa las reservaciones
- `Php/logout.php` - Cierra la sesión del usuario
- `Php/verificar_sesion.php` - Verifica si el usuario está autenticado
- `Php/crear_tabla_reservaciones.php` - Crea la tabla de reservaciones en la base de datos

### Archivos HTML (Frontend)
- `Público/RegistroAtleta.html` - Formulario de registro
- `Público/InicioSesion.html` - Formulario de inicio de sesión
- `Privado/Reservacion.php` - Panel de reservaciones (requiere autenticación)

### Archivos CSS
- `Css/Registro.css` - Estilos para el formulario de registro
- `Css/InicioSesion.css` - Estilos para el formulario de login
- `Css/servicios.css` - Estilos para la página de reservaciones

### Archivos JavaScript
- `JS/ValidaciónRegistro.js` - Validaciones del lado del cliente para el registro

## Configuración de la Base de Datos

### Requisitos
- SQL Server con ODBC Driver 17 for SQL Server
- Base de datos: `Proyecto_Progra3`
- Usuario: `sa`
- Contraseña: `19861997.Sr`

### Tablas Requeridas

#### Tabla `atletas`
```sql
CREATE TABLE atletas (
    identificacion BIGINT PRIMARY KEY,
    usuario VARCHAR(100) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido1 VARCHAR(100) NOT NULL,
    apellido2 VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL
);
```

#### Tabla `reservaciones`
```sql
CREATE TABLE reservaciones (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_atleta BIGINT NOT NULL,
    servicio VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    duracion INT NOT NULL,
    comentarios TEXT,
    fecha_creacion DATETIME NOT NULL DEFAULT GETDATE(),
    estado VARCHAR(20) NOT NULL DEFAULT 'activa',
    FOREIGN KEY (id_atleta) REFERENCES atletas(identificacion)
);
```

## Instalación y Configuración

### 1. Configurar la Base de Datos
1. Ejecutar el script SQL para crear la tabla `atletas`
2. Ejecutar `Php/crear_tabla_reservaciones.php` para crear la tabla `reservaciones`

### 2. Verificar la Conexión
1. Ejecutar `Php/test_conexion_simple.php` para verificar la conectividad
2. Asegurarse de que el ODBC Driver 17 esté instalado

### 3. Configurar el Servidor Web
1. Asegurarse de que PHP tenga la extensión ODBC habilitada
2. Configurar XAMPP o el servidor web de preferencia

## Flujo del Sistema

### 1. Registro de Atleta
1. El usuario accede a `RegistroAtleta.html`
2. Completa el formulario con sus datos
3. Los datos se envían a `Registro.php`
4. Se valida la información y se inserta en la base de datos
5. Se redirige al usuario al inicio de sesión

### 2. Inicio de Sesión
1. El usuario accede a `InicioSesion.html`
2. Ingresa su usuario y contraseña
3. Los datos se envían a `login.php`
4. Se verifica la autenticación
5. Si es exitosa, se crea una sesión y se redirige a `Reservacion.php`

### 3. Reservación de Servicios
1. El usuario autenticado accede a `Reservacion.php`
2. Selecciona el servicio, fecha, hora y duración
3. Los datos se envían a `procesar_reservacion.php`
4. Se valida la disponibilidad y se inserta la reservación
5. Se confirma la reservación al usuario

## Servicios Disponibles
- Zona de Funcionales
- Zona de Cardio
- Zona de Pesas
- Nutrición Deportiva
- Sauna y Recuperación

## Horarios Disponibles
- De 6:00 AM a 8:00 PM
- Duración: 1, 1.5 o 2 horas

## Seguridad
- Contraseñas hasheadas con `password_hash()`
- Validación de sesiones para páginas privadas
- Validación de datos en el servidor
- Protección contra SQL injection usando prepared statements

## Solución de Problemas

### Error de Conexión
- Verificar que el servidor SQL Server esté funcionando
- Confirmar que las credenciales sean correctas
- Verificar que el ODBC Driver 17 esté instalado

### Error de Autenticación
- Verificar que el usuario exista en la base de datos
- Confirmar que la contraseña sea correcta
- Verificar que la tabla `atletas` tenga la estructura correcta

### Error de Reservación
- Verificar que la tabla `reservaciones` exista
- Confirmar que no haya conflictos de horarios
- Verificar que la fecha no sea anterior a hoy

## Archivos de Prueba
- `Php/test_conexion_simple.php` - Test básico de conexión
- `Php/crear_tabla_reservaciones.php` - Crear tabla de reservaciones

## Notas Importantes
- El sistema requiere PHP con extensión ODBC
- Las sesiones se manejan con `session_start()`
- Todas las consultas usan prepared statements para seguridad
- Los archivos de configuración están en los archivos PHP principales
