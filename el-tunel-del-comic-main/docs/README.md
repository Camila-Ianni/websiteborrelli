# El Túnel del Cómic - Documentación Técnica

> 🎨 **Tienda online de cómics y manga** con diseño industrial/manga-style  
> 🛠️ **Stack:** Laravel 13 + MySQL + Tailwind CSS + MercadoPago + PayPal

---

## Índice

1. [Vista General](#vista-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Modelo de Base de Datos](#modelo-de-base-de-datos)
4. [Diseño Visual Original](#diseño-visual-original)
5. [Flujos de Usuario](#flujos-de-usuario)
6. [API de Pagos](#api-de-pagos)
7. [Multilenguaje](#multilenguaje)
8. [Rutas](#rutas)
9. [Instalación](#instalación)
10. [Testing](#testing)

---

## Vista General

**El Túnel del Cómic** es una tienda online especializada en cómics, manga y novelas gráficas. El proyecto migró desde un diseño HTML estático hacia una aplicación Laravel completa.

### Características Principales

- ✅ Catálogo con filtros (editorial, categoría, precio)
- ✅ Carrito de compras con sesión
- ✅ Checkout con órdenes y descuento de stock
- ✅ Panel Admin protegido con autenticación
- ✅ Acciones masivas (activar/desactivar, stock, precios)
- ✅ Multilenguaje (ES/EN/KO)
- ✅ Integración MercadoPago + PayPal (checkout)

### Credenciales

| Rol | Email | Password |
|-----|-------|----------|
| Admin | admin@eltuneldelcomic.com | admin123 |

---

## Arquitectura del Sistema

### Diagrama de Capas

```mermaid
flowchart TB
    subgraph Browser["🌐 Browser"]
        direction LR
        HTML[HTML/Blade]
        TW[Tailwind CSS]
        JS[JavaScript]
    end

    subgraph Laravel["⚙️ Laravel Application"]
        direction TB
        
        subgraph Routes["Routes"]
            WEB[web.php]
        end
        
        subgraph Middleware["Middleware"]
            AUTH[Auth]
            ADMIN[AdminMiddleware]
            LOCALE[SetLocale]
        end
        
        subgraph Controllers["Controllers"]
            HC[HomeController]
            CC[CatalogController]
            CTC[CartController]
            CHC[CheckoutController]
            AC[Admin\\ComicController]
            AO[Admin\\OrderController]
            PAY[PaymentController]
        end
        
        subgraph Services["Services"]
            MP[MercadoPagoService]
            PP[PayPalService]
        end
        
        subgraph Models["Models"]
            COM[Comic]
            PUB[Publisher]
            CAT[Category]
            ORD[Order]
            OI[OrderItem]
            USR[User]
        end
    end

    subgraph External["🔗 External"]
        MPAPI[MercadoPago API]
    end

    subgraph Database["🗄️ MySQL"]
        DB[(el_tunel_del_comic)]
    end

    Browser --> Routes
    Routes --> Middleware
    Middleware --> Controllers
    Controllers --> Services
    Controllers --> Models
    Services --> MPAPI
    Models --> DB
```

### Estructura de Directorios

```mermaid
flowchart LR
    subgraph Root["📁 el-tunel-del-comic-laravel"]
        APP["app/"]
        RES["resources/"]
        PUB["public/"]
        DOCS["docs/"]
        ROUTES["routes/"]
        DB["database/"]
        TESTS["tests/"]
    end

    subgraph AppDir["app/"]
        CONT["Controllers/"]
        MOD["Models/"]
        MID["Middleware/"]
        SVC["Services/"]
    end

    subgraph ResDir["resources/"]
        VIEWS["views/"]
        LANG["../lang/"]
    end

    subgraph ViewsDir["views/"]
        LAY["layouts/"]
        CAT2["catalog/"]
        CART2["cart/"]
        CHK["checkout/"]
        ADM["admin/"]
        AUTH2["auth/"]
    end

    APP --> AppDir
    RES --> ResDir
    VIEWS --> ViewsDir
```

---

## Modelo de Base de Datos

### Diagrama Entidad-Relación

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        string password
        boolean is_admin
        timestamp email_verified_at
        string remember_token
        timestamps created_at
    }

    PUBLISHERS {
        bigint id PK
        string name
        string slug UK
        string country
        string logo_url
        timestamps created_at
    }

    CATEGORIES {
        bigint id PK
        string name
        string slug UK
        string color
        timestamps created_at
    }

    COMICS {
        bigint id PK
        string title
        string slug UK
        text description
        decimal price
        decimal original_price
        int stock
        string image_url
        string isbn
        int year
        boolean is_active
        boolean is_featured
        bigint publisher_id FK
        timestamps created_at
    }

    CATEGORY_COMIC {
        bigint comic_id FK
        bigint category_id FK
    }

    ORDERS {
        bigint id PK
        string order_number UK
        string customer_name
        string customer_dni
        string customer_email
        string customer_phone
        decimal subtotal
        decimal shipping
        decimal total
        enum status
        string payment_id
        string payment_status
        text notes
        timestamps created_at
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint comic_id FK
        int quantity
        decimal unit_price
        decimal total
        timestamps created_at
    }

    PUBLISHERS ||--o{ COMICS : "publica"
    COMICS }o--o{ CATEGORIES : "pertenece"
    COMICS ||--o{ ORDER_ITEMS : "incluido en"
    ORDERS ||--o{ ORDER_ITEMS : "contiene"
```

### Valores de Enumeración

```mermaid
flowchart LR
    subgraph OrderStatus["Order Status"]
        PEND[pending]
        PAID[paid]
        SHIP[shipped]
        DELIV[delivered]
        CANC[cancelled]
    end

    PEND --> PAID
    PAID --> SHIP
    SHIP --> DELIV
    PEND --> CANC
```

---

## Diseño Visual Original

El diseño original HTML tiene un estilo **industrial manga** con las siguientes características:

### Paleta de Colores

```mermaid
mindmap
    root((🎨 Brand))
        Primary
            #FDB813 Yellow Gold
            #785500 Dark Gold
        Background
            #FFFFFF White
            #F9F6F5 Surface
            #F8F8F8 Light Gray
        Text
            #000000 Black
            #2F2F2E Dark Gray
            #5C5B5B Medium Gray
        Accent
            #B02500 Error Red
            Gradients halftone
```

### Componentes Visuales del HTML Original

```mermaid
flowchart TB
    subgraph HomePage["🏠 Home (index.html)"]
        NAV1[TopNavBar - Skewed industrial]
        HERO[Hero Section - Full screen black]
        CATS[Editorial Categories - 6 colored panels]
        NEW[New Drops - Collectible cards 4x]
        BANNER[Cosmic Banner - Silver Surfer]
        BEST[Bestsellers - 5 cards carousel]
        BLOG[The Pulse - Blog section]
        BARGAIN[Bargain Bin - Sale items]
        FOOT1[Footer - Black with yellow accent]
    end

    subgraph CatalogPage["📚 Catalog (catalog.html)"]
        NAV2[TopNavBar]
        SIDE[Sidebar Filters]
        GRID[Comic Grid - 4 columns]
        LOAD[Load More button]
    end

    subgraph CartPage["🛒 Cart (cart.html)"]
        NAV3[TopNavBar]
        ITEMS[Cart Items List]
        FORM[Checkout Form]
        PAY[Payment Instructions Box]
    end

    HomePage --> CatalogPage
    CatalogPage --> CartPage
```

### Estilos Especiales

```mermaid
flowchart LR
    subgraph Styles["CSS Special Effects"]
        HALF[".halftone-bg" - Dot pattern]
        SLANT[".slanted-title" - skewX -6deg]
        COLLECT[".collectible-card" - Hover shadow]
        INDUST[".industrial-nav" - Skewed nav]
        SPEED[".speed-lines" - 45deg stripes]
        GLOW[".glow-yellow" - Box shadow]
        ITALIC[".italic-impact" - Italic tight]
    end
```

---

## Flujos de Usuario

### Flujo de Compra Completo

```mermaid
sequenceDiagram
    actor U as Usuario
    participant H as Home
    participant C as Catálogo
    participant K as Carrito
    participant CH as Checkout
    participant MP as MercadoPago
    participant DB as Database

    U->>H: Visita home
    H->>C: Click "The Archive" / Catalog
    C->>C: Aplica filtros (editorial/categoría/precio)
    U->>C: Busca "manga"
    C-->>U: Muestra resultados filtrados
    
    U->>K: Click "Add to Cart"
    K->>DB: Verifica stock disponible
    DB-->>K: Stock OK
    K-->>U: Actualiza contador carrito

    U->>K: Click icono carrito
    K-->>U: Muestra items en carrito
    U->>K: Ajusta cantidades (+/-)
    
    U->>CH: Click "Checkout"
    CH-->>U: Formulario (nombre, DNI, email, teléfono)
    U->>CH: Completa datos y confirma
    
    CH->>MP: Crear preferencia de pago
    MP-->>CH: URL de pago
    CH->>U: Redirige a MercadoPago
    
    U->>MP: Completa pago
    MP->>CH: Webhook notificación
    CH->>DB: Actualiza estado orden
    CH->>DB: Descuenta stock
    
    MP-->>U: Redirige a success page
    U->>CH: Ve confirmación con datos de transferencia
```

### Flujo de Administración

```mermaid
sequenceDiagram
    actor A as Admin
    participant L as Login
    participant P as Panel
    participant COM as Comics CRUD
    participant ORD as Orders

    A->>L: Ingresa credenciales
    L->>L: Valida is_admin = true
    L-->>A: Redirige a /admin/comics

    A->>COM: Lista comics
    A->>COM: Selecciona múltiples
    A->>COM: Acción masiva "Activar"
    COM->>COM: UPDATE is_active = true
    COM-->>A: Confirma cambios

    A->>ORD: Ver pedidos
    ORD-->>A: Lista con estado/cliente/total
    A->>ORD: Cambia estado a "shipped"
    ORD-->>A: Actualizado
```

### Estados de Orden

```mermaid
stateDiagram-v2
    [*] --> pending: Orden creada

    pending --> paid: Pago confirmado via webhook
    pending --> cancelled: Cancelado por usuario/admin

    paid --> shipped: Admin marca como enviado
    shipped --> delivered: Entrega confirmada

    paid --> cancelled: Reembolso
    
    delivered --> [*]
    cancelled --> [*]

    note right of pending: Stock reservado
    note right of paid: Stock confirmado
    note right of cancelled: Stock restaurado
```

---

## API de Pagos

### Integración MercadoPago

```mermaid
flowchart TB
    subgraph App["Laravel App"]
        CTRL[CheckoutController]
        SVC[MercadoPagoService]
        WHK[WebhookController]
    end

    subgraph MP["MercadoPago"]
        API[API REST]
        CHK[Checkout Pro]
        WH[Webhooks]
    end

    subgraph DB["Database"]
        ORD[(orders)]
        ITEMS[(order_items)]
    end

    CTRL -->|create preference| SVC
    SVC -->|POST /checkout/preferences| API
    API -->|preference_id + init_point| SVC
    SVC -->|redirect URL| CTRL

    WH -->|payment.created| WHK
    WHK -->|update status| ORD

    style MP fill:#009ee3
```

### Endpoints de Pago

| Endpoint | Método | Descripción |
|----------|--------|-------------|
| `/checkout` | GET | Formulario de checkout |
| `/checkout` | POST | Crear orden + preferencia MP |
| `/checkout/success` | GET | Página de éxito |
| `/checkout/failure` | GET | Página de error |
| `/checkout/pending` | GET | Pago pendiente |
| `/webhooks/mercadopago` | POST | Recibe notificaciones |

---

## Multilenguaje

### Idiomas Soportados

```mermaid
mindmap
    root((🌐 i18n))
        🇪🇸 Español
            Default
            lang/es/messages.php
        🇺🇸 English
            lang/en/messages.php
        🇰🇷 한국어
            lang/ko/messages.php
```

### Implementación

```mermaid
sequenceDiagram
    participant U as Usuario
    participant NAV as Navbar
    participant LC as LanguageController
    participant SESS as Session
    participant MW as SetLocale Middleware
    participant APP as Application

    U->>NAV: Click bandera 🇺🇸
    NAV->>LC: GET /lang/en
    LC->>LC: Valida locale en [es, en, ko]
    LC->>SESS: session(['locale' => 'en'])
    LC-->>U: Redirect back

    Note over MW,APP: En cada request...
    MW->>SESS: session('locale', 'es')
    MW->>APP: App::setLocale('en')
    APP-->>U: Vista traducida
```

---

## Rutas

### Mapa de Rutas

```mermaid
flowchart TD
    subgraph Public["🌐 Rutas Públicas"]
        R1["GET / → home"]
        R2["GET /catalog → catalog.index"]
        R3["GET /catalog/ajax → catalog.ajax"]
        R4["GET /cart → cart.index"]
        R5["POST /cart/add/{id} → cart.add"]
        R6["POST /cart/update/{id} → cart.update"]
        R7["DELETE /cart/remove/{id} → cart.remove"]
        R8["GET /checkout → checkout.show"]
        R9["POST /checkout → checkout.store"]
        R10["GET /checkout/success → checkout.success"]
        R11["GET /lang/{locale} → language.switch"]
    end

    subgraph Auth["🔑 Autenticación"]
        A1["GET /login"]
        A2["POST /login"]
        A3["POST /logout"]
        A4["GET /register"]
        A5["POST /register"]
    end

    subgraph Admin["🔐 Admin (middleware: auth, admin)"]
        AD1["GET /admin/comics → admin.comics.index"]
        AD2["GET /admin/comics/create → admin.comics.create"]
        AD3["POST /admin/comics → admin.comics.store"]
        AD4["GET /admin/comics/{id}/edit → admin.comics.edit"]
        AD5["PUT /admin/comics/{id} → admin.comics.update"]
        AD6["DELETE /admin/comics/{id} → admin.comics.destroy"]
        AD7["POST /admin/comics/bulk → admin.comics.bulk"]
        AD8["GET /admin/orders → admin.orders.index"]
        AD9["PUT /admin/orders/{id} → admin.orders.update"]
    end

    subgraph Webhooks["🔔 Webhooks"]
        W1["POST /webhooks/mercadopago"]
    end
```

### Tabla de Rutas

| Método | URI | Acción | Middleware |
|--------|-----|--------|------------|
| GET | `/` | HomeController@index | web |
| GET | `/catalog` | CatalogController@index | web |
| GET | `/catalog/ajax` | CatalogController@ajax | web |
| GET | `/cart` | CartController@index | web |
| POST | `/cart/add/{comic}` | CartController@add | web |
| POST | `/cart/update/{comic}` | CartController@update | web |
| DELETE | `/cart/remove/{comic}` | CartController@remove | web |
| GET | `/checkout` | CheckoutController@show | web |
| POST | `/checkout` | CheckoutController@store | web |
| GET | `/checkout/success` | CheckoutController@success | web |
| GET | `/lang/{locale}` | LanguageController@switch | web |
| GET | `/login` | Auth | web, guest |
| POST | `/login` | Auth | web, guest |
| POST | `/logout` | Auth | web, auth |
| GET | `/admin/comics` | Admin\ComicController@index | web, auth, admin |
| POST | `/admin/comics/bulk` | Admin\ComicController@bulk | web, auth, admin |
| GET | `/admin/orders` | Admin\OrderController@index | web, auth, admin |
| POST | `/webhooks/mercadopago` | WebhookController@mercadopago | - |

---

## Instalación

### Requisitos

- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js (opcional, para assets)

### Pasos

```bash
# Clonar o navegar al proyecto
cd el-tunel-del-comic-laravel

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# DB_DATABASE=el_tunel_del_comic
# DB_USERNAME=root
# DB_PASSWORD=Nienpedo01

# Ejecutar migraciones y seeders
php artisan migrate
php artisan db:seed

# Crear link simbólico para storage
php artisan storage:link

# Iniciar servidor
php artisan serve --port=8080
```

### Variables de Entorno para MercadoPago

```env
MERCADOPAGO_PUBLIC_KEY=APP_USR-xxxxxxxx
MERCADOPAGO_ACCESS_TOKEN=APP_USR-xxxxxxxx
MERCADOPAGO_WEBHOOK_SECRET=xxxxxxxx
```

---

## Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Con coverage
php artisan test --coverage

# Tests específicos
php artisan test --filter=ComicTest
php artisan test --filter=OrderTest
```

### Estructura de Tests

```
tests/
├── Feature/
│   ├── CatalogTest.php
│   ├── CartTest.php
│   ├── CheckoutTest.php
│   └── AdminTest.php
└── Unit/
    ├── ComicTest.php
    ├── OrderTest.php
    └── MercadoPagoServiceTest.php
```

---

## Datos de Pago (Transferencia Bancaria)

| Campo | Valor |
|-------|-------|
| Banco | Banco Galicia |
| CBU | 0000003100095847362514 |
| Alias | TUNEL.COMIC.PAY |
| Titular | EL TUNEL S.R.L. |
| Email | pagos@eltuneldelcomic.com |

---

## Soporte

📧 **Email:** soporte@eltuneldelcomic.com  
📞 **WhatsApp:** +54 9 11 0000-0000

---

*Documentación generada para El Túnel del Cómic - Laravel Edition*
