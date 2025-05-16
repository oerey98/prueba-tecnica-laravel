<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ClientesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Cliente::select('nombre', 'apellido', 'cedula', 'departamento', 'ciudad', 'celular', 'email', 'created_at')->orderByDesc('created_at')
            ->where('habeas_data', true)
            ->get()
            ->map(function ($cliente) {
                $cliente->fecha = Carbon::parse($cliente->created_at)->format('Y-m-d H:i');
                unset($cliente->created_at);
                return $cliente;
            });
    }

    public function headings(): array
    {
        return ['Nombre', 'Apellido', 'Cédula', 'Departamento', 'Ciudad', 'Celular', 'Correo Electrónico', 'Fecha y Hora de Registro'];
    }
}
