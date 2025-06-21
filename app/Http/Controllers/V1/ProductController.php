<?php

namespace App\Http\Controllers\V1;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\ProductCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Info(
 *     title="Inventario API",
 *     version="1.0.0",
 *     description="API RESTful para la gestión de productos",
 *     @OA\Contact(email="atsgula@gmail.com")
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local de desarrollo"
 * )
 * @OA\Tag(
 *     name="Productos",
 *     description="Gestión de productos del inventario"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Obtener todos los productos",
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de productos"
     *     )
     * )
     */
    public function index()
    {
        try {
            $products = Product::with('category')->paginate(10);
            return response()->json(new ProductCollection($products));
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Mostrar un producto específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            return new ProductResource($product);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al mostrar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Crear un nuevo producto",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category_id", "name", "price", "stock"},
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente"
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::create($request->all());

            return response()->json([
                'message' => 'Producto creado exitosamente',
                'product' => new ProductResource($product)
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al crear el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Actualizar un producto",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="category_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());

            return response()->json([
                'message' => 'Producto actualizado correctamente',
                'product' => new ProductResource($product)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Eliminar un producto",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del producto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado correctamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Error al eliminar el producto',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
