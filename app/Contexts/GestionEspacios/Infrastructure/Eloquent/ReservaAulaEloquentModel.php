<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Eloquent;

use App\Models\User;
use Database\Factories\ReservaAulaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservaAulaEloquentModel extends Model
{
    use HasFactory;

    protected $table = 'reservas_aulas';

    protected $fillable = [
        'aula_id',
        'user_id',
        'tipo',
        'solicitante',
        'motivo',
        'fecha_inicio',
        'fecha_fin',
        'dias_semana',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected static function newFactory(): ReservaAulaFactory
    {
        return ReservaAulaFactory::new();
    }

    public function aula(): BelongsTo
    {
        return $this->belongsTo(AulaEloquentModel::class, 'aula_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
