# API Reference

## Endpoints

Esta documentación describe los endpoints disponibles en El Túnel del Cómic.

---

## Rutas Públicas

### Home

```http
GET /
```

**Respuesta:** Página principal con comics destacados.

---

### Catálogo

```http
GET /catalog
```

**Query Parameters:**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `search` | string | Búsqueda por título/descripción |
| `publisher` | int | ID de la editorial |
| `category` | int | ID de la categoría |
| `min_price` | decimal | Precio mínimo |
| `max_price` | decimal | Precio máximo |
| `sort` | string | Ordenamiento: `newest`, `price_asc`, `price_desc`, `title` |
| `page` | int | Número de página |

**Ejemplo:**
```
GET /catalog?search=manga&publisher=1&sort=price_asc
```

---

### Carrito

#### Ver carrito
```http
GET /cart
```

#### Agregar al carrito
```http
POST /cart/add/{comic_id}
```

#### Actualizar cantidad
```http
PATCH /cart/update/{comic_id}
Content-Type: application/x-www-form-urlencoded

quantity=3
```

#### Eliminar item
```http
DELETE /cart/remove/{comic_id}
```

#### Vaciar carrito
```http
DELETE /cart/clear
```

---

### Checkout

#### Ver formulario
```http
GET /checkout
```

#### Procesar orden
```http
POST /checkout
Content-Type: application/x-www-form-urlencoded

customer_name=Juan+Pérez
customer_dni=12345678
customer_email=juan@email.com
customer_phone=+541112345678
payment_method=transfer|mercadopago
```

**Respuesta exitosa:** Redirect a `/checkout/success/{order_id}`

#### Páginas de resultado
```http
GET /checkout/success/{order_id}
GET /checkout/failure/{order_id}
GET /checkout/pending/{order_id}
```

---

### Idioma

```http
GET /lang/{locale}
```

**Locales válidos:** `es`, `en`, `ko`

**Respuesta:** Redirect back con idioma actualizado.

---

## Rutas de Autenticación

### Login
```http
GET /login
POST /login

email=admin@eltuneldelcomic.com
password=admin123
remember=on
```

### Logout
```http
POST /logout
```

### Register
```http
GET /register
POST /register

name=Nuevo+Usuario
email=nuevo@email.com
password=password123
password_confirmation=password123
```

---

## Rutas Admin

> Requiere autenticación + `is_admin = true`

### Comics CRUD

#### Listar comics
```http
GET /admin/comics
```

**Query Parameters:**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `search` | string | Búsqueda por título |
| `status` | string | `active`, `inactive`, `all` |
| `stock` | string | `all`, `low`, `out`, `available` |

#### Crear comic
```http
GET /admin/comics/create
POST /admin/comics
Content-Type: multipart/form-data

title=Nuevo+Comic
description=Descripción+del+comic
price=29.99
original_price=39.99
stock=10
publisher_id=1
categories[]=1
categories[]=2
is_active=1
image=@comic.jpg
```

#### Editar comic
```http
GET /admin/comics/{id}/edit
PUT /admin/comics/{id}
```

#### Eliminar comic
```http
DELETE /admin/comics/{id}
```

#### Acciones masivas
```http
POST /admin/comics/bulk-action
Content-Type: application/x-www-form-urlencoded

action=activate|deactivate|add_stock|price_up|price_down
comic_ids[]=1
comic_ids[]=2
comic_ids[]=3
```

**Acciones disponibles:**
- `activate` - Activar comics
- `deactivate` - Desactivar comics
- `add_stock` - Agregar 5 unidades al stock
- `price_up` - Incrementar precio 10%
- `price_down` - Decrementar precio 10%

---

### Órdenes

#### Listar órdenes
```http
GET /admin/orders
```

**Query Parameters:**

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `status` | string | `pending`, `paid`, `cancelled`, `all` |
| `search` | string | Búsqueda por nombre/email/orden |

#### Ver orden
```http
GET /admin/orders/{id}
```

#### Actualizar estado
```http
PATCH /admin/orders/{id}/status
Content-Type: application/x-www-form-urlencoded

status=paid|pending|cancelled|shipped|delivered
```

---

## Webhooks

### MercadoPago

```http
POST /webhooks/mercadopago
Content-Type: application/json
X-Signature: ts=...,v1=...
X-Request-Id: uuid

{
  "action": "payment.created",
  "api_version": "v1",
  "data": {
    "id": "123456789"
  },
  "date_created": "2024-01-01T00:00:00.000-03:00",
  "id": 123456789,
  "live_mode": true,
  "type": "payment",
  "user_id": "123456"
}
```

**Respuesta:** `200 OK`

---

## Modelos de Datos

### Comic

```json
{
  "id": 1,
  "title": "Spider-Man #1",
  "slug": "spider-man-1",
  "description": "First issue of Amazing Spider-Man",
  "price": 29.99,
  "original_price": 39.99,
  "stock": 10,
  "image_url": "/storage/comics/spiderman.jpg",
  "isbn": "978-0-123456-78-9",
  "year": 2024,
  "is_active": true,
  "is_featured": false,
  "publisher_id": 1,
  "created_at": "2024-01-01T00:00:00.000Z",
  "updated_at": "2024-01-01T00:00:00.000Z",
  "publisher": {
    "id": 1,
    "name": "Marvel",
    "slug": "marvel"
  },
  "categories": [
    {"id": 1, "name": "Action"},
    {"id": 2, "name": "Superhero"}
  ]
}
```

### Order

```json
{
  "id": 1,
  "order_number": "ORD-65A1B2C3D4",
  "customer_name": "Juan Pérez",
  "customer_dni": "12345678",
  "customer_email": "juan@email.com",
  "customer_phone": "+541112345678",
  "subtotal": 59.98,
  "shipping": 0,
  "total": 59.98,
  "status": "pending",
  "payment_method": "transfer",
  "payment_id": null,
  "payment_status": null,
  "created_at": "2024-01-01T00:00:00.000Z",
  "items": [
    {
      "id": 1,
      "comic_id": 1,
      "quantity": 2,
      "price": 29.99,
      "subtotal": 59.98,
      "comic": {
        "id": 1,
        "title": "Spider-Man #1"
      }
    }
  ]
}
```

---

## Códigos de Estado

| Código | Descripción |
|--------|-------------|
| 200 | OK |
| 201 | Creado |
| 302 | Redirect |
| 400 | Bad Request |
| 401 | No autenticado |
| 403 | Prohibido (no admin) |
| 404 | No encontrado |
| 422 | Validación fallida |
| 500 | Error del servidor |

---

## Validaciones

### Checkout

| Campo | Reglas |
|-------|--------|
| `customer_name` | required, string, max:255 |
| `customer_dni` | required, string, max:20 |
| `customer_email` | required, email, max:255 |
| `customer_phone` | nullable, string, max:30 |
| `payment_method` | required, in:transfer,mercadopago |

### Comic (Admin)

| Campo | Reglas |
|-------|--------|
| `title` | required, string, max:255 |
| `description` | nullable, string |
| `price` | required, numeric, min:0 |
| `original_price` | nullable, numeric, min:0 |
| `stock` | required, integer, min:0 |
| `publisher_id` | nullable, exists:publishers,id |
| `categories` | nullable, array |
| `is_active` | boolean |
| `image` | nullable, image, max:2048 |

---

## Rate Limiting

| Endpoint | Límite |
|----------|--------|
| Webhooks | 60/min |
| API general | 60/min |

---

*API Reference - El Túnel del Cómic v1.0*
