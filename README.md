# IconicFootball-API ⚽

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PostgreSQL-Neon-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Redis-Cache-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/License-Open_Source-green?style=for-the-badge" alt="Open Source">
</p>

<p align="center">
  <a href="README-ES.md">🇪🇸 Documentación en Español</a>
</p>

---

## 📋 Table of Contents

- [About](#-about)
- [Tech Stack](#-tech-stack)
- [Rate Limiting](#-rate-limiting)
- [API Endpoints](#-api-endpoints)
    - [Get All Players](#get-all-players)
    - [Get Player by ID](#get-player-by-id)
    - [Include Relations](#include-relations)
    - [Pagination](#pagination)
- [Response Structure](#-response-structure)
- [Player Attributes](#-player-attributes)
- [Cache System](#-cache-system)
- [Database Relations](#-database-relations)

---

## 🎯 About

**IconicFootball-API** is a RESTful API built with Laravel 12 that provides detailed information about iconic football players, including their stats, clubs, and national teams. The API features intelligent caching, rate limiting by user roles, and optimized queries for high performance.

---

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **Database**: PostgreSQL (Neon)
- **Cache**: Redis
- **Image Storage**: Cloudinary
- **API Type**: RESTful

---

## ⚡ Rate Limiting

The API implements rate limiting based on user categories to ensure fair usage and optimal performance:

| Category                  | Requests per Minute | Identifier   |
| ------------------------- | ------------------- | ------------ |
| 🌍 **Public**             | 200                 | IP Address   |
| 👤 **Authenticated User** | 250                 | User ID / IP |
| 👑 **Admin**              | 500                 | User ID / IP |

> **Note**: When rate limit is exceeded, you'll receive a `429 Too Many Requests` response.

---

## 📡 API Endpoints

### Base URL

```
http://your-domain.com/api
```

---

### Get All Players

Retrieve a paginated list of all players in the database.

**Endpoint**

```http
GET /players
```

**Default Response** (20 players per page)

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

### Get Player by ID

Retrieve detailed information about a specific player.

**Endpoint**

```http
GET /players/{id}
```

**Example**

```http
GET /players/1
```

**Response**

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

### Include Relations

You can include related data (club and/or country) in your requests using the `include` parameter.

#### Include Club and Country

**Endpoint**

```http
GET /players?include=club,country
```

**Response**

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

#### Include Only Club

**Endpoint**

```http
GET /players?include=club
```

**Response**

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

#### Include Only Country

**Endpoint**

```http
GET /players?include=country
```

#### Single Player with Relations

**Endpoint**

```http
GET /players/{id}?include=club,country
```

**Example**

```http
GET /players/1?include=club,country
```

**Response**

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

### Pagination

Control the number of results per page and navigate through pages.

#### Custom Items per Page

**Endpoint**

```http
GET /players?per_page={number}
```

**Example** (Get 11 players)

```http
GET /players?per_page=11
```

> **Note**: Default pagination is 20 items per page. Maximum recommended: 20.

#### Navigate Pages

**Endpoint**

```http
GET /players?page={number}
```

**Example**

```http
GET /players?page=2
```

#### Combined Parameters

You can combine multiple parameters for precise queries:

**Example** (11 players with club and country data)

```http
GET /players?include=club,country&per_page=11
```

**Example** (Page 2 with club data)

```http
GET /players?page=2&include=club
```

---

## 📊 Response Structure

### Successful List Response

```json
{
    "data": [
        /* Array of players */
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 100,
        "last_page": 5
    }
}
```

### Successful Single Player Response

```json
{
    "player": {
        /* Player object */
    },
    "status": 200
}
```

### Not Found Response

```json
{
    "message": "Player not found",
    "status": 404
}
```

### Empty Result

```json
{
    "message": "Players not found",
    "status": 200
}
```

---

## 👤 Player Attributes

| Attribute        | Type         | Description                   |
| ---------------- | ------------ | ----------------------------- |
| `id`             | Integer      | Unique player identifier      |
| `known_as`       | String       | Player's popular name         |
| `full_name`      | String       | Complete legal name           |
| `img`            | String (URL) | Player's image (Cloudinary)   |
| `prime_season`   | String       | Peak performance season       |
| `prime_position` | String       | Primary position during prime |
| `preferred_foot` | String       | Preferred foot (left/right)   |
| `spd`            | Integer      | Speed stat (0-99)             |
| `sho`            | Integer      | Shooting stat (0-99)          |
| `pas`            | Integer      | Passing stat (0-99)           |
| `dri`            | Integer      | Dribbling stat (0-99)         |
| `def`            | Integer      | Defense stat (0-99)           |
| `phy`            | Integer      | Physical stat (0-99)          |
| `prime_rating`   | Integer      | Overall rating (0-99)         |
| `club_id`        | Integer      | Foreign key to club           |
| `country_id`     | Integer      | Foreign key to country        |

---

## 🚀 Cache System

The API implements **Redis caching** for optimal performance:

- **Cache Duration**: 60 seconds
- **Cache Strategy**: Query-based caching
- **Cache Keys**: Generated from request parameters (page, per_page, include)
- **Benefits**: Reduced database load, faster response times

**Cached Endpoints:**

- ✅ `GET /players` (all combinations)
- ✅ `GET /players/{id}` (all combinations)

---

## 🔗 Database Relations

### Player Model Relationships

```
Player
├── belongsTo → Club
└── belongsTo → Country

Club
└── hasMany → Players

Country
└── hasMany → Players
```

**Available Relations:**

- `club`: Club information with logo
- `country`: Country information with flag

---

## 💡 Usage Examples

### Basic Request (JavaScript)

```javascript
fetch("http://your-domain.com/api/players")
    .then((response) => response.json())
    .then((data) => console.log(data));
```

### With Parameters (JavaScript)

```javascript
const url =
    "http://your-domain.com/api/players?include=club,country&per_page=11";
fetch(url)
    .then((response) => response.json())
    .then((data) => console.log(data));
```

### cURL Example

```bash
curl -X GET "http://your-domain.com/api/players?include=club,country&per_page=11"
```

---

## 📝 Notes

- All responses are in JSON format
- All timestamps use UTC timezone
- Images are hosted on Cloudinary CDN
- API responses include proper HTTP status codes
- Pagination metadata is included in list responses

---

## 📄 License

Open Source

---

<p align="center">
  By: Forlán ordoñez
</p>

<p align="center">
  <a href="README-ES.md">🇪🇸 Ver documentación en Español</a>
</p>
