<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Controllers;

use App\Contexts\GestionEspacios\Application\CasosDeUso\GestionarEquipamientos;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\ActualizarEquipamientoRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\CrearEquipamientoRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Resources\EquipamientoResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EquipamientoController extends Controller
{
    public function __construct(
        private readonly GestionarEquipamientos $gestionarEquipamientos,
    ) {
    }

    public function index(): JsonResponse
    {
        return EquipamientoResource::collection($this->gestionarEquipamientos->listar())->response();
    }

    public function store(CrearEquipamientoRequest $request): JsonResponse
    {
        try {
            $equipamiento = $this->gestionarEquipamientos->crear(
                $request->string('nombre')->toString(),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new EquipamientoResource($equipamiento))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $equipamiento = $this->gestionarEquipamientos->obtener($id);

        if ($equipamiento === null) {
            return response()->json(['message' => 'Equipamiento no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return (new EquipamientoResource($equipamiento))->response();
    }

    public function update(ActualizarEquipamientoRequest $request, int $id): JsonResponse
    {
        try {
            $equipamiento = $this->gestionarEquipamientos->actualizar(
                $id,
                $request->string('nombre')->toString(),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new EquipamientoResource($equipamiento))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->gestionarEquipamientos->eliminar($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
