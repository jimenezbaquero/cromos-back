<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Collection",
 *     description="Endpoints para obtener datos de colecciones, y suscribirse"
 * )
 */
class CollectionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/collections",
     *     summary="Obtener todas las colecciones",
     *     description="Retorna una lista de todas las colecciones disponibles.",
     *     tags={"Colecciones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de colecciones obtenido con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="collections",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Coleccion")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function getCollections(Request $request): \Illuminate\Http\JsonResponse {
        $collections = Collection::all();


        return response()->json(['collections' => $collections], 200);
    }

}
