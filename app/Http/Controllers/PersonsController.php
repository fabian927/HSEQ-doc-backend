<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Persons;

class PersonsController extends Controller
{
    public function getPersons()
    {
        $persons = Persons::all();

        if ($persons->isEmpty()) {
            $data = [
                'message' => 'No se encontraron datos',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'persons' => $persons,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function createPerson(Request $request)
    {
        error_log("request" . json_decode($request));
        $validate = Validator::make($request->all(), [
            'name' => 'required|String',
            'last_name' => 'required|String',
            'doc_type' => 'required|String',
            'document' => 'required|unique:personas',
            'email' => 'required|email',
            'phone' => 'required|String',
            'address' => 'required|String',
            'birthdate' => 'required|String',
            'age' => 'required|String'
        ]);

        if ($validate->fails()) {
            error_log(json_encode($validate->errors()));

            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate->errors(),
                'status' => 200
            ];
            return response()->json($data, 400);
        }

        $persons = Persons::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'doc_type' => $request->doc_type,
            'document' => $request->document,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'birthdate' => $request->birthdate,
            'age' => $request->age
        ]);

        if (!$persons) {
            $data = [
                'message' => 'Error al crear persona',
                'status' => 500
            ];
            return response()->json($data, 400);
        }
        $data = [
            'message' => $persons,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function getById($document)
    {
        $person = Persons::where('document', $document)->first();

        if (!$person) {
            $data = [
                'message' => 'No se encontro persona',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $data = [
            'person' => $person,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function deletPerson($id)
    {
        $person = Persons::find($id);

        if (!$person) {
            $data = [
                'message' => 'No se encontro persona',
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $person->delete();

        $data = [
            'message' => 'eliminada correctamente',
            'person' => $person,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePerson(Request $request, $id)
    {
        $person = Persons::find($id);

        if (!$person) {
            $data = [
                'message' => 'No se encontro persona',
                'status' => '400'
            ];

            return response()->json($data, 400);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'max:255',
            'last_name' => 'max:255',
            'doc_type' => 'max:255',
            'document' => 'unique:personas,document,' . $person->id,
            'email' => 'email|unique:personas,email,' . $person->id,
            'phone' => 'max:255',
            'address' => 'max:255',
            'birthdate' => 'max:255',
            'age' => 'max:255'
        ]);

        if ($validate->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validate->errors(),
                'status' => 200
            ];
            return response()->json($data, 400);
        }

        $person->fill($request->only([
            'name',
            'last_name',
            'doc_type',
            'document',
            'email',
            'phone',
            'address',
            'birthdate',
            'age'
        ]));

        $person->save();

        $data = [
            'message' => 'Persona actualizada',
            'person' => $person,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
