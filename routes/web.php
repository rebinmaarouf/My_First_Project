<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Profile\AvatarController;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

use App\Http\Controllers\TicketController; 
use App\Http\Controllers\Auth\ProviderController; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {


    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::post('/profile/avatar/ai',[AvatarController::class,'generate'])->name('profile.avatar.ai');

    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


// Route::get('/openai',function(){
// $result = OpenAI::images()->create([
//     "prompt" => "create avatar for user with cool style animated in tech world",
//     "n" => 1,
//     "size" => "256x256",

// ]);

// dd($result);
// });

// Route::get('auth/github',[GithubController::class,'redirect'])->name(name:'github.login');
// Route::get('auth/github/callback',[GithubController::class,'callback']);






// Route::get('/auth/redirect', function () {
//     return Socialite::driver(driver:'github')->redirect();
// });


// Route::get('login/github', 'Auth\LoginController@redirectToProvider')->name('github');

// Route::get('/auth/github/callback', function () {
//     $user = Socialite::driver(driver:'github')->user();
//     $user = User::updateOrCreate(['email' => $user->email],[
//         'name' => $user->email,
//         'password' => 'password',
//     ]);
//    Auth::login($user);
//    return redirect('/dashboard');
// });

Route::middleware('auth')->group(function(){

// Route::get('/ticket/create',[TicketController::class,'create'])->name('ticket.create');
// Route::post('/ticket/create',[TicketController::class,'store'])->name('ticket.create');

Route::resource('/ticket',TicketController::class);

    
});

Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);
 
Route::get('/auth/{provider}/callback',[ProviderController::class, 'callback'] );


