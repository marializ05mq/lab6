<?php

namespace App\Http\Controllers;
use App\Models\Dog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DogController extends Controller
{
    public function get(Request $request)
    {
        $limit = $request->input('limit');

        if ($limit) {
            $dogs = Dog::limit((int)$limit)->get();
        } else {
            $dogs = Dog::all();
        }

        return response()->json($dogs);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dog = Dog::create($request->only('nombre'));
        return response()->json($dog, 201);
    }
    public function show($id)
    {
        $dog = Dog::find($id);

        if (!$dog) {
            return response()->json(['error' => 'Perro no encontrado'], 404);
        }

        return response()->json($dog);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $dog = Dog::find($id);

        if (!$dog) {
            return response()->json(['error' => 'Perro no encontrado'], 404);
        }

        $dog->nombre = $request->input('nombre');
        $dog->save();

        return response()->json(['message' => 'Perro actualizado', 'dog' => $dog]);
    }

    public function destroy($id)
    {
        $dog = Dog::find($id);

        if (!$dog) {
            return response()->json(['error' => 'Perro no encontrado'], 404);
        }

        $dog->delete();

        return response()->json(['message' => 'Perro eliminado']);
    }

}
