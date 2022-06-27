<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use App\Services\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserServices implements UserServiceInterface
{
    /**
     * The model instance.
     *
     * @var App\Model\User
     */
    protected $model;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Constructor to bind model to a repository.
     *
     * @param \App\Model\User                $model
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(User $model, Request $request)
    {
        $this->model = $model;
        $this->request = $request;
    }

    /**
     * Define the validation rules for the model.
     *
     * @param  int $id
     * @return array
     */
    public function rules($id = null)
    {
        return [
            /**
             * Rule syntax:
             *  'column' => 'validation1|validation2'
             *
             *  or
             *
             *  'column' => ['validation1', function1()]
             */
            'firstname' => 'required',
        ];
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        return view('users.index', [
            'users' => User::paginate(5)
        ]);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        
        return Validator::make($attributes, [

            'prefixname' => ['required', Rule::in(['Mr', 'Mrs', 'Ms'])],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param  integer $id
     * @return \Illuminate\Database\Eloquent\User|null
     */
    public function find(int $id):? User
    {

        $this->quick_create_user();

        $user = User::find($id);

        return $user;
    }

    /**
     * Update model resource.
     *
     * @param  integer $id
     * @param  array   $attributes
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        $this->quick_create_user();
        
       $user = User::find($id);

       $user->update([$attributes]);
    
       return true;
       
    }

    /**
     * Soft delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function destroy($id)
    {
        // Code goes brrrr.
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
        // Code goes brrrr.
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
        // Code goes brrrr.
    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
        // Code goes brrrr.
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        // Code goes brrrr.
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        // Code goes brrrr.
    }

    private function quick_create_user()
    {
       return User::create([
            'prefixname' => 'Mr',
            'firstname' => 'Christopher',
            'middlename' => '',
            'lastname' => 'Bwabwa',
            'suffixname' => '',
            'username' => 'chreez',
            'email' => 'cbwabwa@mail.test',
            'password' => bcrypt('password'),
            'photo' => ' Some photo',
            'type' => 'Admin'
        ]);
    }
}