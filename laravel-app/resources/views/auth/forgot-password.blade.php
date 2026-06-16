<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Lupa Password?</h2>
        <p class="text-gray-600 mb-8">Tidak masalah! Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan reset password yang memungkinkan Anda memilih yang baru.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-2" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <x-text-input id="email" class="block pl-10 mt-1 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all" type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan email Anda" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <a class="underline text-sm text-gray-600 hover:text-orange-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors" href="{{ route('login') }}">
                    {{ __('Kembali ke login') }}
                </a>
                <x-primary-button class="ml-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 px-8 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all">
                    {{ __('Kirim Tautan Reset') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
