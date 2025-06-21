<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

/**
 * @OA\Tag(
 *     name="Categorías",
 *     description="Gestión de categorías de productos"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categorías"},
     *     summary="Crear una nueva categoría",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoría creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::create($request->only(['name', 'description']));

            return response()->json([
                'message'  => 'Categoría creada exitosamente',
                'category' => $category
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al crear la categoría',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Categorías"},
     *     summary="Actualizar una categoría existente",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría actualizada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $category = Category::findOrFail($id);
            $category->update($request->only(['name', 'description']));

            return response()->json([
                'message'  => 'Categoría actualizada correctamente',
                'category' => $category
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoría no encontrada'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar la categoría',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Categorías"},
     *     summary="Eliminar una categoría",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la categoría a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoría eliminada correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoría no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json([
                'message' => 'Categoría eliminada correctamente'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Categoría no encontrada'
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoría',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
