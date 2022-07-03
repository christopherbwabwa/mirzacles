<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\UploadedFile;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserServiceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_users()
    {

        User::factory(20)->create();

        (new UserServices(new User, request()));

        $response = $this->get('/');

        $response->assertSee('Next');
    }

    /**
     * @test
     * @return void
     */
    public function test_it_can_store_a_user_to_database()
    {
        $this->assertCount(0, User::all());

        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'photo' => ' Some photo',
            'type' => 'Admin'
        ];

        (new UserServices(new User, request()))->store($data);

        $response = $this->post('register', $data);

        $response->assertStatus(302);

        $this->assertCount(1, User::all());
    }

    /**
     * @test
     * @return void
     */
    public function test_it_can_find_and_return_an_existing_user()
    {
        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'photo' => ' Some photo',
            'type' => 'Admin'
        ];

        (new UserServices(new User, request()))->store($data);

        $this->post('register', $data);

        $user = (new UserServices(new User, request()))->find(1);

        $this->assertEquals('Christopher', $user->firstname);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
        $user = User::factory()->create();

        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Pedri',
            'middlename' => '',
            'lastname' => 'Nion',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'password_confirmation' => 'password',
            'photo' => ' Some photo',
            'type' => 'Admin'
        ];

        (new UserServices($user, request()))->update($user->id, $data);

        $this->assertDatabaseHas('users', [
            'firstname' => 'Pedri',
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {

        User::factory()->create();

        $user = User::first();

        $this->assertNotEquals('Christopher', $user->firstname);

        (new UserServices($user, request()))->destroy($user->id);

        $this->assertCount(0, User::all());

        $this->assertSoftDeleted($user);

    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(RouteServiceProvider::HOME);

        $response2 = $this->get('/users/archived');

        $response2->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        User::factory()->create();

        $this->assertDatabaseCount('users', 1);

        $user = User::first();

        (new UserServices($user, request()))->destroy($user->id);

        $this->assertSoftDeleted($user);

        (new UserServices($user, request()))->restore($user->id);

        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_permanently_delete_a_soft_deleted_user()
    {
        User::factory()->create();

        $this->assertDatabaseCount('users', 1);

        $user = User::first();

        (new UserServices($user, request()))->destroy($user->id);

        $this->assertSoftDeleted($user);

        $trashed = User::onlyTrashed()->first();

        (new UserServices($trashed, request()))->delete($trashed->id);

        $this->assertDatabaseCount('users', 0);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // Storage::fake('avatars');
        
        // $response = $this->json('POST', 'register', [
        //     'prefixname' => 'Mr',
        //     'firstname' => 'Pedri',
        //     'middlename' => '',
        //     'lastname' => 'Nion',
        //     'suffixname' => '',
        //     'username' => 'chreez',
        //     'email' => 'cbwabwa@mail.test',
        //     'password' => 'password',
        //     'password_confirmation' => 'password',
        //     'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        //     'type' => 'Admin'
           
        // ]);
        // // Assert the file was stored...
        // Storage::disk('avatars')->assertExists('avatar.jpg');
        // // Assert a file does not exist...
        // Storage::disk('avatars')->assertMissing('missing.jpg');

        // $filename = UploadedFile::fake()->image('logo.jpg');

        // $response = $this->post('register', [
            
        //     'prefixname' => 'Mr',
        //     'firstname' => 'Pedri',
        //     'middlename' => '',
        //     'lastname' => 'Nion',
        //     'suffixname' => '',
        //     'username' => 'chreez',
        //     'email' => 'cbwabwa@mail.test',
        //     'password' => 'password',
        //     'password_confirmation' => 'password',
        //     'photo' => (new UserServices(new User, request()))->upload($filename),
        //     'type' => 'Admin'

        // ]);

        // $response->assertInvalid();
    }
}
