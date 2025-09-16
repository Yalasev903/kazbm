<?php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EpayService
{
    public function getAccessToken()
    {
        $response = Http::asForm()->post(config('services.epay.oauth') . '/oauth2/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.epay.client_id'),
            'client_secret' => config('services.epay.client_secret'),
        ]);

        return $response->json('access_token');
    }

    public function createPayment($amount, $email, $phone)
    {
        $token = $this->getAccessToken();
        if (!$token) return null;

        $order_id = uniqid('order_');

        $payload = [
            'terminal' => config('services.epay.terminal_id'),
            'order_id' => $order_id,
            'amount' => $amount,
            'currency' => 'KZT',
            'description' => 'Оплата услуг',
            'success_url' => route('payment.success'),
            'failure_url' => route('payment.failure'),
            'post_link' => route('epay.callback'),
            'back_link' => url('/'),
            'email' => $email,
            'phone' => $phone,
        ];

        $response = Http::withToken($token)
            ->post(config('services.epay.url') . '/payment', $payload);

        return [
            'url' => $response->json('redirect_url'),
            'order_id' => $order_id,
            'response' => $response->json(),
        ];
    }
}
