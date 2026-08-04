<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Eloquent;

use Database\Factories\EquipamientoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EquipamientoEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'equipamientos';

    protected $fillable = [
        'nombre',
    ];

    protected static function newFactory(): EquipamientoFactory
    {
        return EquipamientoFactory::new();
    }

    public function aulas(): BelongsToMany
    {
        return $this->belongsToMany(
            AulaEloquentModel::class,
            'aula_equipamiento',
            'equipamiento_id',
            'aula_id'
        )->withPivot('cantidad')->withTimestamps(['created_at']);
    }
}
