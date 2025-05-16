<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Automóviles Bogotá</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body
  class="min-h-screen bg-cover bg-center bg-no-repeat flex items-center justify-center p-6 relative"
  style="background-image: url('https://wallpapers.com/images/hd/black-car-4k-qgapc9ubvarvfmc7.jpg');"
>

  <!-- Botón Home arriba derecha -->
  <a
    href="{{ route('landing') }}"
    class="absolute top-6 left-6 px-5 py-2 rounded-xl bg-gray-700 text-white font-semibold hover:bg-gray-800 transition"
  >
    Home
  </a>

  <form
    action="{{ route('login') }}"
    method="POST"
    novalidate
    class="bg-white bg-opacity-90 p-8 rounded-3xl shadow-xl w-full max-w-md"
  >
    @csrf

    <h1 class="text-3xl font-extrabold mb-8 text-blue-700 text-center">
      Iniciar Sesión
    </h1>

    @if(session('error'))
      <div class="mb-4 p-3 bg-red-200 text-red-900 rounded">
        {{ session('error') }}
      </div>
    @endif

    <label for="email" class="block mb-2 font-semibold text-gray-700">Email</label>
    <input
      type="email"
      id="email"
      name="email"
      placeholder="Ingrese su correo electrónico"
      value="{{ old('email') }}"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-4 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
      required
      autofocus
    />
    @error('email')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <label for="password" class="block mb-2 font-semibold text-gray-700">Contraseña</label>
    <input
      type="password"
      id="password"
      name="password"
      placeholder="Ingrese su contraseña"
      class="w-full rounded-xl border-2 border-blue-300 px-4 py-3 mb-6 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-500 transition"
      required
    />
    @error('password')
      <p class="text-red-600 text-sm mb-4">{{ $message }}</p>
    @enderror

    <button
      type="submit"
      class="w-full rounded-2xl bg-gradient-to-r from-purple-600 to-green-500 hover:from-green-500 hover:to-purple-600 text-white font-extrabold py-3 shadow-lg transition"
    >
      Entrar
    </button>
  </form>

</body>
</html>
