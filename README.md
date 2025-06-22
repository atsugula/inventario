# 🧾 Inventario API

API RESTful desarrollada con Laravel 10 para la gestión eficiente de productos y categorías. Incluye autenticación basada en tokens, autorización por roles, pruebas automatizadas, documentación Swagger y una arquitectura limpia orientada a repositorios.

---

## 🌐 URL en Producción

- [http://inventario.nexusats.com/](http://inventario.nexusats.com/)

---

## 🚀 Requisitos

- PHP 8.1 o superior
- Composer
- MySQL o MariaDB
- Laravel 10
- Entorno local: XAMPP, Valet o Homestead

---

## ⚙️ Instalación local

1. **Clonar el repositorio**
    ```bash
    git clone https://github.com/atsugula/inventario
    cd inventario-api
    ```

2. **Instalar dependencias**
    ```bash
    composer install
    ```

3. **Configurar variables de entorno**
    ```bash
    cp .env.example .env
    ```
    Edita `.env` para definir:
    ```ini
    APP_NAME=Inventario
    APP_URL=http://localhost:8000
    DB_DATABASE=inventario
    DB_USERNAME=root
    DB_PASSWORD=secret
    ```

4. **Generar la clave de aplicación**
    ```bash
    php artisan key:generate
    ```

5. **Ejecutar migraciones y seeders**
    ```bash
    php artisan migrate --seed
    ```

6. **Iniciar el servidor local**
    ```bash
    php artisan serve
    ```

---

## 🧪 Pruebas automatizadas

Para validar la funcionalidad y seguridad de la API, ejecuta:
```bash
php artisan test
```
Las pruebas cubren:
- Registro, login y logout
- CRUD de productos y categorías
- Middleware de autorización y control de acceso

---

## 📬 Colección Postman

1. Abre Postman.
2. Importa `Inventario.postman_collection.json`.
3. Configura la variable `{{base_url}}` como `http://localhost:8000`.
4. Realiza login con:
    ```json
    {
      "email": "admin@example.com",
      "password": "password"
    }
    ```
5. Copia el token recibido y úsalo en los headers como `Authorization: Bearer {{token}}`.

---

## 📚 Documentación Swagger

1. Genera la documentación:
    ```bash
    php artisan l5-swagger:generate
    ```
2. Accede a: [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## 🧠 Decisiones de diseño

### Roles y Autorización

- **Modelo de roles:** El campo `role` en la tabla `users` define el tipo de usuario (`admin` o `user`).
- **Middleware:** Se utiliza el middleware `IsAdmin` para proteger rutas críticas (POST, PUT, DELETE) de productos y categorías.

### Arquitectura

- **Patrón repositorio:** Implementación de interfaces en `app/Repositories` para desacoplar la lógica de acceso a datos.
- **Validación:** Uso de `Validator::make(...)` para asegurar la integridad de los datos.
- **Documentación:** Integración de Swagger mediante `zircote/swagger-php`.
- **Testing:** Pruebas automatizadas con PHPUnit y Laravel Feature Tests.

### Esquema de base de datos

- `users`: incluye campo `role`
- `products`: referencia a `categories`
- `categories`: estructura básica

---

## 👤 Usuario admin por defecto

Si ejecutaste los seeders, puedes usar:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

---

## 📝 Endpoints principales

### Autenticación

- `POST /api/register` - Registro de usuario
- `POST /api/login` - Inicio de sesión
- `POST /api/logout` - Cierre de sesión

### Productos

- `GET /api/products` - Listar productos
- `POST /api/products` - Crear producto (admin)
- `GET /api/products/{id}` - Ver producto
- `PUT /api/products/{id}` - Actualizar producto (admin)
- `DELETE /api/products/{id}` - Eliminar producto (admin)

### Categorías

- `GET /api/categories` - Listar categorías
- `POST /api/categories` - Crear categoría (admin)
- `GET /api/categories/{id}` - Ver categoría
- `PUT /api/categories/{id}` - Actualizar categoría (admin)
- `DELETE /api/categories/{id}` - Eliminar categoría (admin)

---

## 🛠️ Notas adicionales

- El proyecto sigue principios SOLID y buenas prácticas de Laravel.
- El control de acceso está centralizado en middleware y políticas.
- La documentación y pruebas facilitan la mantenibilidad y escalabilidad del sistema.
