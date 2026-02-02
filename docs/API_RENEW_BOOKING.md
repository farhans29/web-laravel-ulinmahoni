# Renew Booking API Documentation

## Overview

The Renew Booking API allows users to create a new booking based on a previously completed booking. This endpoint copies all relevant details from the original booking (guest information, property, room) and creates a new reservation with new dates.

**Version:** 1.0
**Base URL:** `/api/v1`
**Endpoint:** `POST /api/v1/booking/{order_id}/renew`

---

## Authentication

This endpoint requires API key authentication via the `X-API-KEY` header.

```http
X-API-KEY: your-api-key-here
```

---

## Endpoint Details

### HTTP Method
`POST`

### URL Structure
```
/api/v1/booking/{order_id}/renew
```

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `order_id` | string | Yes | The order ID of the original booking to renew (e.g., `UMH-260201001PP`) |

---

## Request

### Headers

| Header | Value | Required | Description |
|--------|-------|----------|-------------|
| `Content-Type` | `application/json` | Yes | Indicates JSON request body |
| `Accept` | `application/json` | Yes | Expects JSON response |
| `X-API-KEY` | `string` | Yes | API authentication key |

### Request Body

```json
{
  "check_in": "2026-03-01",
  "check_out": "2026-03-05",
  "voucher_code": "PROMO2026"
}
```

### Body Parameters

| Parameter | Type | Required | Validation | Description |
|-----------|------|----------|------------|-------------|
| `check_in` | string (date) | Yes | `YYYY-MM-DD` format, must be today or later | New check-in date for the renewed booking |
| `check_out` | string (date) | Yes | `YYYY-MM-DD` format, must be after `check_in` | New check-out date for the renewed booking |
| `voucher_code` | string | No | 8-20 characters | Optional voucher code for discount |

---

## Response

### Success Response (201 Created)

```json
{
  "status": "success",
  "message": "Booking renewed successfully",
  "data": {
    "original_order_id": "UMH-260201001PP",
    "new_order_id": "UMH-260301002PP",
    "transaction": {
      "idrec": 123,
      "order_id": "UMH-260301002PP",
      "user_id": 1,
      "user_name": "John Doe",
      "user_email": "john@example.com",
      "user_phone_number": "+6281234567890",
      "property_id": 1,
      "property_name": "Sample Property",
      "property_type": "Hotel",
      "room_id": 5,
      "room_name": "Deluxe Room",
      "booking_type": "daily",
      "booking_days": 4,
      "booking_months": null,
      "check_in": "2026-03-01T14:00:00.000000Z",
      "check_out": "2026-03-05T12:00:00.000000Z",
      "room_price": 2000000,
      "daily_price": 500000,
      "monthly_price": null,
      "admin_fees": 0,
      "service_fees": 30000,
      "tax": 0,
      "subtotal_before_discount": 2000000,
      "discount_amount": 100000,
      "grandtotal_price": 1930000,
      "voucher_id": 10,
      "voucher_code": "PROMO2026",
      "transaction_code": "TRX-abc123def456",
      "transaction_status": "pending",
      "transaction_date": "2026-02-02T10:30:00.000000Z",
      "expired_at": "2026-02-02T11:30:00.000000Z",
      "status": "1"
    },
    "expired_at": "2026-02-02T11:30:00.000000Z"
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `status` | string | Response status (`success` or `error`) |
| `message` | string | Human-readable message |
| `data.original_order_id` | string | Order ID of the original booking |
| `data.new_order_id` | string | Order ID of the newly created booking |
| `data.transaction` | object | Complete transaction details for the new booking |
| `data.expired_at` | string (ISO 8601) | Expiration time for the new booking (1 hour from creation) |

---

## Error Responses

### 400 Bad Request - Invalid Status

**Scenario:** Original booking is not paid or completed

```json
{
  "status": "error",
  "message": "Only paid or completed bookings can be renewed",
  "current_status": "pending"
}
```

### 400 Bad Request - Room Not Exists

**Scenario:** Room from original booking no longer exists

```json
{
  "status": "error",
  "message": "Room from original booking no longer exists"
}
```

### 400 Bad Request - Booking Type Unavailable

**Scenario:** Selected booking type is not available for the room

```json
{
  "status": "error",
  "message": "Daily booking is not available for this room"
}
```

or

```json
{
  "status": "error",
  "message": "Monthly booking is not available for this room"
}
```

### 401 Unauthorized

**Scenario:** Invalid or missing API key

```json
{
  "status": "error",
  "message": "Invalid or missing API KEY",
  "documentation": "Please contact our staff for assistance"
}
```

### 404 Not Found

**Scenario:** Original booking not found

```json
{
  "status": "error",
  "message": "Original booking not found"
}
```

### 409 Conflict

**Scenario:** Room is not available for the selected dates

```json
{
  "status": "error",
  "message": "Room is not available for the selected dates",
  "conflicting_bookings": [...]
}
```

### 422 Unprocessable Entity - Validation Failed

**Scenario:** Invalid request data

```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "check_in": [
      "The check in field is required.",
      "The check in must be a date after or equal to today."
    ],
    "check_out": [
      "The check out field is required.",
      "The check out must be a date after check in."
    ]
  }
}
```

### 422 Unprocessable Entity - Invalid Voucher

**Scenario:** Voucher validation failed

```json
{
  "status": "error",
  "message": "Voucher validation failed",
  "errors": [
    "Voucher is expired",
    "Voucher has reached maximum usage limit"
  ]
}
```

### 500 Internal Server Error

**Scenario:** Server-side error

```json
{
  "status": "error",
  "message": "Error renewing booking",
  "error": "Internal server error"
}
```

---

## Business Logic

### Booking Eligibility

Only bookings with the following statuses can be renewed:
- `paid`
- `completed`

Bookings with status `pending`, `cancelled`, or `expired` cannot be renewed.

### Data Inheritance

The following data is copied from the original booking:
- User information (ID, name, email, phone number)
- Property information (ID, name, type)
- Room information (ID, name)
- Booking type (daily or monthly)

### New Data Generated

The following data is newly calculated or generated:
- New `order_id` (format: `UMH-YYMMDDXXXPP`)
- New `transaction_code` (format: `TRX-` + 16 random characters)
- New check-in and check-out dates (from request)
- New pricing based on current room rates
- New voucher application (if provided)
- New `expired_at` timestamp (1 hour from creation)
- Transaction status set to `pending`

### Pricing Calculation

#### Daily Bookings
```
booking_days = check_out_date - check_in_date
room_price = daily_price × booking_days
subtotal = room_price + admin_fees + tax
grandtotal = subtotal - discount + service_fees
```

#### Monthly Bookings
```
room_price = monthly_price × booking_months
subtotal = room_price + admin_fees + tax
grandtotal = subtotal - discount + service_fees
```

**Default Fees:**
- Admin fees: 0
- Service fees: 30,000
- Tax: 0

### Room Availability Check

The system checks for conflicting bookings:
- Same property and room
- Active bookings (not cancelled or expired)
- Overlapping date ranges

### Booking Type Validation

The system validates that the booking type is available:
- **Daily bookings**: Require `price_original_daily > 0`
- **Monthly bookings**: Require `price_original_monthly > 0`

### Check-in/Check-out Times

- **Check-in time:** Automatically set to 14:00:00 (2:00 PM)
- **Check-out time:** Automatically set to 12:00:00 (12:00 PM)

### Voucher Processing

If a voucher code is provided:
1. Validates voucher (active, not expired, usage limits)
2. Calculates discount amount
3. Applies discount to subtotal
4. Increments voucher usage count
5. Logs voucher usage for audit

### Database Records Created

The renewal process creates three new records:

1. **Transaction** (`t_transactions`)
   - New booking transaction with pending status

2. **Booking** (`t_booking`)
   - New booking record linked to property and room

3. **Payment** (`t_payment`)
   - New payment record with unpaid status

4. **Voucher Usage** (`t_voucher_usage`) - if applicable
   - Usage log for the applied voucher

### Email Notification

After successful renewal, a booking confirmation email is sent to the user's email address.

---

## Example Usage

### Example 1: Daily Booking Renewal

**Request:**
```bash
curl -X POST https://api.example.com/api/v1/booking/UMH-260201001PP/renew \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-API-KEY: your-api-key" \
  -d '{
    "check_in": "2026-03-15",
    "check_out": "2026-03-18"
  }'
```

**Response:**
```json
{
  "status": "success",
  "message": "Booking renewed successfully",
  "data": {
    "original_order_id": "UMH-260201001PP",
    "new_order_id": "UMH-260315005PP",
    "transaction": { ... },
    "expired_at": "2026-02-02T11:30:00.000000Z"
  }
}
```

### Example 2: Monthly Booking Renewal with Voucher

**Request:**
```bash
curl -X POST https://api.example.com/api/v1/booking/UMH-260201002PP/renew \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-API-KEY: your-api-key" \
  -d '{
    "check_in": "2026-04-01",
    "check_out": "2026-07-01",
    "voucher_code": "LONGSTAY20"
  }'
```

**Response:**
```json
{
  "status": "success",
  "message": "Booking renewed successfully",
  "data": {
    "original_order_id": "UMH-260201002PP",
    "new_order_id": "UMH-260401008PP",
    "transaction": {
      "booking_type": "monthly",
      "booking_months": 3,
      "discount_amount": 500000,
      "voucher_code": "LONGSTAY20",
      ...
    },
    "expired_at": "2026-02-02T11:30:00.000000Z"
  }
}
```

### Example 3: Error - Room Unavailable

**Request:**
```bash
curl -X POST https://api.example.com/api/v1/booking/UMH-260201001PP/renew \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-API-KEY: your-api-key" \
  -d '{
    "check_in": "2026-03-20",
    "check_out": "2026-03-25"
  }'
```

**Response:**
```json
{
  "status": "error",
  "message": "Room is not available for the selected dates",
  "conflicting_bookings": [
    {
      "order_id": "UMH-260320010PP",
      "check_in": "2026-03-19",
      "check_out": "2026-03-24"
    }
  ]
}
```

---

## Frontend Integration

### JavaScript Fetch Example

```javascript
async function renewBooking(orderId, checkIn, checkOut, voucherCode = null) {
  try {
    const payload = {
      check_in: checkIn,
      check_out: checkOut
    };

    if (voucherCode) {
      payload.voucher_code = voucherCode;
    }

    const response = await fetch(`/api/v1/booking/${orderId}/renew`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-API-KEY': 'your-api-key'
      },
      body: JSON.stringify(payload)
    });

    const data = await response.json();

    if (response.ok && data.status === 'success') {
      console.log('Booking renewed:', data.data.new_order_id);
      // Redirect to payment page
      window.location.href = `/payment/${data.data.new_order_id}`;
    } else {
      console.error('Renewal failed:', data.message);
      // Handle error
    }
  } catch (error) {
    console.error('Network error:', error);
  }
}

// Usage
renewBooking('UMH-260201001PP', '2026-03-15', '2026-03-18');
```

---

## Security Considerations

### API Key Protection
- Store API keys securely in environment variables
- Never commit API keys to version control
- Rotate API keys regularly

### Input Validation
- All dates are validated server-side
- Check-in must be today or later
- Check-out must be after check-in
- Voucher codes are validated against database

### Authorization
- Only the original booking owner should be able to renew (implement user authentication if needed)
- API key restricts access to authorized clients

### Rate Limiting
Consider implementing rate limiting to prevent abuse:
- Maximum 10 renewal requests per minute per API key
- Maximum 100 renewal requests per hour per user

---

## Testing

### Test Scenarios

1. **Happy Path - Daily Booking**
   - Create a paid booking
   - Renew with valid future dates
   - Verify new booking created
   - Verify payment redirect

2. **Happy Path - Monthly Booking with Voucher**
   - Create a paid monthly booking
   - Renew with valid voucher
   - Verify discount applied
   - Verify voucher usage logged

3. **Error - Pending Booking**
   - Attempt to renew pending booking
   - Verify 400 error response

4. **Error - Room Unavailable**
   - Renew with dates that conflict with existing booking
   - Verify 409 error response

5. **Error - Invalid Dates**
   - Renew with check-in before today
   - Renew with check-out before check-in
   - Verify 422 validation errors

6. **Error - Invalid Voucher**
   - Renew with expired voucher
   - Renew with invalid voucher code
   - Verify 422 error response

7. **Security - Invalid API Key**
   - Send request without API key
   - Send request with invalid API key
   - Verify 401 error response

8. **Booking Type Validation**
   - Attempt to renew daily booking for room without daily pricing
   - Attempt to renew monthly booking for room without monthly pricing
   - Verify 400 error response

---

## Changelog

### Version 1.0 (2026-02-02)
- Initial release
- Support for daily and monthly booking renewals
- Voucher support
- Room availability checking
- Booking type validation
- Email notifications

---

## Support

For API support or questions, please contact:
- **Email:** support@ulinmahoni.com
- **Documentation:** https://docs.ulinmahoni.com
- **GitHub Issues:** https://github.com/ulinmahoni/api/issues

---

## Related Endpoints

- `POST /api/v1/booking` - Create new booking
- `GET /api/v1/booking/{id}` - Get booking details
- `GET /api/v1/booking/order/{order_id}` - Get booking by order ID
- `POST /api/v1/booking/check-availability` - Check room availability
- `GET /api/v1/rooms/{id}` - Get room details and pricing

---

**Last Updated:** February 2, 2026
**API Version:** 1.0
