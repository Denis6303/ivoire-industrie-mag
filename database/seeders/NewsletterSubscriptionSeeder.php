<?php

namespace Database\Seeders;

use App\Models\NewsletterSubscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsletterSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::query()->where('email', 'admin@ivoireindustriemag.ci')->value('id');

        $samples = [
            ['email' => 'amina.kone@example.com', 'first_name' => 'Amina', 'status' => 'active'],
            ['email' => 'youssouf.traore@example.com', 'first_name' => 'Youssouf', 'status' => 'active'],
            ['email' => 'marie.kouassi@example.com', 'first_name' => 'Marie', 'status' => 'pending'],
            ['email' => 'serge.yao@example.com', 'first_name' => 'Serge', 'status' => 'active'],
            ['email' => 'nadia.toure@example.com', 'first_name' => 'Nadia', 'status' => 'unsubscribed'],
            ['email' => 'ibrahim.diaby@example.com', 'first_name' => 'Ibrahim', 'status' => 'pending'],
        ];

        foreach ($samples as $s) {
            $token = $s['status'] === 'pending' ? Str::random(32) : null;
            $confirmedAt = $s['status'] === 'active' ? now()->subDays(random_int(1, 25)) : null;

            NewsletterSubscription::updateOrCreate(
                ['email' => $s['email']],
                [
                    'email' => $s['email'],
                    'status' => $s['status'],
                    'token' => $token,
                    'first_name' => $s['first_name'],
                    'user_id' => $userId,
                    'confirmed_at' => $confirmedAt,
                ]
            );
        }
    }
}

