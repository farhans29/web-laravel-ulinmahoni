# Promo Banner API Documentation

## Overview

The Promo Banner API allows you to manage promotional banners with multiple images. Each banner can have a title, description, status, and multiple associated images with thumbnails.

## Base URL

```
/api/promo-banners
```

## Authentication

All endpoints require the `X-API-KEY` header for authentication.

```
X-API-KEY: your-api-key
```

---

## Endpoints

### 1. Get All Promo Banners

Retrieve all promo banners with their associated images.

**Endpoint:** `GET /api/promo-banners`

**Query Parameters:**

| Parameter | Type    | Required | Description                          |
|-----------|---------|----------|--------------------------------------|
| `id`      | integer | No       | Get a single banner by ID            |
| `idrec`   | integer | No       | Filter by banner ID                  |
| `status`  | integer | No       | Filter by status (0=inactive, 1=active) |
| `page`    | integer | No       | Page number for pagination           |
| `limit`   | integer | No       | Items per page for pagination        |

**Example Request:**

```bash
curl -X GET "https://api.ulinmahoni.com/api/promo-banners" \
  -H "X-API-KEY: your-api-key"
```

**Example Request with Filters:**

```bash
curl -X GET "https://api.ulinmahoni.com/api/promo-banners?status=1&page=1&limit=10" \
  -H "X-API-KEY: your-api-key"
```

**Example Response:**

```json
{
  "data": [
    {
      "idrec": 1,
      "title": "Summer Sale 2024",
      "descriptions": "Get up to 50% off on selected rooms",
      "status": 1,
      "image_id": 1,
      "created_by": 1,
      "created_at": "2024-01-15T10:00:00.000000Z",
      "updated_at": "2024-01-15T10:00:00.000000Z",
      "thumbnail": "https://api.ulinmahoni.com/storage/promo-banners/thumbnails/abc123.jpg",
      "images": [
        {
          "id": 1,
          "image": "https://api.ulinmahoni.com/storage/promo-banners/abc123.jpg",
          "thumbnail": "https://api.ulinmahoni.com/storage/promo-banners/thumbnails/abc123.jpg",
          "caption": "Main banner image",
          "sort_order": 0
        },
        {
          "id": 2,
          "image": "https://api.ulinmahoni.com/storage/promo-banners/def456.jpg",
          "thumbnail": null,
          "caption": "Secondary image",
          "sort_order": 1
        }
      ]
    }
  ]
}
```

---

### 2. Get Single Promo Banner

Retrieve a specific promo banner by ID.

**Endpoint:** `GET /api/promo-banners?id={id}`

**Example Request:**

```bash
curl -X GET "https://api.ulinmahoni.com/api/promo-banners?id=1" \
  -H "X-API-KEY: your-api-key"
```

**Example Response:**

```json
{
  "data": {
    "idrec": 1,
    "title": "Summer Sale 2024",
    "descriptions": "Get up to 50% off on selected rooms",
    "status": 1,
    "image_id": 1,
    "created_by": 1,
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-15T10:00:00.000000Z",
    "thumbnail": "https://api.ulinmahoni.com/storage/promo-banners/thumbnails/abc123.jpg",
    "images": [
      {
        "id": 1,
        "image": "https://api.ulinmahoni.com/storage/promo-banners/abc123.jpg",
        "thumbnail": "https://api.ulinmahoni.com/storage/promo-banners/thumbnails/abc123.jpg",
        "caption": "Main banner image",
        "sort_order": 0
      }
    ]
  }
}
```

---

### 3. Create Promo Banner

Create a new promo banner with optional images.

**Endpoint:** `POST /api/promo-banners`

**Request Body:**

| Field                    | Type    | Required | Description                              |
|--------------------------|---------|----------|------------------------------------------|
| `title`                  | string  | Yes      | Banner title (max 255 characters)        |
| `descriptions`           | string  | No       | Banner description                       |
| `status`                 | integer | No       | Status (0=inactive, 1=active). Default: 1 |
| `created_by`             | integer | Yes      | User ID who created the banner           |
| `images`                 | array   | No       | Array of images to add                   |
| `images.*.image`         | string  | Yes*     | Base64 encoded image or file path        |
| `images.*.thumbnail`     | string  | No       | Base64 encoded thumbnail or file path    |
| `images.*.caption`       | string  | No       | Image caption (max 255 characters)       |
| `images.*.sort_order`    | integer | No       | Display order (default: array index)     |

*Required if `images` array is provided

**Example Request:**

```bash
curl -X POST "https://api.ulinmahoni.com/api/promo-banners" \
  -H "X-API-KEY: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Year Promotion",
    "descriptions": "Special rates for the new year celebration",
    "status": 1,
    "created_by": 1,
    "images": [
      {
        "image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
        "thumbnail": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
        "caption": "New Year Banner",
        "sort_order": 0
      }
    ]
  }'
```

**Example Response:**

```json
{
  "message": "Promo banner created successfully",
  "created_id": 5,
  "images": [
    {
      "idrec": 10,
      "promo_banner_id": 5,
      "image": "promo-banners/uuid-generated-filename.jpg",
      "thumbnail": "promo-banners/thumbnails/uuid-generated-filename.jpg",
      "caption": "New Year Banner",
      "sort_order": 0
    }
  ]
}
```

---

### 4. Update Promo Banner

Update an existing promo banner.

**Endpoint:** `PUT /api/promo-banners/{id}`

**Request Body:**

| Field         | Type    | Required | Description                              |
|---------------|---------|----------|------------------------------------------|
| `title`       | string  | No       | Banner title (max 255 characters)        |
| `descriptions`| string  | No       | Banner description                       |
| `status`      | integer | No       | Status (0=inactive, 1=active)            |
| `image_id`    | integer | No       | Primary image ID                         |
| `updated_by`  | integer | Yes      | User ID who updated the banner           |

**Example Request:**

```bash
curl -X PUT "https://api.ulinmahoni.com/api/promo-banners/5" \
  -H "X-API-KEY: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated New Year Promotion",
    "status": 1,
    "updated_by": 1
  }'
```

**Example Response:**

```json
{
  "message": "Promo banner updated successfully",
  "data": {
    "idrec": 5,
    "title": "Updated New Year Promotion",
    "descriptions": "Special rates for the new year celebration",
    "status": 1,
    "image_id": 10,
    "created_by": 1,
    "updated_by": 1,
    "created_at": "2024-01-15T10:00:00.000000Z",
    "updated_at": "2024-01-16T14:30:00.000000Z"
  }
}
```

---

### 5. Delete Promo Banner

Delete a promo banner and all associated images.

**Endpoint:** `DELETE /api/promo-banners/{id}`

**Example Request:**

```bash
curl -X DELETE "https://api.ulinmahoni.com/api/promo-banners/5" \
  -H "X-API-KEY: your-api-key"
```

**Example Response:**

```json
{
  "message": "Promo banner deleted successfully"
}
```

---

### 6. Add Images to Promo Banner

Add one or more images to an existing promo banner.

**Endpoint:** `POST /api/promo-banners/{id}/images`

**Request Body:**

| Field                    | Type    | Required | Description                              |
|--------------------------|---------|----------|------------------------------------------|
| `images`                 | array   | Yes      | Array of images to add                   |
| `images.*.image`         | string  | Yes      | Base64 encoded image or file path        |
| `images.*.thumbnail`     | string  | No       | Base64 encoded thumbnail or file path    |
| `images.*.caption`       | string  | No       | Image caption (max 255 characters)       |
| `images.*.sort_order`    | integer | No       | Display order                            |

**Example Request:**

```bash
curl -X POST "https://api.ulinmahoni.com/api/promo-banners/5/images" \
  -H "X-API-KEY: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{
    "images": [
      {
        "image": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUA...",
        "caption": "Additional promo image",
        "sort_order": 1
      },
      {
        "image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...",
        "caption": "Another promo image",
        "sort_order": 2
      }
    ]
  }'
```

**Example Response:**

```json
{
  "message": "Images added successfully",
  "data": [
    {
      "idrec": 11,
      "promo_banner_id": 5,
      "image": "promo-banners/uuid-filename-1.png",
      "thumbnail": null,
      "caption": "Additional promo image",
      "sort_order": 1
    },
    {
      "idrec": 12,
      "promo_banner_id": 5,
      "image": "promo-banners/uuid-filename-2.jpg",
      "thumbnail": null,
      "caption": "Another promo image",
      "sort_order": 2
    }
  ]
}
```

---

### 7. Remove Image from Promo Banner

Remove a specific image from a promo banner.

**Endpoint:** `DELETE /api/promo-banners/{id}/images/{imageId}`

**Example Request:**

```bash
curl -X DELETE "https://api.ulinmahoni.com/api/promo-banners/5/images/11" \
  -H "X-API-KEY: your-api-key"
```

**Example Response:**

```json
{
  "message": "Image removed successfully"
}
```

---

## Image Handling

### Base64 Image Format

When uploading images via base64, use the following format:

```
data:image/{format};base64,{base64-encoded-data}
```

Supported formats:
- `image/jpeg`
- `image/png`
- `image/gif`
- `image/webp`

### Image Storage

- Images are stored in `storage/app/public/promo-banners/`
- Thumbnails are stored in `storage/app/public/promo-banners/thumbnails/`
- Files are named using UUID to prevent conflicts

### Primary Image

- The first image added to a banner automatically becomes the primary image (`image_id`)
- You can change the primary image by updating the banner with a different `image_id`
- If the primary image is deleted, the `image_id` is set to null

---

## Error Responses

### 400 Bad Request

```json
{
  "message": "Validation error message"
}
```

### 401 Unauthorized

```json
{
  "status": "error",
  "message": "Invalid or missing API KEY",
  "documentation": "Please contact our staff for assistance"
}
```

### 404 Not Found

```json
{
  "message": "Promo banner not found"
}
```

### 500 Internal Server Error

```json
{
  "message": "Error description"
}
```

---

## Database Schema

### m_promo_banners

| Column       | Type         | Description                    |
|--------------|--------------|--------------------------------|
| idrec        | bigint (PK)  | Primary key                    |
| title        | varchar(255) | Banner title                   |
| descriptions | text         | Banner description (nullable)  |
| status       | tinyint      | 0=inactive, 1=active           |
| image_id     | bigint (FK)  | Primary image reference        |
| created_by   | bigint       | User ID who created            |
| updated_by   | bigint       | User ID who last updated       |
| created_at   | timestamp    | Creation timestamp             |
| updated_at   | timestamp    | Last update timestamp          |

### m_promo_banner_images

| Column          | Type         | Description                    |
|-----------------|--------------|--------------------------------|
| idrec           | bigint (PK)  | Primary key                    |
| promo_banner_id | bigint (FK)  | Reference to promo banner      |
| image           | varchar(255) | Image file path                |
| thumbnail       | varchar(255) | Thumbnail file path (nullable) |
| caption         | varchar(255) | Image caption (nullable)       |
| sort_order      | integer      | Display order                  |
| created_at      | timestamp    | Creation timestamp             |
| updated_at      | timestamp    | Last update timestamp          |

---

## Usage Examples

### Frontend Integration (JavaScript)

```javascript
// Fetch all active banners
async function getActiveBanners() {
  const response = await fetch('/api/promo-banners?status=1', {
    headers: {
      'X-API-KEY': 'your-api-key'
    }
  });
  return response.json();
}

// Create banner with image upload
async function createBanner(title, description, imageFile) {
  const base64Image = await fileToBase64(imageFile);

  const response = await fetch('/api/promo-banners', {
    method: 'POST',
    headers: {
      'X-API-KEY': 'your-api-key',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      title: title,
      descriptions: description,
      status: 1,
      created_by: 1,
      images: [{
        image: base64Image,
        caption: 'Main image',
        sort_order: 0
      }]
    })
  });
  return response.json();
}

// Helper function to convert file to base64
function fileToBase64(file) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = error => reject(error);
  });
}
```

### PHP/Laravel Integration

```php
use Illuminate\Support\Facades\Http;

// Fetch banners
$response = Http::withHeaders([
    'X-API-KEY' => config('services.ulinmahoni.api_key')
])->get('https://api.ulinmahoni.com/api/promo-banners', [
    'status' => 1
]);

$banners = $response->json()['data'];

// Create banner
$response = Http::withHeaders([
    'X-API-KEY' => config('services.ulinmahoni.api_key')
])->post('https://api.ulinmahoni.com/api/promo-banners', [
    'title' => 'New Promotion',
    'descriptions' => 'Special offer',
    'status' => 1,
    'created_by' => auth()->id()
]);
```
