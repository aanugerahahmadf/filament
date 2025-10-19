<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_avatar()
    {
        // Create a user
        /** @var User $user */
        $user = User::factory()->create();

        // Create a fake image file
        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg', 100, 100);

        // Acting as the user, upload the avatar using Volt
        $this->actingAs($user);

        $component = Volt::test('settings.profile')
            ->set('avatar', $file)
            ->call('updateProfileInformation');

        $component->assertHasNoErrors();

        // Refresh the user to get the updated avatar
        $user->refresh();

        // Assert the user's avatar attribute was updated
        $this->assertNotNull($user->avatar);
        $this->assertStringStartsWith('avatars/', $user->avatar);

        // Assert the file was stored
        $this->assertTrue(Storage::disk('public')->exists($user->avatar));
    }

    /** @test */
    public function user_avatar_is_accessible_via_avatar_url_attribute()
    {
        // Create a user with an avatar
        /** @var User $user */
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
        /** @var User $user */
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
