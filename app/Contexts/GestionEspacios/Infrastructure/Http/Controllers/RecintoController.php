<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Controllers;

use App\Contexts\GestionEspacios\Application\CasosDeUso\GestionarRecintos;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\ActualizarRecintoRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\CrearRecintoRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Resources\RecintoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RecintoController extends Controller
{
    public function __construct(
        private readonly GestionarRecintos $gestionarRecintos,
    ) {
    }

    public function index(): JsonResponse
    {
        return RecintoResource::collection($this->gestionarRecintos->listar())->response();
    }

    public function store(CrearRecintoRequest $request): JsonResponse
    {
        try {
            $recinto = $this->gestionarRecintos->crear(
                $request->string('nombre')->toString(),
                $request->boolean('es_propio'),
                $request->boolean('activo'),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new RecintoResource($recinto))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $recinto = $this->gestionarRecintos->obtener($id);

        if ($recinto === null) {
            return response()->json(['message' => 'Recinto no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return (new RecintoResource($recinto))->response();
    }

    public function update(ActualizarRecintoRequest $request, int $id): JsonResponse
    {
        try {
            $recinto = $this->gestionarRecintos->actualizar(
                $id,
                $request->string('nombre')->toString(),
                $request->boolean('es_propio'),
                $request->boolean('activo'),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new RecintoResource($recinto))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->gestionarRecintos->eliminar($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
