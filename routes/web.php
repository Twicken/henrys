<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CandidateController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Services\ReferooService;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// App main entry route
Route::get('/', function (Request $request, ReferooService $referooService) {

    $code = $request->query('code');
    $state = $request->session()->pull('oauth2state');
    $queryState = $request->query('state');


    //if we dont have a referoo code, present user with welcome page.
    if(!$code){
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('/'),
            //'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,

        ]);
    }

    if (!strlen($state) || !strlen($code) || $state !== $queryState) {
        return redirect()->route('/')->with('error', 'Invalid state or authorization code.');
    }
    
    // Handle the authorization callback, exchange code for token
    try {
        $referooService->handleAuthorizationCallback($code);
    } catch (\Exception $e) {
        return redirect()->route('/')->with('error', 'Error during token exchange: ' . $e->getMessage());
    }
    
    return redirect()->to('/dashboard');
})->name('referoo.callback');


// Start the OAuth flow. This is when the user clicks on the login button.
Route::get('/referoo/redirect', function (ReferooService $referooService) {
    // Generate a unique state value for CSRF protection
    $state = bin2hex(random_bytes(16));
    session(['oauth2state' => $state]);

    return redirect($referooService->buildAuthorizationUrl($state));
})->name('login');


// This takes us to the candidate listing page if we have a referoo token.
Route::get('/dashboard', function (Request $request, ReferooService $referooService) {
    $offset = $request->input('offset', 0);
    $limit = $request->input('limit', 25);
    
    // If we have a token, fetch candidates
    if ($referooService->hasAccessToken()) {
        return Inertia::render('Dashboard', [
            'flash' => ['success' => session('success')],
        ]);
    }
    // If we don't have a token, redirect to login. should add error here.
    return redirect()->route('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');

Route::get('/candidatedetails/{num}', function (Request $request, ReferooService $referooService, $num) {
    $candidate = $referooService->getCandidateDetails($num); 
    $refereeDetails = $referooService->getCandidateReferees($num); 
    return Inertia::render('CandidateDetails', [
        'candidate' => $candidate,
        'refereedetails' => $refereeDetails ?? [],
    ]);
})->middleware(['auth', 'verified'])->name('candidate.details');


Route::post('/logout', function (Request $request, ReferooService $referooService) {
    // Call the logout method on ReferooService to clear tokens
    $referooService->logout();

    // Logout the user, invalidate the session and regenerate the token
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Create a redirect response to the home page
    $response = redirect('/');

    // To ensure token is cleared.
    $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
    $response->headers->set('Pragma', 'no-cache');
    $response->headers->set('Expires', '0');

    // Return the response
    return $response;
})->name('logout');

// User must be admin status in order to register another user. This needs to be modified to work with referoo in future.
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->middleware('is_admin')->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware(['is_admin']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
