<!DOCTYPE html>
<html lang="es" x-data="app()">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - Lista de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="flex justify-between mb-6 items-center space-x-4">
        <a href="{{ route('home.export') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Exportar Excel</a>
        <button @click="seleccionarGanador()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Seleccionar Ganador</button>

        <!-- Botón Logout -->
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
           Cerrar Sesión
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr class="bg-blue-600 text-white text-left">
                <th class="py-3 px-4">Nombre</th>
                <th class="py-3 px-4">Apellido</th>
                <th class="py-3 px-4">Cédula</th>
                <th class="py-3 px-4">Departamento</th>
                <th class="py-3 px-4">Ciudad</th>
                <th class="py-3 px-4">Celular</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
            <tr class="border-b hover:bg-gray-100">
                <td class="py-2 px-4">{{ $cliente->nombre }}</td>
                <td class="py-2 px-4">{{ $cliente->apellido }}</td>
                <td class="py-2 px-4">{{ $cliente->cedula }}</td>
                <td class="py-2 px-4">{{ $cliente->departamento }}</td>
                <td class="py-2 px-4">{{ $cliente->ciudad }}</td>
                <td class="py-2 px-4">{{ $cliente->celular }}</td>
                <td class="py-2 px-4">{{ $cliente->email }}</td>
                <td class="py-2 px-4">{{ $cliente->fecha }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Mejorado -->
    <div
      x-show="modalOpen"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 transition-opacity duration-300"
      style="display: none;"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
    >
      <div
        class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 relative"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="scale-90 opacity-0"
        x-transition:enter-end="scale-100 opacity-100"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="scale-100 opacity-100"
        x-transition:leave-end="scale-90 opacity-0"
      >
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 border-b pb-3">Ganador Seleccionado</h2>
        <template x-if="ganador">
            <div class="space-y-3 text-gray-700">
                <p><span class="font-semibold">Nombre:</span> <span x-text="ganador.nombre"></span></p>
                <p><span class="font-semibold">Apellido:</span> <span x-text="ganador.apellido"></span></p>
                <p><span class="font-semibold">Cédula:</span> <span x-text="ganador.cedula"></span></p>
                <p><span class="font-semibold">Departamento:</span> <span x-text="ganador.departamento"></span></p>
                <p><span class="font-semibold">Ciudad:</span> <span x-text="ganador.ciudad"></span></p>
                <p><span class="font-semibold">Celular:</span> <span x-text="ganador.celular"></span></p>
                <p><span class="font-semibold">Email:</span> <span x-text="ganador.email"></span></p>
            </div>
        </template>
        <template x-if="error">
            <p class="text-red-600 font-semibold mt-4" x-text="error"></p>
        </template>
        <button
          @click="modalOpen = false"
          class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition-colors"
          aria-label="Cerrar modal"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

<script>
    function app() {
        return {
            modalOpen: false,
            ganador: null,
            error: null,

            seleccionarGanador() {
                this.error = null;
                fetch("{{ route('home.seleccionarGanador') }}")
                    .then(response => {
                        if (!response.ok) throw response;
                        return response.json();
                    })
                    .then(data => {
                        this.ganador = data;
                        this.modalOpen = true;
                    })
                    .catch(async err => {
                        if (err.json) {
                            const errorData = await err.json();
                            this.error = errorData.error || 'Error desconocido';
                        } else {
                            this.error = 'Error en la solicitud';
                        }
                        this.modalOpen = true;
                    });
            }
        }
    }
</script>

</body>
</html>
