<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Controllers;

use App\Contexts\GestionEspacios\Application\CasosDeUso\GestionarEspacios;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\ActualizarEspacioRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\AsignarEquipamientoRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\CrearEspacioRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Resources\EspacioResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class EspacioController extends Controller
{
    public function __construct(
        private readonly GestionarEspacios $gestionarEspacios,
    ) {
    }

    public function index(): JsonResponse
    {
        return EspacioResource::collection($this->gestionarEspacios->listar())->response();
    }

    public function store(CrearEspacioRequest $request): JsonResponse
    {
        try {
            $espacio = $this->gestionarEspacios->crear(
                $request->integer('recinto_id'),
                $request->string('nombre')->toString(),
                $request->filled('piso') ? $request->string('piso')->toString() : null,
                $request->string('tipo')->toString(),
                $request->filled('capacidad') ? $request->integer('capacidad') : null,
                $request->input('no_disponible_desde'),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new EspacioResource($espacio))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $espacio = $this->gestionarEspacios->obtener($id);

        if ($espacio === null) {
            return response()->json(['message' => 'Espacio no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        return (new EspacioResource($espacio))->response();
    }

    public function update(ActualizarEspacioRequest $request, int $id): JsonResponse
    {
        try {
            $espacio = $this->gestionarEspacios->actualizar(
                $id,
                $request->integer('recinto_id'),
                $request->string('nombre')->toString(),
                $request->filled('piso') ? $request->string('piso')->toString() : null,
                $request->string('tipo')->toString(),
                $request->filled('capacidad') ? $request->integer('capacidad') : null,
                $request->input('no_disponible_desde'),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new EspacioResource($espacio))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->gestionarEspacios->eliminar($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function asignarEquipamiento(AsignarEquipamientoRequest $request, int $id): JsonResponse
    {
        $this->gestionarEspacios->asignarEquipamiento(
            $id,
            $request->integer('equipamiento_id'),
            $request->integer('cantidad'),
        );

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function quitarEquipamiento(int $id, int $equipamientoId): JsonResponse
    {
        $this->gestionarEspacios->quitarEquipamiento($id, $equipamientoId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
