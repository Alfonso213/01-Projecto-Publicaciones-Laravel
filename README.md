# 01 - Proyecto Publicaciones Laravel

Mi primer proyecto con Laravel. Una aplicación de publicaciones desarrollada como toma de contacto con el framework, centrándome en aprender los fundamentos y las buenas prácticas básicas de seguridad y funcionalidad.

## Sobre el proyecto

Aplicación web que permite a los usuarios registrarse, iniciar sesión y gestionar sus publicaciones. Durante el desarrollo me he enfocado en entender y aplicar correctamente los aspectos esenciales de Laravel como:

- Autenticación y autorización de usuarios
- Protección contra ataques CSRF
- Validación de formularios
- Gestión de rutas protegidas
- Migraciones y modelos con Eloquent
- Internacionalización de la aplicación al español
- Implementación de WebSockets para interacciones en tiempo real

## Tecnologías utilizadas

- PHP + Laravel
- MySQL
- Blade 
- Tailwind CSS
- Laravel Breeze (Santuario de autenticación)
- Laravel Reverb (Servidor de WebSockets)
- Vite / AlpineJS

## Instalación y Configuración

1. Clona el repositorio
```bash
   git clone https://github.com/Alfonso213/01-Projecto-Publicaciones-Laravel.git
```

2. Instala las dependencias de PHP y JavaScript
```bash
   composer install
   npm install
```

3. Configura el entorno
```bash
   cp .env.example .env
   php artisan key:generate
```
**Nota importante**: En el archivo `.env`, asegúrate de configurar las variables de Reverb para habilitar el tiempo real:
```env
BROADCAST_CONNECTION=reverb
REVERB_PORT=8081
VITE_REVERB_PORT="${REVERB_PORT}"
```

4. Base de datos y Semillas (Bulk Seeding)
Configura tu base de datos en el `.env` y ejecuta las migraciones con el seeder optimizado para generar datos masivos:
```bash
   php artisan migrate:fresh --seed --seeder=BulkSeeder
   php artisan config:clear
```

5. Compila los assets
```bash
   npm run build
```

## Ejecución del Proyecto (Tiempo Real Activo)

Para que el sistema de Likes y Comentarios aparezca instantáneamente en las pantallas de otros usuarios, es necesario mantener abiertas dos terminales simultáneamente:

1. **Terminal 1 (App)**: `php artisan serve`
2. **Terminal 2 (WebSockets)**: `php artisan reverb:start --port=8081`

## Arquitectura Avanzada Aplicada

En las fases finales del proyecto se implementaron patrones de diseño para mejorar la escalabilidad:

- **Traits (Likeable y Trendable)**: Se encapsuló la lógica de relaciones polimórficas y filtros de tendencia (Query Scopes) en Traits reutilizables para mantener los modelos limpios.
- **Broadcasting**: Uso de eventos con la interfaz `ShouldBroadcastNow` para garantizar que las interacciones sociales se distribuyan por WebSockets sin latencia de colas.
- **Bulk Seeding**: Implementación de un Seeder de alto rendimiento que utiliza inserciones por bloques (chunks) para poblar la base de datos de forma eficiente.

## Lo aprendido

- Estructura y ciclo de vida de una aplicación Laravel
- Sistema de autenticación con Breeze
- Protección de rutas con middleware
- Relaciones polimórficas y Query Scopes avanzados
- Implementación de WebSockets con Laravel Reverb y Echo
- Optimización de rendimiento en la inserción de datos masivos

## Solución de problemas comunes

- **Caché de configuración**: Si cambias el puerto en el archivo .env y el servidor no responde, ejecuta `php artisan config:clear`.
- **Conflictos de puerto**: Si el puerto 8080 está ocupado, el servidor de WebSockets se cerrará. Se recomienda usar el puerto 8081 como está preconfigurado en esta guía.

---

> Proyecto desarrollado como primer contacto con Laravel siguiendo un curso práctico.
