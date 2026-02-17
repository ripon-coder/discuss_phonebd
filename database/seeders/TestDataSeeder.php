<?php

namespace Database\Seeders;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@phonebd.net',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_trusted' => true,
        ]);

        // Create moderator user
        $moderator = User::create([
            'name' => 'Moderator',
            'email' => 'moderator@phonebd.net',
            'password' => bcrypt('password'),
            'role' => 'moderator',
            'is_trusted' => true,
        ]);

        // Create regular user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@phonebd.net',
            'password' => bcrypt('password'),
            'role' => 'user',
            'is_trusted' => false,
        ]);

        // Create test phones
        $phones = [
            ['slug' => 'xiaomi-redmi-note-13', 'name' => 'Xiaomi Redmi Note 13'],
            ['slug' => 'samsung-galaxy-a54', 'name' => 'Samsung Galaxy A54'],
            ['slug' => 'iphone-15-pro', 'name' => 'iPhone 15 Pro'],
            ['slug' => 'oneplus-12', 'name' => 'OnePlus 12'],
            ['slug' => 'google-pixel-8', 'name' => 'Google Pixel 8'],
        ];

        foreach ($phones as $phoneData) {
            $phone = Phone::create($phoneData);

            // Create discussions for each phone
            for ($i = 1; $i <= 3; $i++) {
                $discussion = Discussion::create([
                    'phone_id' => $phone->id,
                    'user_id' => $i === 1 ? $user->id : null,
                    'guest_name' => $i === 1 ? null : 'Guest User ' . $i,
                    'content' => "This is a test question about {$phone->name}. Question number {$i}. How is the battery life? Is it worth buying?",
                    'ip_address' => '127.0.0.' . $i,
                    'status' => 'approved',
                    'is_pinned' => $i === 1,
                ]);

                // Create replies
                for ($j = 1; $j <= 2; $j++) {
                    DiscussionReply::create([
                        'discussion_id' => $discussion->id,
                        'user_id' => $j === 1 ? $admin->id : null,
                        'guest_name' => $j === 1 ? null : 'Helpful User',
                        'content' => "This is reply {$j} to the question. The battery life is excellent! I've been using it for 2 months now.",
                        'ip_address' => '127.0.0.' . ($j + 10),
                        'status' => 'approved',
                    ]);
                }
            }

            // Create one pending discussion
            Discussion::create([
                'phone_id' => $phone->id,
                'guest_name' => 'New User',
                'content' => "This is a pending question that needs moderation. What about the camera quality?",
                'ip_address' => '127.0.0.99',
                'status' => 'pending',
            ]);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Admin: admin@phonebd.net / password');
        $this->command->info('Moderator: moderator@phonebd.net / password');
        $this->command->info('User: user@phonebd.net / password');
    }
}
