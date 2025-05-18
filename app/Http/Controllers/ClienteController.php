<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Exports\ClientesExport;
use App\Models\Concurso;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    public function landing()
    {
        $departamentos = $this->getDepartamentos();
        $ganador = Concurso::with('cliente')->latest()->first();
        return view('landing', compact('departamentos', 'ganador'));;
    }

    public function index()
    {
        $clientes = Cliente::all()->sortByDesc('created_at');
        foreach ($clientes as $cliente) {
            $cliente->fecha = Carbon::parse($cliente->created_at)->format('Y-m-d h:i A');
        }

        return view('home', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'],
            'apellido' => ['required', 'regex:/^[\pL\s]+$/u', 'max:255'],
            'cedula' => 'required|numeric|unique:clientes,cedula',
            'departamento' => 'required|string',
            'ciudad' => 'required|string',
            'celular' => 'required|numeric',
            'email' => 'required|email|unique:clientes,email',
            'habeas_data' => 'accepted',
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

    public function seleccionarGanador()
    {
        $clientesCount = Cliente::count();

        if ($clientesCount < 5) {
            return response()->json(['error' => 'Se requieren al menos 5 clientes para seleccionar un ganador.'], 400);
        }

        $ganador = Cliente::inRandomOrder()->first();
        Concurso::create([
            'id_cliente' => $ganador->id
        ]);

        return response()->json($ganador);
    }

    // Lista simple departamentos y ciudade
    private function getDepartamentos()
    {
        $url = 'https://www.datos.gov.co/resource/xdk5-pm3f.json';
        $json = file_get_contents($url);
        if ($json === false) {
            return [];
        }

        $data = json_decode($json, true);
        if ($data === null) {
            return [];
        }

        $departamentos = [];

        foreach ($data as $item) {
            $departamento = $item['departamento'] ?? null;
            $municipio = $item['municipio'] ?? null;

            if ($departamento && $municipio) {
                // Inicializar array del departamento si no existe
                if (!isset($departamentos[$departamento])) {
                    $departamentos[$departamento] = [];
                }
                // Agregar municipio si no está repetido
                if (!in_array($municipio, $departamentos[$departamento])) {
                    $departamentos[$departamento][] = $municipio;
                }
            }
        }

        foreach ($departamentos as &$municipios) {
            sort($municipios);
        }

        // Ordenar departamentos alfabéticamente
        ksort($departamentos);

        return $departamentos;
    }
}
