<?php

namespace App\Http\Controllers\Ats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\atsModel\ParticipantesAts;

class PartAtsController extends Controller
{
    public function participantesAtsCreate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'participantes' => 'required|array',
            'participantes.*.atsId' => 'required|integer',
            'participantes.*.namePart' => 'required|string',
            'participantes.*.cedulaPart' => 'required|string',
            'participantes.*.cargoPart' => 'required|string',
            'participantes.*.empresaPart' => 'required|string',
            'participantes.*.participation' => 'required|string',
            'participantes.*.firma' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en la validación de participantes.',
                'errors' => $validate->errors(),
                'status' => 422
            ], 422);
        }

        try {
            DB::beginTransaction();

            $createdParticipants = [];
            
            foreach ($request->participantes as $participante) {
                $newParticipant = ParticipantesAts::create([
                    'ats_id' => $participante['atsId'],
                    'name_part' => $participante['namePart'],
                    'cedula_part' => $participante['cedulaPart'],
                    'cargo_part' => $participante['cargoPart'],
                    'empresa_part' => $participante['empresaPart'],
                    'participacion' => $participante['participation'],
                    'firma' => $participante['firma']
                ]);
                
                $createdParticipants[] = $newParticipant->id;
            }

            DB::commit();

            return response()->json([
                'message' => 'Participantes creados correctamente',
                'participantes_ids' => $createdParticipants,
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al crear participantes ATS:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al crear participantes.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}