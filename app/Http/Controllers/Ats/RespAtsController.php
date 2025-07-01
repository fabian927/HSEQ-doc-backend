<?php

namespace App\Http\Controllers\Ats;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\atsModel\ResponsablesAts;

class RespAtsController extends Controller
{
    public function responsablesAtsCreate (Request $request)
    {
        $validate = Validator::make($request->all(), [
            'responsables' => 'required|array',
            'responsables.*.atsId' => 'required|integer',
            'responsables.*.nameResponsable' => 'required|String',
            'responsables.*.tipoResponsable' => 'required|String',
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
                $newResponsable = ResponsablesAts::Create([
                    'ats_id' => $responsable['atsId'],
                    'name_resp' => $responsable['nameResponsable'],
                    'tipo_responsable' => $responsable['tipoResponsable'],
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