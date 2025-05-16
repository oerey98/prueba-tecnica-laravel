<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientesExport;

class ClienteController extends Controller
{
    public function index()
    {
        $departamentos = $this->getDepartamentos();

        // Obtener ganador si hay al menos 5 clientes
        $ganador = null;
        if (Cliente::count() >= 5) {
            $ganador = Cliente::inRandomOrder()->first();
        }

        return view('landing', compact('departamentos', 'ganador'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|alpha|max:255',
            'apellido' => 'required|alpha|max:255',
            'cedula' => 'required|numeric|unique:clientes,cedula',
            'departamento' => 'required|string',
            'ciudad' => 'required|string',
            'celular' => 'required|numeric',
            'email' => 'required|email|unique:clientes,email',
            'habeas_data' => 'accepted',
        ], [
            'habeas_data.accepted' => 'Debes aceptar el tratamiento de datos',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cedula' => $request->cedula,
            'departamento' => $request->departamento,
            'ciudad' => $request->ciudad,
            'celular' => $request->celular,
            'email' => $request->email,
            'habeas_data' => true,
        ]);

        return redirect()->route('landing')->with('success', 'Registro exitoso!');
    }

    public function export()
    {
        return Excel::download(new ClientesExport, 'clientes.xlsx');
    }

    // Lista simple departamentos y ciudades (puedes ampliar)
    private function getDepartamentos()
    {
        return [
            'Bogotá' => ['Bogotá'],
            'Antioquia' => ['Medellín', 'Envigado', 'Rionegro'],
            'Cundinamarca' => ['Soacha', 'Chía', 'Zipaquirá'],
            // Agrega más departamentos y ciudades
        ];
    }
}
