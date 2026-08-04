<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Eloquent;

use Database\Factories\RecintoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecintoEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'recintos';

    protected $fillable = [
        'nombre',
        'es_propio',
        'activo',
    ];

    protected $casts = [
        'es_propio' => 'boolean',
        'activo' => 'boolean',
    ];

    protected static function newFactory(): RecintoFactory
    {
        return RecintoFactory::new();
    }

    public function aulas(): HasMany
    {
        return $this->hasMany(AulaEloquentModel::class, 'recinto_id');
    }
}
