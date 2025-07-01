<?php

namespace App\Http\Controllers\Ats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\atsModel\Ats;
use App\Models\atsModel\TipoBloqueo;
use App\Models\atsModel\ElementosProteccion;
use App\Models\atsModel\PreguntaAts;

class ATSController extends Controller
{
    public function createAts(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'proyecto' => 'required|string',
            'fecha' => 'required',
            'actividad' => 'required|string',
            'responsable' => 'required|string',
            'ejecutor' => 'required|string',
            'requiere_bloqueo' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en la validación de información.',
                'error' => $validate->errors(),
                'status' => 422
            ]);
        }

        try {
            DB::beginTransaction(); 

            $ats = Ats::create([
                'usuario_id' => $request->userId,
                'proyecto' => $request->proyecto,
                'fecha' => $request->fecha,
                'actividad' => $request->actividad,
                'responsable' => $request->responsable,
                'ejecutor' => $request->ejecutor,
                'requiere_bloqueo' => $request->requiere_bloqueo,
                'detalle_bloqueo' => $request->detalle_bloqueo,
                'otro_elemento' => $request->otro_elemento
            ]);

            if ($request->has('tBloqueo') && is_array($request->tBloqueo)) {
                foreach ($request->tBloqueo as $tipo) {
                    TipoBloqueo::create([
                        'ats_id' => $ats->id,
                        'tipo' => $tipo
                    ]);
                }
            }

            if ($request->has('elementosProteccion') && is_array($request->elementosProteccion)) {
                foreach ($request->elementosProteccion as $nombre => $activo) {
                    ElementosProteccion::create([
                        'ats_id' => $ats->id,
                        'nombre' => $nombre,
                        'activo' => $activo
                    ]);
                }
            }

            if ($request->has('preguntas') && is_array($request->preguntas)) {
                foreach ($request->preguntas as $pregunta) {
                    PreguntaAts::create([
                        'ats_id' => $ats->id,
                        'numero' => $pregunta['numero'],
                        'respuesta' => $pregunta['respuesta']
                    ]);
                }
            }

            DB::commit(); 

            return response()->json([
                'message' => 'ATS creado correctamente',
                'ats_id' => $ats->id,
                'status' => 201
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 

            Log::error('Error al crear ATS:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error en el servidor al crear ATS.',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function getAtsComplete ($user_id)
    {
        try {
            $ats = Ats::with(['participantesAts', 'responsablesAts', 'tiposBloqueo', 'elementosProteccion', 'preguntas'])
                ->where('usuario_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->take(30)
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Registros obtenidos correctamente.',
                'data' => $ats
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ocurrió un error al obtener los datos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAts ($user_id)
    {
        try {
            $ats = Ats::where('usuario_id', $user_id)
                ->orderBy('created_at', 'desc') 
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Registros ATS obtenidos correctamente.',
                'data' => $ats
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error al obtener ATS: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error al obtener los datos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}