<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;
use Illuminate\Support\Facades\Validator;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        $query = Registro::query();

        if ($request->has('deleted')) {
            $query->where('deleted', $request->input('deleted'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('order_by')) {
            $query->orderBy($request->input('order_by'));
        }
        if ($request->has('limit')) {
            $query->limit($request->input('limit'));
        }
        if ($request->has('offset')) {
            $query->offset($request->input('offset'));
        }

        $registros = $query->get();

        return response()->json($registros);
    }

    public function show($id)
    {
        $registro = Registro::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($registro);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'type' => 'required|in:denuncia,sugestao,duvida',
            'message' => 'required|string',
            'is_identified' => 'required|boolean',
            'whistleblower_name' => 'required_if:is_identified,1|string',
            'whistleblower_birth' => 'required_if:is_identified,1|date',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Erro nos dados da requisição', 'errors' => $validatedData->errors()], 400);
        }

        $registro = new Registro();
        $registro->deleted = 0;
        $registro->type = $request->input('type');
        $registro->message = $request->input('message');
        $registro->is_identified = $request->input('is_identified');
        $registro->created_at = now();

        if ($request->input('is_identified')) {
            $whistleblowerName = $request->input('whistleblower_name');
            $whistleblowerBirth = $request->input('whistleblower_birth');

            if (!$whistleblowerName || !$whistleblowerBirth) {
                return response()->json(['message' => 'Dados do informante incompletos'], 400);
            }

            $registro->whistleblower_name = $whistleblowerName;
            $registro->whistleblower_birth = $whistleblowerBirth;
        } else {
            if (($request->input('whistleblower_name')) || $request->input('whistleblower_birth')) {
                return response()->json(['message' => 'O capo is_identified é falso'], 400);
            }
            
            $registro->whistleblower_name = null;
            $registro->whistleblower_birth = null;
        }

        $registro->save();

        return response()->json($registro, 201);
    }

    public function update(Request $request, $id)
    {
        $registro = Registro::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $validatedData = Validator::make($request->all(), [
            'type' => 'in:denuncia,sugestao,duvida',
            'message' => 'string',
            'is_identified' => 'sometimes|boolean',
            'whistleblower_name' => 'string',
            'whistleblower_birth' => 'date',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['message' => 'Erro nos dados da requisição', 'errors' => $validatedData->errors()], 400);
        }

        if (($request->has('whistleblower_name') || $request->has('whistleblower_birth')) && (!$registro->is_identified) && !$request->has('is_identified')) {
            return response()->json(['message' => 'is_identified é false'], 400);
        }

        if ($request->has('is_identified')) {
            if (!$request->input('is_identified')) {
                $request->merge(['whistleblower_name' => null, 'whistleblower_birth' => null]);
            } elseif (!$request->has('whistleblower_name') || !$request->has('whistleblower_birth')) {
                return response()->json(['message' => 'Campos whistleblower_name e whistleblower_birth são obrigatórios quando is_identified é true'], 400);
            }
        }

        $registro->update($request->all());

        return response()->json($registro);
    }

    public function destroy($id)
    {
        $registro = Registro::find($id);

        if (!$registro) {
            return response()->json(['error' => 'Registro não encontrado'], 404);
        }

        $registro->update(['deleted' => 1]);

        return response()->json(['message' => 'Registro deletado com sucesso'], 200);
    }

}
