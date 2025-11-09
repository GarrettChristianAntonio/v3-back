<?php

declare(strict_types=1);

namespace Src\Central\Administracion\Usuarios\Application\DTOs;

final class RespuestaUsuarioDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $nombreUsuario,
        public readonly string $fechaAsignacion,
        public readonly string $creadoEn,
        public readonly string $actualizadoEn
    ) {}

    public static function desdeModelo($usuario): self
    {
        return new self(
            id: $usuario->id,
            nombreUsuario: $usuario->nombre_usuario,
            fechaAsignacion: $usuario->fecha_asignacion?->toISOString() ?? '',
            creadoEn: $usuario->created_at?->toISOString() ?? '',
            actualizadoEn: $usuario->updated_at?->toISOString() ?? ''
        );
    }

    public function aArray(): array
    {
        return [
            'id' => $this->id,
            'nombre_usuario' => $this->nombreUsuario,
            'fecha_asignacion' => $this->fechaAsignacion,
            'creado_en' => $this->creadoEn,
            'actualizado_en' => $this->actualizadoEn,
        ];
    }
}
