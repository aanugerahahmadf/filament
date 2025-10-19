<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_avatar_is_accessible_via_avatar_url_attribute()
    {
        // Create a user with an avatar
        $user = User::factory()->create([
            'avatar' => 'avatars/test.jpg'
        ]);

        // Assert the avatar URL is correct
        $this->assertEquals(asset('storage/avatars/test.jpg'), $user->avatar_url);
    }

    /** @test */
    public function user_without_avatar_gets_default_avatar_url()
    {
        // Create a user without an avatar
        $user = User::factory()->create([
            'avatar' => null,
            'name' => 'John Doe'
        ]);

        // Assert the avatar URL falls back to ui-avatars
        $this->assertEquals(
            'https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff',
            $user->avatar_url
        );
    }
}
