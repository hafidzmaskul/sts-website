<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $keys = [
            'shipping_method_1_name',
            'shipping_method_1_desc',
            'shipping_method_1_price',
            'shipping_method_2_name',
            'shipping_method_2_desc',
            'shipping_method_2_price',
            'shipping_method_3_name',
            'shipping_method_3_desc',
            'shipping_method_3_price',
            'payment_method_1_name',
            'payment_method_1_desc',
            'payment_method_1_icon',
            'payment_method_2_name',
            'payment_method_2_desc',
            'payment_method_2_icon',
            'payment_method_3_name',
            'payment_method_3_desc',
            'payment_method_3_icon',
            'transaction_tax'
        ];

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $shippingMethods = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($settings["shipping_method_{$i}_name"])) {
                $shippingMethods[] = [
                    'id' => $i,
                    'name' => $settings["shipping_method_{$i}_name"] ?? '',
                    'description' => $settings["shipping_method_{$i}_desc"] ?? '',
                    'price' => (float) ($settings["shipping_method_{$i}_price"] ?? 0),
                ];
            }
        }

        $paymentMethods = [];
        for ($i = 1; $i <= 3; $i++) {
            if (!empty($settings["payment_method_{$i}_name"])) {
                $iconPath = $settings["payment_method_{$i}_icon"] ?? null;
                $paymentMethods[] = [
                    'id' => $i,
                    'name' => $settings["payment_method_{$i}_name"] ?? '',
                    'description' => $settings["payment_method_{$i}_desc"] ?? '',
                    'icon_url' => $iconPath ? asset(Storage::url($iconPath)) : null,
                ];
            }
        }

        return response()->json([
            'shipping_methods' => $shippingMethods,
            'payment_methods' => $paymentMethods,
            'tax' => [
                'percentage' => (float) ($settings['transaction_tax'] ?? 20),
            ],
        ]);
    }
}
