# Sistema de Agenda de Contactos

## Descripción

Sistema web de agenda de contactos desarrollado en PHP y MySQL que permite a los usuarios gestionar sus contactos personales de forma segura. Cada usuario tiene acceso únicamente a sus propios contactos mediante un sistema de autenticación por sesiones.

## Características Principales

- **Autenticación de usuarios**: Registro e inicio de sesión seguro
- **Gestión de contactos**: Crear, leer, actualizar y eliminar contactos
- **Datos por usuario**: Cada usuario maneja sus propios contactos de forma independiente
- **Validación de datos**: Validación tanto en cliente como en servidor
- **Interfaz simple**: Diseño minimalista y funcional
- **Base de datos normalizada**: Estructura optimizada con relaciones apropiadas

## Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de datos**: MySQL 5.7+ / MariaDB 10.3+
- **Frontend**: HTML5, CSS3, JavaScript
- **Servidor web**: Apache/Nginx

## Estructura del Proyecto

```
agenda-contactos/
├── includes/
│   ├── database.php      # Configuración de conexión a BD
│   └── funciones.php     # Funciones para manejo de contactos
├── index.php            # Página principal - Lista de contactos
├── login.php            # Formulario de inicio de sesión
├── registro.php         # Formulario de registro de usuarios
├── agregar.php          # Formulario para agregar contactos
├── editar.php           # Formulario para editar contactos
├── editar_perfil.php    # Formulario para editar perfil de usuario
├── logout.php           # Script para cerrar sesión
└── README.md            # Este archivo
```

## Instalación

### Requisitos Previos

- Servidor web con PHP 7.4 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Extensiones PHP: PDO, PDO_MySQL

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [url-del-repositorio]
   cd agenda-contactos
   ```

2. **Crear la base de datos**
   - Ejecutar el script SQL proporcionado en MySQL
   - El script creará automáticamente la base de datos `agenda_contactos`

3. **Configurar la conexión**
   - Editar el archivo `includes/database.php`
   - Modificar las credenciales de conexión:
   ```php
   $servidor = "localhost";
   $usuario = "tu_usuario";
   $contraseña = "tu_contraseña";
   $base_datos = "agenda_contactos";
   ```

4. **Configurar el servidor web**
   - Apuntar el documento root al directorio del proyecto
   - Asegurar que el servidor tenga permisos de lectura en todos los archivos

5. **Verificar la instalación**
   - Acceder a la aplicación desde el navegador
   - Debería aparecer la página de login

## Uso de la Aplicación

### Registro de Usuario

1. Acceder a la aplicación
2. Hacer clic en "Crear una nueva cuenta"
3. Completar el formulario con:
   - Nombre completo
   - Email (debe ser único)
   - Contraseña (mínimo 4 caracteres)
   - Confirmar contraseña
4. Hacer clic en "Crear Cuenta"

### Inicio de Sesión

1. En la página principal, ingresar:
   - Email registrado
   - Contraseña
2. Hacer clic en "Iniciar Sesión"
3. Serás redirigido a la lista de contactos

### Gestión de Contactos

#### Agregar Contacto
1. Desde la página principal, hacer clic en "Agregar Contacto"
2. Completar el formulario:
   - Nombre (obligatorio)
   - Teléfono (opcional)
   - Email (opcional, debe ser válido si se proporciona)
3. Hacer clic en "Agregar Contacto"

#### Ver Contactos
- La página principal muestra todos los contactos del usuario
- Se muestran en una tabla con: nombre, teléfono, email, fecha de creación
- Se muestra el total de contactos registrados

#### Editar Contacto
1. En la lista de contactos, hacer clic en "Editar" del contacto deseado
2. Modificar los campos necesarios
3. Hacer clic en "Actualizar Contacto"

#### Eliminar Contacto
1. En la lista de contactos, hacer clic en "Eliminar"
2. Confirmar la eliminación en el diálogo
3. El contacto será eliminado permanentemente

### Gestión de Perfil

#### Editar Perfil
1. Desde la página principal, hacer clic en "Editar Perfil"
2. Modificar nombre y/o email
3. Opcionalmente cambiar contraseña:
   - Ingresar contraseña actual
   - Ingresar nueva contraseña
   - Confirmar nueva contraseña
4. Hacer clic en "Actualizar Perfil"

### Cerrar Sesión
- Hacer clic en "Cerrar Sesión" desde cualquier página
- Serás redirigido al formulario de login

## Estructura de Base de Datos

### Tabla `usuarios`
- `id`: Identificador único (AUTO_INCREMENT)
- `nombre`: Nombre completo del usuario
- `email`: Email único del usuario
- `contrasena`: Contraseña en texto plano
- `fecha_creacion`: Timestamp de registro

### Tabla `contactos`
- `id`: Identificador único (AUTO_INCREMENT)
- `nombre`: Nombre del contacto
- `telefono`: Número telefónico (opcional)
- `email`: Email del contacto (opcional)
- `usuario_id`: Referencia al usuario propietario
- `fecha_creacion`: Timestamp de creación
- `fecha_actualizacion`: Timestamp de última modificación

## Seguridad

### Medidas Implementadas
- **Validación de entrada**: Todos los campos son validados
- **Prepared Statements**: Prevención de inyección SQL
- **Control de acceso**: Los usuarios solo pueden ver/modificar sus propios contactos
- **Escape de salida**: Prevención de XSS con `htmlspecialchars()`
- **Validación de sesión**: Verificación de autenticación en cada página

### Consideraciones de Seguridad
- Las contraseñas se almacenan en texto plano (no recomendado para producción)
- Para producción, implementar hash de contraseñas con `password_hash()`
- Considerar implementar HTTPS en producción
- Agregar validación adicional del lado cliente

## Funcionalidades Principales

### Archivo `includes/funciones.php`
- `crearContacto()`: Inserta un nuevo contacto en la base de datos
- `obtenerContactos()`: Recupera todos los contactos de un usuario
- `actualizarContacto()`: Modifica un contacto existente
- `eliminarContacto()`: Elimina un contacto específico

### Sistema de Sesiones
- Manejo seguro de sesiones PHP
- Verificación de autenticación en cada página protegida
- Almacenamiento de datos del usuario en la sesión

## Solución de Problemas

### Errores Comunes

**Error de conexión a la base de datos**
- Verificar credenciales en `includes/database.php`
- Asegurar que el servidor MySQL esté ejecutándose
- Verificar que la base de datos existe

**Página en blanco**
- Activar display_errors en PHP
- Revisar logs del servidor web
- Verificar permisos de archivos

**Error 404 en includes/**
- Verificar que la carpeta `includes/` existe
- Asegurar que los archivos `database.php` y `funciones.php` están presentes

## Mejoras Futuras

- Implementar hash de contraseñas
- Agregar paginación para listas largas de contactos
- Implementar búsqueda y filtrado de contactos
- Agregar campos adicionales (dirección, empresa, notas)
- Implementar categorías/etiquetas para contactos
- Agregar exportación/importación de contactos
- Implementar diseño responsive
- Agregar validación con JavaScript

## Licencia

Este proyecto es de uso libre para fines educativos y de aprendizaje.

## Soporte

Para reportar errores o solicitar características, crear un issue en el repositorio del proyecto.