<?php

use Braintree\Gateway;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
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
Route::get('/proxy-tomtom', function(Request $request) {
    $url = $request->query('url');

    if (!$url) {
        return response()->json(['error' => 'URL mancante'], 400);
    }

    try {
        // Fai la richiesta all'API TomTom
        $client = new Client(['verify' => false]);  // Disabilita SSL solo per sviluppo
        $response = $client->get($url);

        $jsonData = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'La risposta non è un JSON valido'], 500);
        }

        return response()->json($jsonData, 200);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Errore nella richiesta: ' . $e->getMessage()], 500);
    }
});

// payment api
Route::get('/braintree/token', function () {
    $gateway = new Gateway([
        'environment' => env('BRAINTREE_ENV'),
        'merchantId' => env('BRAINTREE_MERCHANT_ID'),
        'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
        'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
    ]);

    $clientToken = $gateway->clientToken()->generate();
    return response()->json(['clientToken' => $clientToken]);
});

Route::post('/braintree/checkout', function (Request $request) {
    $gateway = new Gateway([
        'environment' => env('BRAINTREE_ENV'),
        'merchantId' => env('BRAINTREE_MERCHANT_ID'),
        'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
        'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
    ]);

    $nonce = $request->paymentMethodNonce;
    $result = $gateway->transaction()->sale([
        'amount' => '10.00',  // Importo della transazione
        'paymentMethodNonce' => $nonce,
        'options' => [
            'submitForSettlement' => true
        ]
    ]);

    return response()->json($result);
});
// nel path della root in assenza di caratteri dopo / o caratteri sbagliati, la route reindirizza alla pagina app di vue
Route::get('/', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');



