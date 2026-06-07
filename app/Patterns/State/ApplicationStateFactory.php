<?php

namespace App\Patterns\State;

class ApplicationStateFactory
{
    public static function make(string $status): ApplicationState
    {
        return match ($status) {
            'recebido' => new RecebidoState(),
            'em_analise' => new EmAnaliseState(),
            'entrevista' => new EntrevistaState(),
            'aprovado' => new AprovadoState(),
            'rejeitado' => new RejeitadoState(),
            default => throw new \InvalidArgumentException("Status desconhecido: $status"),
        };
    }
}
