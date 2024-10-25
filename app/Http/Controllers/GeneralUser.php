<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterApartmentRequest;
use App\Models\Apartment;
use App\Models\Message;
use App\Models\Service;
use Illuminate\Http\Request;


class GeneralUser extends Controller
{
    public function allApartment(){

        $allApartment = Apartment::with('services', 'messages')->get();


        return response()->json($allApartment);

    }

    public function services(){
        return response()->json(Service::all());
    }

    public function messages()
    {
        return response()->json(Message::all());
    }

    public function filtredApartment(FilterApartmentRequest $request)
    {

        $apartments = $request->filtredApartment();

        return response()->json($apartments);
    }





}
