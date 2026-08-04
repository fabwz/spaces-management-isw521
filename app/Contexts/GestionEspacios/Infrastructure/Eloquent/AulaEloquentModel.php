<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Eloquent;

use Database\Factories\AulaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AulaEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'aulas';

    protected $fillable = [
        'recinto_id',
        'nombre',
        'piso',
        'tipo',
        'capacidad',
        'no_disponible_desde',
    ];

    protected $casts = [
        'capacidad' => 'integer',
        'no_disponible_desde' => 'date',
    ];

    protected static function newFactory(): AulaFactory
    {
        return AulaFactory::new();
    }

    public function recinto(): BelongsTo
    {
        return $this->belongsTo(RecintoEloquentModel::class, 'recinto_id');
    }

    public function equipamientos(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipamientoEloquentModel::class,
            'aula_equipamiento',
            'aula_id',
            'equipamiento_id'
        )->withPivot('cantidad')->withTimestamps(['created_at']);
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(ReservaAulaEloquentModel::class, 'aula_id');
    }
}
