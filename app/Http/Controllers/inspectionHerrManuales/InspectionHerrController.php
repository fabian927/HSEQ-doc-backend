<?php

namespace App\Http\Controllers\inspectionHerrManuales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\inspectionHerrManualesModel\InspectionHerrManuales;
use App\Models\inspectionHerrManualesModel\PreguntasHerrManuales;

class InspectionHerrController extends Controller
{
    public function createInspection(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'responsable' => 'required|string',
            'proyecto' => 'required|string',
            'ejecutor' => 'required|string',
            'fechaInicioTrab' => 'required',
            'departamento' => 'required|string',
            'municipio' => 'required|string',
            'actividad' => 'required|string'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message'=> 'Error en la validación de información',
                'error' => $validate->errors(),
                'status' => 422
            ]);
        }
        
        try {

            DB::beginTransaction();

            $inspection = InspectionHerrManuales::create([
                'usuario_id' => $request->userId,
                'responsable' => $request->responsable,
                'proyecto' => $request->proyecto,
                'ejecutor' => $request->ejecutor,
                'fecha' => $request->fechaInicioTrab,
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,
                'actividad' => $request->actividad
            ]);

            if ($request->has('preguntas') && is_array($request->preguntas)) {
                foreach ($request->preguntas as $pregunta) {
                    PreguntasHerrManuales::create([
                        'inspeccion_id' => $inspection->id,
                        'numero' => $pregunta['numero'],
                        'respuesta' => $pregunta['respuesta']
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Documento creado correctamente',
                'inspection_id' => $inspection->id,
                'status' => 201
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error en el servidor al crear el documento',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function getDocumentsComplete ($user_id)
    {
        error_log('entra a la funcion '.$user_id);
        try {
            $inspection = InspectionHerrManuales::with(['preguntasInspection', 'participantesInspection'])
            ->where('usuario_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Resgistros obtenidos correctamente',
                'data' => $inspection
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ocurrio un error al obtener la informacion del servidor',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}