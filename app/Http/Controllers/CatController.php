<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatController extends Controller
{
    public function get()
    {
        $gatos = DB::select("SELECT * FROM gatos");
        return response()->json($gatos);
    }

    public function store(Request $request)
    {
        $nombre = $request->input('nombre');
        DB::insert("INSERT INTO gatos (nombre) VALUES (?)", [$nombre]);
        return response()->json(['message' => 'Gato agregado']);
    }

    public function show($id)
    {
        $gato = DB::select("SELECT * FROM gatos WHERE id = ?", [(int)$id]);

        if (empty($gato)) {
            return response()->json(['error' => 'Gato no encontrado'], 404);
        }

        return response()->json($gato[0]);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Verificar si el gato existe
        $existing = DB::select("SELECT * FROM gatos WHERE id = ?", [(int)$id]);

        if (empty($existing)) {
            return response()->json(['error' => 'Gato no encontrado'], 404);
        }

        $nombre = $request->input('nombre');
        DB::update("UPDATE gatos SET nombre = ? WHERE id = ?", [$nombre, (int)$id]);

        return response()->json(['message' => 'Gato actualizado']);
    }

    public function destroy($id)
    {
        // Verificar si el gato existe
        $existing = DB::select("SELECT * FROM gatos WHERE id = ?", [(int)$id]);

        if (empty($existing)) {
            return response()->json(['error' => 'Gato no encontrado'], 404);
        }

        DB::delete("DELETE FROM gatos WHERE id = ?", [(int)$id]);

        return response()->json(['message' => 'Gato eliminado']);
    }

}