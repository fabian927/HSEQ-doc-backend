<?php

namespace App\Http\Controllers\inspectionHerrManuales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\inspectionHerrManualesModel\ParticipantesHerrManuales;

class PartInspectionController extends Controller
{
    public function createPartInspection(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'participantes' => 'required|array',
            'participantes.*.inspectionId' => 'required|integer',
            'participantes.*.namePart' => 'required|String',
            'participantes.*.cedulaPart' => 'required|String',
            'participantes.*.cargoPart' => 'required|String',
            'participantes.*.empresaPart' => 'required|String',
            'participantes.*.firma' => 'required|String',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en la validación de información desde el servidor',
                'error' => $validate->errors(),
                'status' => 422
            ], 422);
        }

        try {
            DB::beginTransaction();

            $createdParticipantesInsp = [];

            foreach($request->participantes as $participante){
                $newParticipante = ParticipantesHerrManuales::create([
                    'inspeccion_id' => $participante['inspectionId'],
                    'name_part' => $participante['namePart'],
                    'cedula_part' => $participante['cedulaPart'],
                    'cargo_part' => $participante['cargoPart'],
                    'empresa_part' => $participante['empresaPart'],
                    'firma' => $participante['firma']
                ]);

                $createdParticipantesInsp = $newParticipante->id;
            }

            DB::commit();

            return response()->json([
                'message' => 'Participantes del documento creados correctamente',
                'participantes_ids' => $createdParticipantesInsp,
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
            throw new Exception("Error Processing Request");
            return response()->json([
                'message' => 'Error en el servidor al crear el documento',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}