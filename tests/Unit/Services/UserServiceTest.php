<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\UploadedFile;
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

       $data = (new UserServices(new User, request()))->list();

       $this->assertInstanceOf(LengthAwarePaginator::class, $data);

    }

    /**
     * @test
     * @return void
     */
    public function test_it_can_store_a_user_to_database()
    {
        $this->assertCount(0, User::all());

        $image = UploadedFile::fake()->image('logo.jpg');

        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'photo' => $image,
            'type' => 'Admin'
        ];

        (new UserServices(new User, request()))->store($data);

        $this->assertCount(1, User::all());
    }

    /**
     * @test
     * @return void
     */
    public function test_it_can_find_and_return_an_existing_user()
    {
        $this->assertDatabaseCount('users', 0);

        $image = UploadedFile::fake()->image('logo.jpg');

        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'photo' => $image,
            'type' => 'Admin'
        ];

        (new UserServices(new User, request()))->store($data);

        $this->assertDatabaseCount('users', 1);
        
        $user = (new UserServices(new User, request()))->find(1);

        $this->assertEquals('Christopher', $user->firstname);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {

        $image = UploadedFile::fake()->image('logo.jpg');

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
            'photo' => $image,

            'type' => 'Admin'
        ];

        $this->assertDatabaseCount('users', 0);

        User::factory()->create();

        $this->assertDatabaseCount('users', 1);

        $user = User::first();

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

        $this->assertDatabaseCount('users', 1);
        
        $user = User::first();

        (new UserServices(new User, request()))->destroy($user);

        $this->assertCount(0, User::all());

        $this->assertSoftDeleted($user);

    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        User::factory(20)->create();
        
        $users = User::all();

        foreach ($users as $user)
        {
            (new UserServices($user, request()))->destroy($user);
        }
        
        
        $data = (new UserServices(new User, request()))->listTrashed();

        $this->assertInstanceOf(LengthAwarePaginator::class, $data);

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

        (new UserServices($user, request()))->destroy($user);

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

        (new UserServices($user, request()))->destroy($user);

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
        $this->assertCount(0, User::all());

        $image = UploadedFile::fake()->image('logo.jpg');

        $data = [
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => 'password',
            'photo' => $image,
            'type' => 'Admin'
        ];

        (new UserServices(new User, request()))->store($data);

        $user = User::first();

        $this->assertNotEmpty($user['photo']);
    }
}
