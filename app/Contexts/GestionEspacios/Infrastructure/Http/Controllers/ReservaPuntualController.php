<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Controllers;

use App\Contexts\GestionEspacios\Application\CasosDeUso\GestionarReservas;
use App\Contexts\GestionEspacios\Domain\Exceptions\TransicionEstadoInvalidaException;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\ActualizarReservaRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Requests\CrearReservaRequest;
use App\Contexts\GestionEspacios\Infrastructure\Http\Resources\ReservaPuntualResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ReservaPuntualController extends Controller
{
    public function __construct(
        private readonly GestionarReservas $gestionarReservas,
    ) {
    }

    public function index(): JsonResponse
    {
        return ReservaPuntualResource::collection($this->gestionarReservas->listar())->response();
    }

    public function store(CrearReservaRequest $request): JsonResponse
    {
        try {
            $reserva = $this->gestionarReservas->crear(
                $request->integer('aula_id'),
                $request->filled('user_id') ? $request->integer('user_id') : null,
                $request->string('tipo')->toString(),
                $request->input('solicitante'),
                $request->string('motivo')->toString(),
                $request->input('fecha_inicio'),
                $request->input('fecha_fin'),
                $request->input('dias_semana'),
                $request->string('hora_inicio')->toString(),
                $request->string('hora_fin')->toString(),
                $request->string('estado')->toString(),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new ReservaPuntualResource($reserva))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $reserva = $this->gestionarReservas->obtener($id);

        if ($reserva === null) {
            return response()->json(['message' => 'Reserva no encontrada.'], Response::HTTP_NOT_FOUND);
        }

        return (new ReservaPuntualResource($reserva))->response();
    }

    public function update(ActualizarReservaRequest $request, int $id): JsonResponse
    {
        try {
            $reserva = $this->gestionarReservas->actualizar(
                $id,
                $request->integer('aula_id'),
                $request->filled('user_id') ? $request->integer('user_id') : null,
                $request->string('tipo')->toString(),
                $request->input('solicitante'),
                $request->string('motivo')->toString(),
                $request->input('fecha_inicio'),
                $request->input('fecha_fin'),
                $request->input('dias_semana'),
                $request->string('hora_inicio')->toString(),
                $request->string('hora_fin')->toString(),
                $request->string('estado')->toString(),
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new ReservaPuntualResource($reserva))->response();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->gestionarReservas->eliminar($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function aprobar(int $id): JsonResponse
    {
        try {
            $reserva = $this->gestionarReservas->aprobar($id);
        } catch (TransicionEstadoInvalidaException|\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new ReservaPuntualResource($reserva))->response();
    }

    public function rechazar(int $id): JsonResponse
    {
        try {
            $reserva = $this->gestionarReservas->rechazar($id);
        } catch (TransicionEstadoInvalidaException|\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new ReservaPuntualResource($reserva))->response();
    }

    public function cancelar(int $id): JsonResponse
    {
        try {
            $reserva = $this->gestionarReservas->cancelar($id);
        } catch (TransicionEstadoInvalidaException|\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return (new ReservaPuntualResource($reserva))->response();
    }
}
