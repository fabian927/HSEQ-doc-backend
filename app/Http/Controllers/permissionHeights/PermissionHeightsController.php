<?php

namespace App\Http\Controllers\PermissionHeights;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PermissionHeightsModel\PermissionHeights;
use App\Models\PermissionHeightsModel\ElementsProtect;
use App\Models\PermissionHeightsModel\AskPermission;
use App\Models\PermissionHeightsModel\AccessType;

class PermissionHeightsController extends Controller
{
    public function createPermission (Request $request)
    {

        $validate = Validator::make($request ->all(), [
            'userId' => 'required|integer',
            'responsable' => 'required|string',
            'proyecto' => 'required|string',
            'ejecutor' => 'required|string',
            'alturaTrabajo' => 'required|string',
            'alturaCaida' => 'required|string',
            'lugarExacto' => 'required|string',
            'equipo' => 'required|string',
            'fechaInicioTrab' => 'required',
            'fechaFinTrab' => 'required',
            'herramientas' => 'required|string',
            'departamento' => 'required|string',
            'municipio' => 'required|string',
            'actividad' => 'required|string',
            'nPersonas' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'message' => 'Error en la validación de información',
                'error' => $validate->errors(),
                'status' => 422
            ]);
        }

        try {
            
            DB::beginTransaction(); 

            $permission = PermissionHeights::create([
                'usuario_id' => $request->userId,
                'responsable' => $request->responsable,
                'proyecto' => $request->proyecto,
                'ejecutor' => $request->ejecutor,
                'altura_trabajo' => $request->alturaTrabajo,
                'altura_caida' => $request->alturaCaida,
                'lugar_exacto' => $request->lugarExacto,
                'equipo' => $request->equipo,
                'fecha_inicio' => $request->fechaInicioTrab,
                'fecha_fin' => $request->fechaFinTrab,
                'herramientas' => $request->herramientas,
                'departamento' => $request->departamento,
                'municipio' => $request->municipio,
                'actividad' => $request->actividad,
                'n_personas' => $request->nPersonas,
                'observaciones' => $request->observaciones,
                'otro_elemento' => $request->otroElemento,
                'otro_elemento_protect' => $request->otroElementoProtect
            ]);

            if ($request->has('elementsProtect') && is_array($request->elementsProtect)) {
                foreach ($request->elementsProtect as $nombre => $activo) {
                    ElementsProtect::create([
                        'permission_id' => $permission->id,
                        'nombre' => $nombre,
                        'activo' => $activo
                    ]);
                }
            }

            if ($request->has('accessType') && is_array($request->accessType)) {
                foreach ($request->accessType as $nombre => $activo) {
                    AccessType::create([
                        'permission_id' => $permission->id,
                        'nombre' => $nombre,
                        'activo' => $activo
                    ]);
                }
            }

            if ($request->has('preguntas') && is_array($request->preguntas)) {
                foreach ($request->preguntas as $pregunta) {
                    AskPermission::create([
                        'permission_id' => $permission->id,
                        'numero' => $pregunta['numero'],
                        'respuesta' => $pregunta['respuesta']
                    ]);
                }
            }

            DB::commit(); 

            return response()->json([
                'message' => 'permiso creado correctamente',
                'permission_id' => $permission->id,
                'status' => 201
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 

            Log::error('Error al crear permiso:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error en el servidor al crear permiso.',
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function getPermComplete ($user_id) 
    {
        try {
            $permission = PermissionHeights::with(['accessType', 'elementsProtect', 'askPermission', 'partPermission', 'respPermission'])
            ->where('usuario_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Registros obtenidos correctamente.',
                'data' => $permission
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Ocurrió un error al obtener los datos.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}