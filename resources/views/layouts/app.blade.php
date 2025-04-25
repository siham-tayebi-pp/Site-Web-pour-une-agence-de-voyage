<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VoyCom - Agence de Voyage')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    {{-- <x-header/> --}}

    <!-- Contenu -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">VoyCom</h3>
                    <p>Votre agence de voyage de confiance depuis 2010.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Liens Utiles</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-blue-400">Aide</a></li>
                        <li><a href="#" class="hover:text-blue-400">Conditions</a></li>
                        <li><a href="#" class="hover:text-blue-400">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li>contact@voycom.com</li>
                        <li>+33 1 23 45 67 89</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Réseaux Sociaux</h4>
                    <div class="flex space-x-4">
                        <a href="#"><i class="fab fa-facebook-f hover:text-blue-400"></i></a>
                        <a href="#"><i class="fab fa-twitter hover:text-blue-400"></i></a>
                        <a href="#"><i class="fab fa-instagram hover:text-blue-400"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; 2023 VoyCom. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>