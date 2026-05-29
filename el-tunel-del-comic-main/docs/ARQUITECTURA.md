# Arquitectura del Proyecto

## Descripción General

Este documento describe la arquitectura técnica de **El Túnel del Cómic**, una aplicación de e-commerce desarrollada con Laravel 13.

---

## Stack Tecnológico

```mermaid
flowchart TB
    subgraph Frontend["🎨 Frontend"]
        BLADE[Blade Templates]
        TAILWIND[Tailwind CSS]
        JS[Vanilla JavaScript]
        ALPINE[Alpine.js - opcional]
    end

    subgraph Backend["⚙️ Backend"]
        LARAVEL[Laravel 13]
        PHP[PHP 8.2+]
        ELOQUENT[Eloquent ORM]
    end

    subgraph Data["🗄️ Data Layer"]
        MYSQL[(MySQL 8.0)]
        REDIS[(Redis - cache)]
        SESSION[File/DB Sessions]
    end

    subgraph External["🔗 External Services"]
        MP[MercadoPago API]
        PP[PayPal API]
        MAIL[SMTP/Mail]
    end

    Frontend --> Backend
    Backend --> Data
    Backend --> External
```

---

## Patrón MVC en Laravel

```mermaid
flowchart LR
    subgraph Request["📥 HTTP Request"]
        R[Request]
    end

    subgraph Route["🛤️ Routing"]
        WEB[web.php]
        MW[Middleware]
    end

    subgraph Controller["⚙️ Controller"]
        C[Controller]
        RQ[Form Request]
    end

    subgraph Model["📦 Model"]
        M[Eloquent Model]
        REL[Relationships]
        SCOPE[Scopes]
    end

    subgraph View["👁️ View"]
        V[Blade Template]
        COMP[Components]
        LAYOUT[Layouts]
    end

    subgraph Response["📤 HTTP Response"]
        RES[Response/JSON]
    end

    R --> WEB --> MW --> C
    C --> RQ
    C --> M --> REL
    M --> SCOPE
    C --> V --> COMP
    V --> LAYOUT
    V --> RES
```

---

## Estructura de Directorios

```
el-tunel-del-comic-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── ComicController.php
│   │   │   │   └── OrderController.php
│   │   │   ├── CartController.php
│   │   │   ├── CatalogController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── LanguageController.php
│   │   │   └── WebhookController.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   └── SetLocale.php
│   │   └── Requests/
│   │       └── CheckoutRequest.php
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Comic.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Publisher.php
│   │   └── User.php
│   └── Services/
│       ├── MercadoPagoService.php
│       └── PayPalService.php
├── bootstrap/
│   └── app.php                    # Middleware config
├── config/
│   ├── app.php
│   ├── database.php
│   └── services.php               # MercadoPago + PayPal config
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│       ├── AdminUserSeeder.php
│       ├── CategorySeeder.php
│       ├── ComicSeeder.php
│       └── PublisherSeeder.php
├── docs/
│   ├── README.md
│   ├── ARQUITECTURA.md
│   ├── GUIA_RAPIDA.md
│   └── API.md
├── lang/
│   ├── en/messages.php
│   ├── es/messages.php
│   └── ko/messages.php
├── public/
│   └── storage/                   # Symlink to storage
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── comics/
│       │   └── orders/
│       ├── auth/
│       ├── cart/
│       ├── catalog/
│       ├── checkout/
│       ├── home/
│       └── layouts/
│           ├── app.blade.php
│           ├── navbar.blade.php
│           └── footer.blade.php
├── routes/
│   ├── auth.php
│   └── web.php
├── storage/
│   └── app/public/comics/
├── tests/
│   ├── Feature/
│   └── Unit/
└── .env
```

---

## Modelos y Relaciones

```mermaid
classDiagram
    class Comic {
        +id: bigint
        +title: string
        +slug: string
        +description: text
        +price: decimal
        +stock: int
        +is_active: bool
        +publisher_id: bigint
        +publisher() Publisher
        +categories() BelongsToMany
        +scopeActive() Builder
        +scopeInStock() Builder
    }

    class Publisher {
        +id: bigint
        +name: string
        +slug: string
        +country: string
        +comics() HasMany
    }

    class Category {
        +id: bigint
        +name: string
        +slug: string
        +color: string
        +comics() BelongsToMany
    }

    class Order {
        +id: bigint
        +order_number: string
        +customer_name: string
        +customer_email: string
        +total: decimal
        +status: enum
        +payment_method: string
        +items() HasMany
        +generateOrderNumber() string
        +isPaid() bool
    }

    class OrderItem {
        +id: bigint
        +order_id: bigint
        +comic_id: bigint
        +quantity: int
        +price: decimal
        +order() BelongsTo
        +comic() BelongsTo
    }

    class User {
        +id: bigint
        +name: string
        +email: string
        +is_admin: bool
    }

    Publisher "1" --> "*" Comic : publishes
    Comic "*" --> "*" Category : belongs to
    Order "1" --> "*" OrderItem : contains
    Comic "1" --> "*" OrderItem : included in
```

---

## Flujo de Datos

### Flujo de Compra

```mermaid
sequenceDiagram
    participant B as Browser
    participant R as Router
    participant C as Controller
    participant S as Service
    participant PS as PayPalService
    participant M as Model
    participant DB as Database
    participant MP as MercadoPago
    participant PP as PayPal

    B->>R: POST /cart/add/1
    R->>C: CartController@add
    C->>M: Comic::find(1)
    M->>DB: SELECT
    DB-->>M: Comic data
    C->>C: session()->put('cart')
    C-->>B: Redirect with flash

    B->>R: POST /checkout
    R->>C: CheckoutController@process
    C->>C: Validate request
    C->>M: Create Order
    M->>DB: INSERT order
    C->>M: Create OrderItems
    M->>DB: INSERT order_items
    C->>M: Decrement stock
    M->>DB: UPDATE comics

    alt MercadoPago
        C->>S: MercadoPagoService
        S->>MP: Create Preference
        MP-->>S: init_point URL
        C-->>B: Redirect to MP
    else PayPal
        C->>PS: PayPalService
        PS->>PP: Create Order
        PP-->>PS: approve URL
        C-->>B: Redirect to PayPal
    else Transfer
        C-->>B: Redirect to success
    end
```

### Flujo de Autenticación

```mermaid
sequenceDiagram
    participant B as Browser
    participant MW as Middleware
    participant C as Controller
    participant AUTH as Auth Guard
    participant DB as Database

    B->>MW: Request /admin
    MW->>AUTH: Check auth
    alt Not authenticated
        MW-->>B: Redirect /login
    else Authenticated
        MW->>AUTH: Check is_admin
        alt Not admin
            MW-->>B: 403 Forbidden
        else Is admin
            MW->>C: Continue request
            C-->>B: Admin page
        end
    end
```

---

## Middleware Stack

```mermaid
flowchart TD
    subgraph Global["Global Middleware"]
        CSRF[VerifyCsrfToken]
        COOKIES[EncryptCookies]
        SESSION[StartSession]
    end

    subgraph Web["Web Middleware Group"]
        LOCALE[SetLocale]
    end

    subgraph Auth["Auth Middleware"]
        AUTHENTICATE[Authenticate]
    end

    subgraph Admin["Admin Middleware"]
        ISADMIN[AdminMiddleware]
    end

    REQUEST[HTTP Request] --> Global
    Global --> Web
    Web --> |/admin/*| Auth
    Auth --> |is_admin| Admin
    Admin --> CONTROLLER[Controller]

    Web --> |public routes| CONTROLLER
```

---

## Servicios Externos

### MercadoPago Integration

```mermaid
flowchart LR
    subgraph App["Laravel App"]
        CTRL[Controller]
        SVC[MercadoPagoService]
        WHK[WebhookController]
    end

    subgraph MP["MercadoPago"]
        API[REST API]
        CHK[Checkout Pro]
        WH[Webhooks]
    end

    CTRL -->|1. Create preference| SVC
    SVC -->|2. POST /preferences| API
    API -->|3. preference_id + init_point| SVC
    SVC -->|4. Redirect URL| CTRL
    CTRL -->|5. Redirect user| CHK
    WH -->|6. POST notification| WHK
    WHK -->|7. GET /payments/:id| API
    WHK -->|8. Update order| DB[(Database)]
```

---

## Configuración de Caché

```mermaid
flowchart TB
    subgraph Cache["Cache Strategy"]
        FILE[File Cache - default]
        REDIS[Redis - production]
        DB[Database - sessions]
    end

    subgraph Usage["Cache Usage"]
        VIEWS[Compiled Views]
        CONFIG[Config Cache]
        ROUTES[Route Cache]
        SESSION[Sessions]
    end

    FILE --> VIEWS
    FILE --> CONFIG
    FILE --> ROUTES
    DB --> SESSION
```

---

## Testing Strategy

```mermaid
flowchart TB
    subgraph Unit["Unit Tests"]
        UM[Model Tests]
        US[Service Tests]
    end

    subgraph Feature["Feature Tests"]
        FC[Controller Tests]
        FA[Admin Tests]
        FI[Integration Tests]
    end

    subgraph E2E["End-to-End"]
        BROWSER[Browser Tests]
    end

    Unit --> Feature --> E2E
```

### Cobertura de Tests

| Tipo | Archivo | Cobertura |
|------|---------|-----------|
| Unit | ComicTest.php | Modelo Comic |
| Unit | OrderTest.php | Modelo Order |
| Unit | MercadoPagoServiceTest.php | Servicio MP |
| Feature | CatalogTest.php | Catálogo |
| Feature | CartTest.php | Carrito |
| Feature | CheckoutTest.php | Checkout |
| Feature | AdminTest.php | Panel Admin |

---

## Despliegue

### Requisitos de Servidor

```mermaid
flowchart LR
    subgraph Server["Servidor"]
        PHP[PHP 8.2+]
        MYSQL[MySQL 8.0+]
        NGINX[Nginx/Apache]
        COMPOSER[Composer 2.x]
    end

    subgraph Extensions["PHP Extensions"]
        PDO[PDO MySQL]
        MBSTRING[mbstring]
        OPENSSL[OpenSSL]
        CURL[cURL]
        JSON[JSON]
    end

    Server --> Extensions
```

### Checklist de Producción

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Configurar SSL/HTTPS
- [ ] Configurar MercadoPago producción
- [ ] Configurar backups de DB
- [ ] Configurar logs

---

## Seguridad

### Medidas Implementadas

```mermaid
mindmap
    root((🔒 Security))
        CSRF
            Token en forms
            Verificación automática
        XSS
            Blade escaping
            Content Security Policy
        SQL Injection
            Eloquent ORM
            Query bindings
        Auth
            bcrypt passwords
            Session regeneration
            Remember token
        Admin
            Middleware protection
            Role-based access
```

---

## Escalabilidad

### Consideraciones Futuras

1. **Horizontal Scaling**
   - Load balancer
   - Session storage en Redis
   - File storage en S3

2. **Optimización**
   - Query caching
   - Eager loading
   - Database indexing

3. **Queue System**
   - Laravel Queues para emails
   - Procesamiento de webhooks

---

*Documentación de arquitectura - El Túnel del Cómic v1.0*
