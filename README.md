<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Project Requirements
- PHP Version: 8.2.12
- Framework: Laravel 11.20.0
- Database Drivers: SQL Server (sqlsrv) controllers enabled
```cmd
</> php artisan make:migration create_clientes_table
```
```cmd
</> php artisan session:table
```
```cmd
</> php artisan migrate
```
```cmd
</> php artisan serve  
```
---
    POST → Crear nuevo cliente
    http://127.0.0.1:8000/api/clientes
```json
{
    "nombre": "phpeitor",
    "fecha_cita": "20224-09-14",
    "hora_cita": "09:00",
    "nombre_medico": "Dr. AMV",
    "nombre_centro": "ESSALUD CIX",
    "telefono": "942890820"
}
```
---
    GET → Obtener cliente específico
    http://127.0.0.1:8000/api/clientes/{id}
---
    GET → Obtener todos los clientes
    http://127.0.0.1:8000/api/clientes
---
    DELETE → Eliminar cliente
    http://127.0.0.1:8000/api/clientes/{id}
---
    PUT → Actualizar cliente
    http://127.0.0.1:8000/api/clientes/{id}
```json
{
    "nombre": "phpeitor update",
    "fecha_cita": "20224-09-15",
    "hora_cita": "10:00",
    "nombre_medico": "Dr. TRUX",
    "nombre_centro": "ESSALUD TRUX",
    "telefono": "942890820"
}
```
---
    PATCH → Actualizar parcialmente cliente
    http://127.0.0.1:8000/api/clientes
```json
{
    "id": "1",
    "estado": "CONFIRMADO"
}
```
```cmd
</> php artisan vscode:update-font
```
