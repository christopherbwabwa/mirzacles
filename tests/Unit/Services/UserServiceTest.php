<?php

namespace Tests\Unit\Services;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use \Illuminate\Pagination\LengthAwarePaginator;
use App\Services\UserService;
use App\Services\UserServices;
use Illuminate\Http\Request;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserServiceTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase, WithFaker;

//     /**
//      * @test
//      * @return void
//      */
//     public function it_can_return_a_paginated_list_of_users()
//     {
//           $page = new UserServices(new User, new Request);
          
//           assertEquals(LengthAwarePaginator::class, $page->list());
//     }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
         $user = new UserServices(new User, request());

         $response = $this->get('register', [$user]);

         $response->assertStatus(200);

     //    $user = User::factory()->make();

     //    $response = $this->get('register', [$user]);

     //    $response->assertStatus(200);
        
    }

    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
       $service = new UserServices(new User, request());
       
       $user = $service->find(1);

       assertEquals(1, $user->id);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
    {
       $service = new UserServices(new User, request());

      $user = $service->update(1, [
          'prefixname' => 'Ms',
            'firstname' => 'Katarina',
            'middlename' => 'Vasylivna',
            'lastname' => 'Numerov',
            'suffixname' => '',
            'username' => 'katy',
            'email' => 'kate@mail.test',
            'password' => bcrypt('password'),
            'photo' => 'No photo',
            'type' => 'visitor'
       ]);

    }

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function it_can_soft_delete_an_existing_user()
    // {
    //     // Arrangements

    //     // Actions

    //     // Assertions
    // }

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function it_can_return_a_paginated_list_of_trashed_users()
    // {
    //     // Arrangements

    //     // Actions

    //     // Assertions
    // }

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function it_can_restore_a_soft_deleted_user()
    // {
    //     // Arrangements

    //     // Actions

    //     // Assertions
    // }

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function it_can_permanently_delete_a_soft_deleted_user()
    // {
    //     // Arrangements

    //     // Actions

    //     // Assertions
    // }

    // /**
    //  * @test
    //  * @return void
    //  */
    // public function it_can_upload_photo()
    // {
    //     // Arrangements

    //     // Actions

    //     // Assertions
    // }
}
