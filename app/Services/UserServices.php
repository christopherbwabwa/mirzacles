<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
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
            /**
             * Rule syntax:
             *  'column' => 'validation1|validation2'
             *
             *  or
             *
             *  'column' => ['validation1', function1()]
             */
            
            'username' => [
                'string',
                'required',
                'min:3',
                'max:255',
                Rule::unique('users')
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
                Rule::unique('users')
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
         $this->model::factory()->make($attributes);

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
       return $this->model::onlyTrashed()->get();
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
        Storage::putFile('photos', $this->request->file($file));
    }

}