# External Integrations Setup Guide

This document describes how to set up the external services that DeliverEats integrates with.
Currently, all integrations use **placeholder implementations** that simulate success.

---

## 1. Google Maps Distance Matrix API

**Purpose:** Calculate real driving distances and ETAs between restaurants, riders, and customers.

**Current Implementation:** `App\Services\External\HaversineDistanceCalculator` — uses straight-line Haversine formula × 1.3 road factor.

**Contract:** `App\Contracts\DistanceCalculatorInterface`

### Setup Steps

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable **Distance Matrix API** and **Maps JavaScript API**
4. Create an API key under **Credentials**
5. Restrict the key to your server IP and allowed domains

### Environment Variables

```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

### Implementation

Create `App\Services\External\GoogleMapsDistanceCalculator` implementing `DistanceCalculatorInterface`.

```php
// Example API call:
// GET https://maps.googleapis.com/maps/api/distancematrix/json
//   ?origins={originLat},{originLng}
//   &destinations={destLat},{destLng}
//   &key={GOOGLE_MAPS_API_KEY}
//   &mode=driving
```

### Swap Binding

In `AppServiceProvider::register()`:
```php
$this->app->bind(DistanceCalculatorInterface::class, GoogleMapsDistanceCalculator::class);
```

### Testing

- Use the API Explorer to verify key works
- Test with known Cairo coordinates (e.g., Downtown → Maadi)
- Expected driving distance: ~12km, ~25 minutes

---

## 2. Stripe Connect

**Purpose:** Process card payments, handle platform fees, and transfer earnings to restaurant/rider connected accounts.

**Current Implementation:** `App\Services\External\PlaceholderPaymentGateway` — returns fake payment intent IDs.

**Contract:** `App\Contracts\PaymentGatewayInterface`

### Setup Steps

1. Create a [Stripe account](https://dashboard.stripe.com/register)
2. Enable **Stripe Connect** in dashboard
3. Set platform type to **Express** or **Standard**
4. Configure webhook endpoints

### Environment Variables

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
STRIPE_CURRENCY=EGP
```

### Webhook Setup

Register webhook at `https://yourapp.com/api/webhooks/stripe` for events:
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `transfer.created`
- `account.updated`

### Onboarding Flow

Each restaurant/rider needs a Stripe Connected Account:
```php
$account = \Stripe\Account::create([
    'type' => 'express',
    'country' => 'EG',
    'email' => $user->email,
]);
```

### Testing

- Use Stripe test cards: `4242 4242 4242 4242`
- Test webhook with Stripe CLI: `stripe listen --forward-to localhost:8000/api/webhooks/stripe`
- Verify payment splits in Stripe Dashboard

---

## 3. Paymob

**Purpose:** Alternative payment gateway for the Egyptian market (local cards, mobile wallets).

**Current Implementation:** Same placeholder as Stripe.

### Setup Steps

1. Register at [Paymob](https://accept.paymob.com/)
2. Get approved for payment acceptance
3. Create integration IDs for card and wallet payments
4. Configure HMAC for webhook verification

### Environment Variables

```env
PAYMOB_API_KEY=your_api_key
PAYMOB_INTEGRATION_ID=your_integration_id
PAYMOB_IFRAME_ID=your_iframe_id
PAYMOB_HMAC_SECRET=your_hmac_secret
PAYMOB_WALLET_INTEGRATION_ID=your_wallet_integration_id
```

### Payment Flow

1. **Auth Request** → Get auth token
2. **Order Registration** → Register order with Paymob
3. **Payment Key** → Generate payment key
4. **iFrame / Wallet** → Redirect user to pay
5. **Webhook** → Receive payment confirmation via HMAC-verified callback

### Webhook Setup

Register callback URL: `https://yourapp.com/api/webhooks/paymob`

Verify HMAC:
```php
$hmac = hash_hmac('sha512', $concatenated_string, env('PAYMOB_HMAC_SECRET'));
```

### Testing

- Use Paymob sandbox environment
- Test card: `5123456789012346` (Mastercard test)
- Verify transaction in Paymob dashboard

---

## Switching Implementations

All external services use Laravel's service container. To switch from placeholder to real:

1. Create your implementation class implementing the contract interface
2. Update the binding in `App\Providers\AppServiceProvider::register()`
3. Add required environment variables to `.env`
4. Run tests to verify

```php
// AppServiceProvider.php
public function register(): void
{
    // Switch to real implementations:
    $this->app->bind(DistanceCalculatorInterface::class, GoogleMapsDistanceCalculator::class);
    $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);
    // or for Paymob:
    // $this->app->bind(PaymentGatewayInterface::class, PaymobPaymentGateway::class);
}
```
