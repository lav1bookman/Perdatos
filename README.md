# 🌊 Proyecto CRUD Básico: Monitoreo Marino

Este proyecto es una demostración simple en **PHP + MySQL** (usando Docker) para insertar y consultar datos desde la tabla `Usuario`, parte de un sistema de monitoreo marino. Incluye:

- Conexión a base de datos usando PDO
- Insertar usuarios desde un formulario HTML
- Validación del guardado con una tabla desplegable
- Entorno reproducible con Docker Compose

---

## 📁 Estructura del proyecto
```sh
├── php/
│ ├── db.php # Conexión PDO a MySQL
│ ├── usuarios_form.php # Formulario HTML + listado
│ └── usuarios_insertar.php # Lógica de inserción (POST)
```