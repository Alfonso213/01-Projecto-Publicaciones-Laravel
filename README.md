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

## Tecnologías utilizadas

- **PHP** + **Laravel**
- **MySQL**
- **Blade** 
- **Tailwind CSS**
- **Laravel Breeze** 
- **Vite**

## Instalación

1. Clona el repositorio
```bash
   git clone https://github.com/Alfonso213/01-Projecto-Publicaciones-Laravel.git
```

2. Instala las dependencias
```bash
   composer install
   npm install
```

3. Copia el archivo de entorno
```bash
   cp .env.example .env
```

4. Genera la clave de la aplicación
```bash
   php artisan key:generate
```

5. Configura tu base de datos en el `.env` y ejecuta las migraciones
```bash
   php artisan migrate
```

6. Compila los assets
```bash
   npm run dev
```

7. Inicia el servidor
```bash
   php artisan serve
```

## Lo aprendido

- Estructura y ciclo de vida de una aplicación Laravel
- Sistema de autenticación con Breeze
- Protección de rutas con middleware
- Validación de datos en el servidor
- Relaciones entre modelos con Eloquent
- Traducción y localización al español

---

> Proyecto desarrollado como primer contacto con Laravel siguiendo un curso práctico.
