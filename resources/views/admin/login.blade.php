<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="w-[980px] max-w-[92vw] border-[3px] border-black bg-white">
            <div class="px-10 sm:px-16 py-10 sm:py-14">
                <h1 class="text-center text-3xl sm:text-4xl font-extrabold tracking-wide">
                    LOGIN
                </h1>

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-10 sm:mt-14">
                    @csrf

                    <div class="grid grid-cols-[90px_1fr] sm:grid-cols-[110px_1fr] gap-x-10 sm:gap-x-14 gap-y-10 items-center">
                        {{-- Row: Username --}}
                        <div class="flex justify-center">
                            {{-- User icon (outline) --}}
                            <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" stroke="black" stroke-width="2.5">
                                <circle cx="32" cy="20" r="10"></circle>
                                <path d="M12 54c4-12 16-16 20-16s16 4 20 16" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div>
                            <label for="username" class="sr-only">Username</label>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                value="{{ old('username') }}"
                                autocomplete="username"
                                class="w-full h-14 sm:h-16 bg-black text-white px-5 outline-none focus:ring-4 focus:ring-black/20"
                                placeholder=""
                                required
                            >
                            @error('username')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Row: Password --}}
                        <div class="flex justify-center">
                            {{-- Lock icon (outline) --}}
                            <svg class="h-14 w-14" viewBox="0 0 64 64" fill="none" stroke="black" stroke-width="2.5">
                                <rect x="14" y="28" width="36" height="28" rx="2"></rect>
                                <path d="M22 28v-6a10 10 0 0 1 20 0v6" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                class="w-full h-14 sm:h-16 bg-black text-white px-5 outline-none focus:ring-4 focus:ring-black/20"
                                placeholder=""
                                required
                            >
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <button
                            type="submit"
                            class="bg-gray-300 px-10 sm:px-14 py-3 text-xl sm:text-2xl font-semibold border border-gray-300 hover:border-black transition"
                        >
                            Masuk
                        </button>
                    </div>

                    @if (session('error'))
                        <p class="mt-6 text-red-600">{{ session('error') }}</p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</body>
</html>
