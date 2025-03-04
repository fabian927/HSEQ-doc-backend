<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Location;

class LocationController extends Controller
{
    public function getLocation(){

        $location = Location::all();

        if ($location->isEmpty()) {
            $data = [
                'message' => 'No se encontraron datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'location' => $location,
            'status' => 200
        ];
        return response()->json($data, 400);
    }

    public function createLocation(Request $request){

        $validate = Validator::make($request->all(),[
            'longitude' => 'required',
            'latitude' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'population' => 'required'
        ]);

        if ($validate->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate,
                'status' => 200
            ];         
            return response()->json($data, 400);   
        }

        $location = Location::create([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'address' => $request->address,
            'population' => $request->population
        ]);

        if (!$location) {
            $data = [
                'message' => 'Error al crear location',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'location' => $location,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function getById($id){
        
        $location = Location::find($id);

        if (!$location) {
            $data = [
                'message' => 'No se encontro Location By Id',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $data = [
            'location' => $location,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function deleteLocation($id){

        $location = Location::find($id);

        if (!$location) {
            $data = [
                'message' => 'No se encontro location by id delete',
                'status' => 400
            ];
            return reponse()->json($data, 400);
        }

        $location->delete();

        $data = [
            'message' => 'Location eliminada correctamente',
            'location' => $location,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updateLocation(Request $request, $id){
        $location = Location::find($id);

        if (!$location) {
            $data = [
                'message' => 'No se encontro location',
                'status' => '400'
            ];
            
            return response()->json($data, 400);
        }

        $validate = Validator::make($request->all(),[
            'longitude' => 'required',
            'latitude' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'population' => 'required'
        ]);

        if ($validate->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate,
                'status' => 200
            ];
            return response()->json($data, 400);
        }

        $location->longitude = $request->longitude;
        $location->latitude = $request->latitude;
        $location->country = $request->country;
        $location->state = $request->state;
        $location->city = $request->city;
        $location->address = $request->address;
        $location->population = $request->population;

        $location->save();

        $data = [
            'message' => 'Location actualizada',
            'person' => $location,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
