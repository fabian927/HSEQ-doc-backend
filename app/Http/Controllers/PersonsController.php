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
    error_log("id desde delete: " . $id);
    
    $person = Persons::find($id);

    error_log("person desde delete: " . json_encode($person));

    try {
        $person->delete();
        error_log("Persona eliminada ID: $id");
        
        return response()->json([
            'success' => true,
            'message' => 'Persona eliminada correctamente',
            'data' => $person
        ]);

    } catch (\Exception $e) {
        error_log("Error al eliminar persona ID: $id - " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar persona',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function updatePerson(Request $request, $id)
    {
        $person = Persons::find($id);
        
        if (!$person) {
            return response()->json([
                'message' => 'No se encontró persona',
                'status' => 404
            ], 404);
        }

        error_log($request);
        error_log($person);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|max:255',
            'last_name' => 'sometimes|max:255',
            'doc_type' => 'sometimes|max:255',
            'document' => 'sometimes|unique:personas,document,' . $person->id,
            'email' => 'sometimes|email|unique:personas,email,' . $person->id,
            'phone' => 'sometimes|max:255',
            'address' => 'sometimes|max:255',
            'birthdate' => 'sometimes|date',
            'age' => 'sometimes|integer|max:150'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 422 
            ], 422);
        }

        $updateData = $request->only([
            'name',
            'last_name',
            'doc_type',
            'document',
            'email',
            'phone',
            'address',
            'birthdate',
            'age'
        ]);

        $changes = false;
        foreach ($updateData as $key => $value) {
            if ($person->$key != $value) {
                $changes = true;
                break;
            }
        }

        if (!$changes) {
            return response()->json([
                'message' => 'No se detectaron cambios para actualizar',
                'person' => $person,
                'status' => 200
            ], 200);
        }

        $person->fill($updateData);
        $person->save();

        $person->refresh();

        error_log($person);

        return response()->json([
            'message' => 'Persona actualizada correctamente',
            'person' => $person,
            'status' => 200
        ], 200);
    }
}
