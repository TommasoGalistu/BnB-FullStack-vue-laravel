<?php

namespace App\Http\Controllers\Auth\Ura;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function generate(Request $request){
        return 'generate';
    }
    public function makePayment(Request $request){
        return 'make Payment';
    }
}
