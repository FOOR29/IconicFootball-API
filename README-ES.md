<p align="center">
  <img src=".github/assets/logo/icon.png" alt="IconicFootball Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PostgreSQL-Neon-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Redis-Cache-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/Licencia-Open_Source-green?style=for-the-badge" alt="Open Source">
</p>

<p align="center">
  <a href="README.md">🇬🇧 Documentation in English</a>
</p>

---

## 📋 Tabla de Contenidos

- [Acerca de](#-acerca-de)
- [Stack Tecnológico](#️-stack-tecnológico)
- [Límite de Peticiones](#-límite-de-peticiones)
- [Endpoints de la API](#-endpoints-de-la-api)
  - [Obtener Todos los Jugadores](#obtener-todos-los-jugadores)
  - [Obtener Jugador por ID](#obtener-jugador-por-id)
  - [Incluir Relaciones](#incluir-relaciones)
  - [Paginación](#paginación)
- [Estructura de Respuestas](#-estructura-de-respuestas)
- [Atributos del Jugador](#-atributos-del-jugador)
- [Sistema de Caché](#-sistema-de-caché)
- [Relaciones de Base de Datos](#-relaciones-de-base-de-datos)

---

## 🎯 Acerca de

**IconicFootball-API** es una API RESTful construida con Laravel 12 que proporciona información detallada sobre jugadores de fútbol icónicos, incluyendo sus estadísticas, clubes y selecciones nacionales. La API cuenta con caché inteligente, límites de peticiones por roles de usuario y consultas optimizadas para alto rendimiento.

---

## 🛠️ Stack Tecnológico

- **Framework**: Laravel 12
- **Base de Datos**: PostgreSQL (Neon)
- **Caché**: Redis
- **Almacenamiento de Imágenes**: Cloudinary
- **Tipo de API**: RESTful

---

## ⚡ Límite de Peticiones

La API implementa limitación de peticiones basada en categorías de usuarios para garantizar un uso justo y rendimiento óptimo:

| Categoría | Peticiones por Minuto | Identificador |
|-----------|----------------------|---------------|
| 🌍 **Público** | 200 | Dirección IP |
| 👤 **Usuario Autenticado** | 250 | ID de Usuario / IP |
| 👑 **Administrador** | 500 | ID de Usuario / IP |

> **Nota**: Cuando se excede el límite de peticiones, recibirás una respuesta `429 Too Many Requests`.

---

## 📡 Endpoints de la API

### URL Base
```
http://tu-dominio.com/api
```

---

### Obtener Todos los Jugadores

Obtiene una lista paginada de todos los jugadores en la base de datos.

**Endpoint**
```http
GET /players
```

**Respuesta por Defecto** (20 jugadores por página)
```json
{
  "data": [
    {
      "id": 1,
      "known_as": "Kahn",
      "full_name": "Oliver Rolf Kahn",
      "img": "https://res.cloudinary.com/.../oliver_nfalr0.png",
      "prime_season": "2001-2002",
      "prime_position": "GK",
      "preferred_foot": "right",
      "spd": 82,
      "sho": 25,
      "pas": 59,
      "dri": 44,
      "def": 95,
      "phy": 92,
      "prime_rating": 93,
      "club_id": 1,
      "country_id": 1
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1
  }
}
```

---

### Obtener Jugador por ID

Obtiene información detallada sobre un jugador específico.

**Endpoint**
```http
GET /players/{id}
```

**Ejemplo**
```http
GET /players/1
```

**Respuesta**
```json
{
  "player": {
    "id": 1,
    "known_as": "Kahn",
    "full_name": "Oliver Rolf Kahn",
    "img": "https://res.cloudinary.com/.../oliver_nfalr0.png",
    "prime_season": "2001-2002",
    "prime_position": "GK",
    "preferred_foot": "right",
    "spd": 82,
    "sho": 25,
    "pas": 59,
    "dri": 44,
    "def": 95,
    "phy": 92,
    "prime_rating": 93,
    "club_id": 1,
    "country_id": 1
  },
  "status": 200
}
```

---

### Incluir Relaciones

Puedes incluir datos relacionados (club y/o país) en tus peticiones usando el parámetro `include`.

#### Incluir Club y País

**Endpoint**
```http
GET /players?include=club,country
```

**Respuesta**
```json
{
  "data": [
    {
      "id": 1,
      "known_as": "Kahn",
      "full_name": "Oliver Rolf Kahn",
      "img": "https://res.cloudinary.com/.../oliver_nfalr0.png",
      "prime_season": "2001-2002",
      "prime_position": "GK",
      "preferred_foot": "right",
      "spd": 82,
      "sho": 25,
      "pas": 59,
      "dri": 44,
      "def": 95,
      "phy": 92,
      "prime_rating": 93,
      "club_id": 1,
      "country_id": 1,
      "club": {
        "id": 1,
        "name": "FC Bayern Múnich",
        "logo": "https://res.cloudinary.com/.../bayern-munich.png"
      },
      "country": {
        "id": 1,
        "name": "Alemania",
        "logo": "https://res.cloudinary.com/.../de_apncmu.png"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1
  }
}
```

#### Incluir Solo Club

**Endpoint**
```http
GET /players?include=club
```

**Respuesta**
```json
{
  "data": [
    {
      "id": 1,
      "known_as": "Kahn",
      "full_name": "Oliver Rolf Kahn",
      "img": "https://res.cloudinary.com/.../oliver_nfalr0.png",
      "prime_season": "2001-2002",
      "prime_position": "GK",
      "preferred_foot": "right",
      "spd": 82,
      "sho": 25,
      "pas": 59,
      "dri": 44,
      "def": 95,
      "phy": 92,
      "prime_rating": 93,
      "club_id": 1,
      "country_id": 1,
      "club": {
        "id": 1,
        "name": "FC Bayern Múnich",
        "logo": "https://res.cloudinary.com/.../bayern-munich.png"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 1,
    "last_page": 1
  }
}
```

#### Incluir Solo País

**Endpoint**
```http
GET /players?include=country
```

#### Jugador Individual con Relaciones

**Endpoint**
```http
GET /players/{id}?include=club,country
```

**Ejemplo**
```http
GET /players/1?include=club,country
```

**Respuesta**
```json
{
  "player": {
    "id": 1,
    "known_as": "Kahn",
    "full_name": "Oliver Rolf Kahn",
    "img": "https://res.cloudinary.com/.../oliver_nfalr0.png",
    "prime_season": "2001-2002",
    "prime_position": "GK",
    "preferred_foot": "right",
    "spd": 82,
    "sho": 25,
    "pas": 59,
    "dri": 44,
    "def": 95,
    "phy": 92,
    "prime_rating": 93,
    "club_id": 1,
    "country_id": 1,
    "club": {
      "id": 1,
      "name": "FC Bayern Múnich",
      "logo": "https://res.cloudinary.com/.../bayern-munich.png"
    },
    "country": {
      "id": 1,
      "name": "Alemania",
      "logo": "https://res.cloudinary.com/.../de_apncmu.png"
    }
  },
  "status": 200
}
```

---

### Paginación

Controla el número de resultados por página y navega entre páginas.

#### Elementos Personalizados por Página

**Endpoint**
```http
GET /players?per_page={número}
```

**Ejemplo** (Obtener 11 jugadores)
```http
GET /players?per_page=11
```

> **Nota**: La paginación por defecto es de 20 elementos por página. Máximo recomendado: 20.

#### Navegar entre Páginas

**Endpoint**
```http
GET /players?page={número}
```

**Ejemplo**
```http
GET /players?page=2
```

#### Parámetros Combinados

Puedes combinar múltiples parámetros para consultas precisas:

**Ejemplo** (11 jugadores con datos de club y país)
```http
GET /players?include=club,country&per_page=11
```

**Ejemplo** (Página 2 con datos de club)
```http
GET /players?page=2&include=club
```

---

## 📊 Estructura de Respuestas

### Respuesta Exitosa de Lista
```json
{
  "data": [ /* Array de jugadores */ ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

### Respuesta Exitosa de Jugador Individual
```json
{
  "player": { /* Objeto del jugador */ },
  "status": 200
}
```

### Respuesta No Encontrado
```json
{
  "message": "Player not found",
  "status": 404
}
```

### Resultado Vacío
```json
{
  "message": "Players not found",
  "status": 200
}
```

---

## 👤 Atributos del Jugador

| Atributo | Tipo | Descripción |
|----------|------|-------------|
| `id` | Integer | Identificador único del jugador |
| `known_as` | String | Nombre popular del jugador |
| `full_name` | String | Nombre legal completo |
| `img` | String (URL) | Imagen del jugador (Cloudinary) |
| `prime_season` | String | Temporada de máximo rendimiento |
| `prime_position` | String | Posición principal durante su prime |
| `preferred_foot` | String | Pie preferido (left/right) |
| `spd` | Integer | Estadística de velocidad (0-99) |
| `sho` | Integer | Estadística de disparo (0-99) |
| `pas` | Integer | Estadística de pase (0-99) |
| `dri` | Integer | Estadística de regate (0-99) |
| `def` | Integer | Estadística de defensa (0-99) |
| `phy` | Integer | Estadística física (0-99) |
| `prime_rating` | Integer | Valoración general (0-99) |
| `club_id` | Integer | Clave foránea al club |
| `country_id` | Integer | Clave foránea al país |

---

## 🚀 Sistema de Caché

La API implementa **caché con Redis** para un rendimiento óptimo:

- **Duración del Caché**: 60 segundos
- **Estrategia de Caché**: Caché basado en consultas
- **Claves de Caché**: Generadas desde parámetros de petición (page, per_page, include)
- **Beneficios**: Reducción de carga en base de datos, tiempos de respuesta más rápidos

**Endpoints con Caché:**
- ✅ `GET /players` (todas las combinaciones)
- ✅ `GET /players/{id}` (todas las combinaciones)

---

## 🔗 Relaciones de Base de Datos

### Relaciones del Modelo Player

```
Player
├── belongsTo → Club
└── belongsTo → Country

Club
└── hasMany → Players

Country
└── hasMany → Players
```

**Relaciones Disponibles:**
- `club`: Información del club con logo
- `country`: Información del país con bandera

---

## 💡 Ejemplos de Uso

### Petición Básica (JavaScript)
```javascript
fetch('http://tu-dominio.com/api/players')
  .then(response => response.json())
  .then(data => console.log(data));
```

### Con Parámetros (JavaScript)
```javascript
const url = 'http://tu-dominio.com/api/players?include=club,country&per_page=11';
fetch(url)
  .then(response => response.json())
  .then(data => console.log(data));
```

### Ejemplo con cURL
```bash
curl -X GET "http://tu-dominio.com/api/players?include=club,country&per_page=11"
```

### Ejemplo con Python
```python
import requests

url = "http://tu-dominio.com/api/players"
params = {
    "include": "club,country",
    "per_page": 11
}

response = requests.get(url, params=params)
data = response.json()
print(data)
```

### Ejemplo con PHP
```php
<?php
$url = "http://tu-dominio.com/api/players?include=club,country&per_page=11";
$response = file_get_contents($url);
$data = json_decode($response, true);
print_r($data);
?>
```

---

## 📝 Notas

- Todas las respuestas están en formato JSON
- Todos los timestamps usan zona horaria UTC
- Las imágenes están alojadas en Cloudinary CDN
- Las respuestas de la API incluyen códigos de estado HTTP apropiados
- Los metadatos de paginación se incluyen en las respuestas de lista

---

## 🎯 Casos de Uso Comunes

### 🏆 Obtener un Equipo Ideal (11 Jugadores)
```http
GET /players?per_page=11&include=club,country
```
Ideal para mostrar formaciones de equipos con toda la información relevante.

### 📊 Listar Jugadores con Filtros
```http
GET /players?page=1&include=club
```
Perfecto para interfaces de usuario con navegación paginada.

### 👤 Perfil Completo de Jugador
```http
GET /players/1?include=club,country
```
Obtén toda la información de un jugador específico incluyendo club y selección.

### ⚡ Carga Rápida (Sin Relaciones)
```http
GET /players?per_page=20
```
Para cuando solo necesitas datos básicos sin información adicional.

---

## ❓ Preguntas Frecuentes

**¿Cómo puedo obtener más de 20 jugadores por página?**
> La paginación está limitada a 20 jugadores por página para optimizar el rendimiento. Usa el parámetro `page` para navegar entre páginas.

**¿Las imágenes están optimizadas?**
> Sí, todas las imágenes se sirven desde Cloudinary con optimización automática (formato webp, compresión, etc.).

**¿Cuánto tiempo se mantienen los datos en caché?**
> Los datos se almacenan en caché durante 60 segundos. Después de este tiempo, se actualizan automáticamente.

**¿Puedo combinar include=club con per_page?**
> Sí, todos los parámetros son combinables. Ejemplo: `?include=club&per_page=11&page=2`

---

## 📄 Licencia

Open Source

---

<p align="center">
  By: Forlán Ordoñez
</p>

<p align="center">
  <a href="README.md">🇬🇧 Read documentation in English</a>
</p>