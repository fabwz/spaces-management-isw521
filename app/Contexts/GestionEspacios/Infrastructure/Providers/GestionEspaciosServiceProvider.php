<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Providers;

use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEquipamientos;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEspacios;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioRecintos;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioReservas;
use App\Contexts\GestionEspacios\Infrastructure\Repositorios\RepositorioEquipamientosEloquent;
use App\Contexts\GestionEspacios\Infrastructure\Repositorios\RepositorioEspaciosEloquent;
use App\Contexts\GestionEspacios\Infrastructure\Repositorios\RepositorioRecintosEloquent;
use App\Contexts\GestionEspacios\Infrastructure\Repositorios\RepositorioReservasEloquent;
use Illuminate\Support\ServiceProvider;

class GestionEspaciosServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RepositorioEspacios::class, RepositorioEspaciosEloquent::class);
        $this->app->bind(RepositorioRecintos::class, RepositorioRecintosEloquent::class);
        $this->app->bind(RepositorioEquipamientos::class, RepositorioEquipamientosEloquent::class);
        $this->app->bind(RepositorioReservas::class, RepositorioReservasEloquent::class);
    }
}
