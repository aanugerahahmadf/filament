<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample users
        $users = User::limit(5)->get();

        if ($users->count() < 2) {
            echo "Need at least 2 users to create messages\n";

            return;
        }

        // Create sample messages between users
        $messages = [
            [
                'from_user_id' => $users[0]->id,
                'to_user_id' => $users[1]->id,
                'subject' => 'Welcome to the System',
                'message' => 'Hello! Welcome to our CCTV monitoring system. If you have any questions, feel free to reach out.',
                'body' => 'Hello! Welcome to our CCTV monitoring system. If you have any questions, feel free to reach out.',
                'priority' => 'normal',
                'type' => 'notification',
            ],
            [
                'from_user_id' => $users[1]->id,
                'to_user_id' => $users[0]->id,
                'subject' => 'Re: Welcome to the System',
                'message' => 'Thank you for the welcome! I\'m excited to be part of this system.',
                'body' => 'Thank you for the welcome! I\'m excited to be part of this system.',
                'priority' => 'normal',
                'type' => 'response',
            ],
            [
                'from_user_id' => $users[2]->id,
                'to_user_id' => $users[0]->id,
                'subject' => 'System Update Notice',
                'message' => 'We\'ll be performing system maintenance this weekend. Please save your work before 6 PM.',
                'body' => 'We\'ll be performing system maintenance this weekend. Please save your work before 6 PM.',
                'priority' => 'high',
                'type' => 'notification',
            ],
            [
                'from_user_id' => $users[0]->id,
                'to_user_id' => $users[3]->id,
                'subject' => 'CCTV Camera Issue',
                'message' => 'Camera #15 in Building A seems to be offline. Please check when possible.',
                'body' => 'Camera #15 in Building A seems to be offline. Please check when possible.',
                'priority' => 'high',
                'type' => 'alert',
            ],
            [
                'from_user_id' => $users[3]->id,
                'to_user_id' => $users[0]->id,
                'subject' => 'Re: CCTV Camera Issue',
                'message' => 'I\'ve checked Camera #15 and it\'s back online now. The issue was a loose connection.',
                'body' => 'I\'ve checked Camera #15 and it\'s back online now. The issue was a loose connection.',
                'priority' => 'normal',
                'type' => 'response',
            ],
        ];

        // Insert the messages
        foreach ($messages as $messageData) {
            Message::create($messageData);
        }

        echo 'Created '.count($messages)." sample messages\n";
    }
}
