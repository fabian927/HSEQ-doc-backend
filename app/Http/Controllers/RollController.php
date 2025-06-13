<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Roll;

class RollController extends Controller
{
    public function getRoll(){
        $roll = Roll::all();

        if ($roll->isEmpty()) {
            $data = [
                'message' => 'No se encontraron datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'roll' => $roll,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function createRoll (Request $request){
        
        $validate = Validator::make($request->all(),[

            'roll' => 'required',
            'active' => 'required|boolean'

        ]);

        if ($validate->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate->errors(),
                'status' => 200
            ];
            return response()->json($data, 400);
        }

        $roll = Roll::create([

            'roll' => $request->roll,
            'active' => $request->active
            
        ]);

        if (!$roll) {
            $data = [
                'message' => 'Error al crear roll',
                'status' => 500
            ];
            return response()->json($data, 400);
        }
        $data = [
            'message' => $roll,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function getById ($id){
        $roll = Roll::find($id);

        if (!$roll) {
            $data = [
                'message' => 'No se encontro roll',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $data = [
            'person' => $roll,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function deleteRoll($id){
        $roll = Roll::find($id);

        if (!$roll) {
            $data = [
                'message' => 'No se encontro persona',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $roll->delete();

        $data = [
            'message' => 'eliminada correctamente',
            'person' => $roll,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updateRoll(Request $request, $id){
        $roll = Roll::find($id);

        if (!$roll) {
            $data = [
                'message' => 'No se encontro roll',
                'status' => '400'
            ];
            
            return response()->json($data, 400);
        }

        $validate = Validator::make($request->all(),[

            'roll' => 'max:255',
            'active' => 'boolean'

        ]);

        if ($validate->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate->errors(),
                'status' => 200
            ];
            return response()->json($data, 400);
        }

        $roll->fill($request->only([
            'roll', 'active'
        ]));

        $roll->save();

        $data = [
            'message' => 'Roll actualizado',
            'person' => $roll,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
