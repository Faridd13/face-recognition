<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .float-animation {
                animation: float 3s ease-in-out infinite;
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(249, 115, 22, 0.3); }
                50% { box-shadow: 0 0 40px rgba(249, 115, 22, 0.6); }
            }
            .pulse-glow {
                animation: pulse-glow 2s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gradient-to-br from-slate-900 via-orange-900 to-slate-900 min-h-screen">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
            <!-- Logo -->
            <div class="mb-8 float-animation">
                <a href="/" class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-2xl pulse-glow">
                        <svg class="w-12 h-12 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Tech grid background -->
                            <rect x="2" y="2" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="14" y="2" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="2" y="14" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <rect x="14" y="14" width="8" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <!-- Face outline -->
                            <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="1.5" fill="rgba(255,255,255,0.2)"/>
                            <!-- Eyes -->
                            <circle cx="10" cy="11" r="0.8" fill="currentColor"/>
                            <circle cx="14" cy="11" r="0.8" fill="currentColor"/>
                            <!-- Smile -->
                            <path d="M9 14C10 15.5 14 15.5 15 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <!-- Scan lines -->
                            <line x1="6" y1="7" x2="18" y2="7" stroke="currentColor" stroke-width="1" opacity="0.7"/>
                            <line x1="6" y1="17" x2="18" y2="17" stroke="currentColor" stroke-width="1" opacity="0.7"/>
                        </svg>
                    </div>
                </a>
                <h1 class="text-center text-3xl font-bold text-white mt-4">FaceAttendance</h1>
                <p class="text-center text-orange-300 text-sm mt-1">Sistem Presensi Wajah Berbasis AI</p>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="mt-8 text-gray-400 text-sm">© 2026 FaceAttendance. All rights reserved.</p>
        </div>
    </body>
</html>