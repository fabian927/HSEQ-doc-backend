<?php

namespace App\Http\Controllers\PermissionHeights;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\PermissionHeightsModel\RespPermission;

class RespPermissionController extends Controller
{
    public function respPermissionCreate (Request $request)
    {
        $validate = Validator::make($request->all(), [
            'responsables' => 'required|array',
            'responsables.*.permissionId' => 'required|integer',
            'responsables.*.nameResp' => 'required|String',
            'responsables.*.cedulaResp' => 'required|String',
            'responsables.*.cargoResp' => 'required|String',
            'responsables.*.firma' => 'required|String',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'messsage' => 'Error en la validacion de responsables',
                'errors' => $validate->errors(),
                'status' => 422
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $createdResponsables = [];

            foreach ($request->responsables as $responsable) {
                $newResponsable = RespPermission::Create([
                    'permission_id' => $responsable['permissionId'],
                    'nombre_resp' => $responsable['nameResp'],
                    'cedula_resp' => $responsable['cedulaResp'],
                    'cargo_resp' => $responsable['cargoResp'],
                    'firma' => $responsable['firma']
                ]);

                $createdResponsables[] = $newResponsable->id;
            }

            DB:: commit();

            return response()->json([
                'message' => 'Responsables Creados con exito',
                'responsables_id' => $createdResponsables,
                'status' => 201
            ], 201);

        } catch (\Exception $e) {
             DB::rollBack();

            return response()->json([
                'message' => 'Error al crear responsables.',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }

    }
}