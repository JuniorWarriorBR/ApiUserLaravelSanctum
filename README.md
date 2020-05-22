## Just a simple API in laravel with Sanctum

## About steps

```
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## Add Sanctum's middleware to your api (App\Http\Kernel.php)

```
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

'api' => [
    EnsureFrontendRequestsAreStateful::class,
    'throttle:60,1',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## In User model

```
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```

## Make a controller and put methods

```
php artisan make:controller Api/UserApi/UserApiController

<?php

namespace App\Http\Controllers\Api\UserApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserApiController extends Controller
{
    public function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }

             $token = $user->createToken('my-app-token')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

             return response($response, 201);
    }

    public function users()
    {
        return User::all();
    }
}
```

## Create a user

```
php artisan thinker
$user = new User;
$user->name = 'admin';
$user->email = 'admin@laravel.com';
$user->password = Hash::make('admin');
$user->save();
```

## Create a route in routes/api.php

```
Route::get('/', function () {return view('welcome');});
Route::post('/login', 'Api\UserApi\UserApiController@index');
Route::middleware('auth:sanctum')->get('/users', 'Api\UserApi\UserApiController@users');
```

## Now just test with postman
