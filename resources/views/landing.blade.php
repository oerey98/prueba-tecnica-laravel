<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Landing Automóviles Mejorado con Tailwind</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body
  class="min-h-screen bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center p-6 gap-8"
  style="background-image: url('https://wallpapers.com/images/hd/black-car-4k-qgapc9ubvarvfmc7.jpg');"
>

  <!-- Botón "Ingresar" arriba derecha -->
  <a href="{{ route('login') }}" 
     class="absolute top-6 right-6 px-5 py-2 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
  >
    Ingresar
  </a>

  @if ($ganador)
    <div class="p-6 bg-yellow-100 border border-yellow-400 rounded-xl max-w-md text-center">
      <h2 class="text-xl font-bold text-yellow-800 mb-2">¡Tenemos Ganador!</h2>
      <p class="text-yellow-700 mb-1"><strong>Nombre:</strong> {{ $ganador->cliente->nombre }}</p>
      <p class="text-yellow-700 mb-1"><strong>Ciudad:</strong> {{ $ganador->cliente->ciudad }}</p>
      <p class="text-yellow-700"><strong>Fecha Sorteo:</strong> {{ \Carbon\Carbon::parse($ganador->fecha_ganador)->format('d/m/Y') }}</p>
    </div>
  @endif

  <!-- Formulario con fondo blanco semitransparente -->
  <form
    x-data="formData()"
    action="{{ route('registrar') }}"
    method="POST"
    novalidate
    class="bg-white bg-opacity-90 p-8 rounded-3xl shadow-xl w-full max-w-md"
  >
    @csrf

    <h1 class="text-3xl font-extrabold mb-8 text-blue-700 text-center">
      Registro Clientes Automóviles Bogotá
    </h1>

    @if(session('success'))
      <div class="mb-4 p-3 bg-green-200 text-green-900 rounded">
        {{ session('success') }}
      </div>
    @endif

    <label for="nombre" class="block mb-2 font-semibold text-gray-700">Nombre</label>
    <input
      type="text"
      id="nombre"
      name="nombre"
      placeholder="Ingrese su nombre"
      value="{{ old('nombre') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    />
    @error('nombre')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="apellido" class="block mb-2 font-semibold text-gray-700">Apellido</label>
    <input
      type="text"
      id="apellido"
      name="apellido"
      placeholder="Ingrese su apellido"
      value="{{ old('apellido') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    />
    @error('apellido')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="cedula" class="block mb-2 font-semibold text-gray-700">Cédula</label>
    <input
      type="text"
      id="cedula"
      name="cedula"
      placeholder="Número de cédula"
      value="{{ old('cedula') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    />
    @error('cedula')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="celular" class="block mb-2 font-semibold text-gray-700">Celular</label>
    <input
      type="text"
      id="celular"
      name="celular"
      placeholder="Número celular"
      value="{{ old('celular') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    />
    @error('celular')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="email" class="block mb-2 font-semibold text-gray-700">Email</label>
    <input
      type="email"
      id="email"
      name="email"
      placeholder="Correo electrónico"
      value="{{ old('email') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    />
    @error('email')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="departamento" class="block mb-2 font-semibold text-gray-700">Departamento</label>
    <select
      id="departamento"
      name="departamento"
      x-model="departamento"
      required
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-1 text-gray-700 bg-white focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
    >
      <option value="" disabled selected>Seleccione Departamento</option>
      <template x-for="[nombre, ciudades] in Object.entries(departamentos)" :key="nombre">
        <option :value="nombre" x-text="nombre" :selected="nombre === '{{ old('departamento') }}'"></option>
      </template>
    </select>
    @error('departamento')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="ciudad" class="block mb-2 font-semibold text-gray-700">Ciudad</label>
    <select
      id="ciudad"
      name="ciudad"
      x-model="ciudad"
      required
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-6 text-gray-700 bg-white focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
      :disabled="!departamento || ciudades.length === 0"
    >
      <option value="" disabled selected>Seleccione Ciudad</option>
      <template x-for="ciudadItem in ciudades" :key="ciudadItem">
        <option :value="ciudadItem" x-text="ciudadItem" :selected="ciudadItem === '{{ old('ciudad') }}'"></option>
      </template>
    </select>
    @error('ciudad')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label class="inline-flex items-center mb-8">
      <input
        type="checkbox"
        name="habeas_data"
        value="1"
        {{ old('habeas_data') ? 'checked' : '' }}
        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
        required
      />
      <span class="ml-2 text-gray-700">Acepto el tratamiento de datos (Habeas Data)</span>
    </label>
    @error('habeas_data')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <button
      type="submit"
      class="w-full rounded-2xl bg-gradient-to-r from-purple-600 to-green-500 hover:from-green-500 hover:to-purple-600 text-white font-extrabold py-3 shadow-lg transition"
    >
      Registrar
    </button>
  </form>

  <script>
    function formData() {
      return {
        departamento: '{{ old('departamento') ?? '' }}',
        ciudad: '{{ old('ciudad') ?? '' }}',
        departamentos: @json($departamentos),
        get ciudades() {
          return this.departamento ? this.departamentos[this.departamento] || [] : [];
        }
      }
    }
  </script>

</body>
</html>
