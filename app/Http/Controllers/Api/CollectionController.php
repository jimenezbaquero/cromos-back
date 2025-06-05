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
    
    /**
     * @OA\Get(
     *     path="/api/subscribedCollections",
     *     summary="Obtener todas las colecciones del usuario",
     *     description="Retorna una lista de todas las colecciones a las que el usuario se ha suscrito.",
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
    public function getSubscribedCollections(Request $request): \Illuminate\Http\JsonResponse {
        $user = $request->user();
        $collections = $user->collections;
        
        
        return response()->json(['collections' => $collections], 200);
    }
    
    /**
     * @OA\Get(
     *     path="/api/cards/{collection}",
     *     summary="Obtener todos los cromos de una colección",
     *     description="Retorna una lista de todos los cromos de una colección.",
     *     tags={"Colecciones"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="collection",
     *         in="path",
     *         description="ID de la colección",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de cromos obtenido con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="cards",
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

    public function getSCardsCollection(Collection $collection): \Illuminate\Http\JsonResponse {
        $cards = $collection->cards;
        return response()->json(['cards' => $cards], 200);
    }
}
