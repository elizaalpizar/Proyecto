# Sistema de Reservaciones - Energym Costa Rica

## Descripción
Sistema web para gestión de reservaciones de servicios deportivos, incluyendo registro de atletas, inicio de sesión y reservación de servicios.

## Estructura del Proyecto

### Archivos PHP (Backend)
- `Php/Registro.php` - Procesa el registro de nuevos atletas
- `Php/login.php` - Maneja la autenticación de usuarios atletas
- `Php/loginAdmin.php` - Maneja la autenticación de administradores
- `Php/procesar_reservacion.php` - Procesa las reservaciones
- `Php/logout.php` - Cierra la sesión del usuario atleta
- `Php/logoutAdmin.php` - Cierra la sesión del administrador
- `Php/verificar_sesion.php` - Verifica si el usuario atleta está autenticado
- `Php/verificar_sesion_admin.php` - Verifica si el administrador está autenticado
- `Php/crear_tabla_reservaciones.php` - Crea la tabla de reservaciones en la base de datos

### Archivos HTML (Frontend)
- `Público/RegistroAtleta.html` - Formulario de registro
- `Público/SeleccionTipoUsuario.html` - Página de selección de tipo de usuario
- `Público/InicioSesion.html` - Formulario de inicio de sesión para atletas
- `Admin/InicioSesionAdmin.html` - Formulario de inicio de sesión para administradores
- `Privado/Reservacion.php` - Panel de reservaciones (requiere autenticación de atleta)
- `Admin/Catalogo_menu(CRUD).html` - Panel de administración (requiere autenticación de admin)

### Archivos CSS
- `Css/Registro.css` - Estilos para el formulario de registro
- `Css/InicioSesion.css` - Estilos para el formulario de login
- `Css/SeleccionTipoUsuario.css` - Estilos para la página de selección de tipo de usuario
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

#### Tabla `administradores`
```sql
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
```

## Instalación y Configuración

### 1. Configurar la Base de Datos
1. Ejecutar el script SQL para crear la tabla `atletas`
2. Ejecutar `Php/crear_tabla_reservaciones.php` para crear la tabla `reservaciones`
3. Ejecutar `SQL/tabla_administradores.sql` para crear la tabla `administradores`

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

### 2. Selección de Tipo de Usuario
1. El usuario accede a `SeleccionTipoUsuario.html`
2. Selecciona si es "Atleta" o "Administrador"
3. Se redirige al formulario de inicio de sesión correspondiente

### 3. Inicio de Sesión
#### Para Atletas:
1. El usuario accede a `InicioSesion.html`
2. Ingresa su usuario y contraseña
3. Los datos se envían a `login.php`
4. Se verifica la autenticación
5. Si es exitosa, se crea una sesión y se redirige a `Reservacion.php`

#### Para Administradores:
1. El usuario accede a `InicioSesionAdmin.html`
2. Ingresa su usuario y contraseña
3. Los datos se envían a `loginAdmin.php`
4. Se verifica la autenticación
5. Si es exitosa, se crea una sesión y se redirige a `Catalogo_menu(CRUD).html`

### 4. Reservación de Servicios
1. El usuario autenticado accede a `Reservacion.php`
2. Selecciona el servicio, fecha, hora y duración
3. Los datos se envían a `procesar_reservacion.php`
4. Se valida la disponibilidad y se inserta la reservación
5. Se confirma la reservación al usuario

### 5. Panel de Administración
1. El administrador autenticado accede a `Catalogo_menu(CRUD).html`
2. Puede gestionar usuarios, reservaciones y configuraciones del sistema
3. Acceso a funciones CRUD para mantenimiento de la base de datos

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
- `SQL/tabla_administradores.sql` - Script para crear tabla de administradores

## Notas Importantes
- El sistema requiere PHP con extensión ODBC
- Las sesiones se manejan con `session_start()`
- Todas las consultas usan prepared statements para seguridad
- Los archivos de configuración están en los archivos PHP principales
- El sistema ahora separa usuarios atletas de administradores
- Las sesiones de administrador y atleta son independientes
- Credenciales por defecto del administrador: usuario: `admin`, contraseña: `admin123`
