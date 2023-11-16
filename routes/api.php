<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use App\Models\User;
// use Vendor\Sanctum\Src\Http\Controllers\CsrfCookieController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Route::get('/sanctum/csrf-cookie',[CsrfCookieController::class, 'show']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

     $request->user();
});

# this featur is'nt working yet
Route::middleware('auth:sanctum','admin')->post('/mail', function (Request $request) {
     // $user = User::findOrFail($request->id);
     $url = URL::temporarySignedRoute('register',now()->addSeconds(60),['name'=>'sia']);
     Mail::to($request->email)->send(new WelcomeMail($url));
     return response()->json(["message"=>"email sent"]);
     });
Route::middleware('auth:sanctum')->get('/patients',[PatientController::class, 'total']);
Route::middleware('auth:sanctum','doc')->get('/patient',[PatientController::class, 'show']);
Route::middleware('auth:sanctum')->post('/patient',[PatientController::class, 'store']);
Route::middleware('auth:sanctum')->put('/patient/{id}',[PatientController::class, 'update']);
Route::middleware('auth:sanctum')->delete('patient/{id}', [PatientController::class, 'show']);

Route::middleware('auth:sanctum','pharm')->get('/drugs',[DrugController::class, 'show']);
Route::middleware('auth:sanctum','pharm')->post('/drugs',[DrugController::class, 'store']);
Route::middleware('auth:sanctum','pharm')->put('/drugs/{id}',[DrugController::class, 'update']);
Route::middleware('auth:sanctum','pharm')->delete('/drugs/{id}',[DrugController::class, 'delete']);

Route::middleware('auth:sanctum')->get('/prescriptions',[PrescriptionController::class, 'total']);
Route::middleware('auth:sanctum')->get('/prescription/patients/',[PrescriptionController::class, 'get']);
Route::middleware('auth:sanctum','doc')->get('/prescription',[PrescriptionController::class, 'show']);
Route::middleware('auth:sanctum','doc')->post('/prescription',[prescriptionController::class, 'store']);
Route::middleware('auth:sanctum')->put('/prescription/{id}',[PrescriptionController::class, 'update']);

// doctor endpoint
Route::middleware('auth:sanctum','doc')->get('/patients/search/',[PatientController::class, 'search']);

Route::get('/boom/{name}', function(\Illuminate\Http\Request $request,$name){
    if(!$request->hasValidSignature()){
        abort(401);
    }
    return redirect("https://google.com");
//     response()->json(array( 'details'=>true));
})->name('register');

// Route::get('/sign',function(){
//  $url = URL::temporarySignedRoute('doc',now()->addSeconds(30),['name'=>'sia']);
//  return $url;
// });

