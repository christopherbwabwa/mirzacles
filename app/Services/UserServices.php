<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Storage;
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
            
            'username' => [
                'string',
                'required',
                'min:3',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],

            'photo' => ['image'],
            'prefixname' => ['required', Rule::in(['Mr', 'Mrs', 'Ms'])],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id)
            ],

            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],

        ];


        
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        return $this->model::paginate(5);
    }

    /**
     * Create model resource.
     *
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $attributes)
    {
        $attributes['password'] = $this->hash($attributes['password']);

        if ($attributes['photo']) {

            $attributes['photo'] = Storage::putFile('photos', $attributes['photo']);
        }

        else
        {
            $attributes['photo'] = 'randomimage.jpg';
        }

         $this->model::factory()->create($attributes);

         return redirect()->route('home');
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
        $user = User::withTrashed()->findOrFail($id);

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
        $user = $this->model::findOrFail($id);

        $attributes['password'] = $this->hash($attributes['password']);

        if ($attributes['photo']) {

            $attributes['photo'] = Storage::putFile('photos', $attributes['photo']);
        }
        
        $user->update($attributes);

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
       $users = $this->model->find($id);
    
       $users[0]->delete();
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function listTrashed()
    {
       return $this->model::onlyTrashed()->paginate(5);
    }

    /**
     * Restore model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function restore($id)
    {
       $users = $this->model::onlyTrashed()
                ->where('id', $id)
                ->get();

        $users[0]->restore();

    }

    /**
     * Permanently delete model resource.
     *
     * @param  integer|array $id
     * @return void
     */
    public function delete($id)
    {
       $user = $this->model::onlyTrashed()->findOrFail($id);

       $user->forceDelete();
       
    }

    /**
     * Generate random hash key.
     *
     * @param  string $key
     * @return string
     */
    public function hash(string $key): string
    {
        return bcrypt($key);
    }

    /**
     * Upload the given file.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return string|null
     */
    public function upload(UploadedFile $file)
    {
        Storage::putFile('photos', $this->request->file($file));
    }

    public function details($user) 
    {
        DB::table('details')->insert([
            'key' => 'Full name',
            'value' => "$user->firstname $user->middlename $user->lastname",
            'icon' => $user->photo
        ]);
    }

}