<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VoyCom - Découvrez le Monde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    

    <!-- Hero Section -->
    <section class="relative bg-blue-900 text-white py-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Explorez des Destinations Inoubliables</h1>
            <p class="text-xl mb-8">Trouvez votre voyage parfait avec nos offres exclusives</p>
            <a href="/voyages" class="bg-white text-blue-900 font-bold px-8 py-3 rounded-lg hover:bg-gray-100">Voir les offres</a>
        </div>
    </section>

    <!-- Destinations Populaires -->
    <section class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Nos Destinations Phares</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Carte 1 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <img src="https://source.unsplash.com/random/600x400?paris" alt="Paris" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Paris, France</h3>
                    <p class="text-gray-600 mb-4">Découvrez la ville de l'amour avec nos forfaits exclusifs.</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">€799</span>
                        <a href="/voyages/1" class="text-blue-600 hover:text-blue-800">Voir détails →</a>
                    </div>
                </div>
            </div>
            <!-- Carte 2 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <img src="https://source.unsplash.com/random/600x400?tokyo" alt="Tokyo" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">Tokyo, Japon</h3>
                    <p class="text-gray-600 mb-4">Plongez dans la culture futuriste du Japon.</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">€1299</span>
                        <a href="/voyages/2" class="text-blue-600 hover:text-blue-800">Voir détails →</a>
                    </div>
                </div>
            </div>
            <!-- Carte 3 -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <img src="https://source.unsplash.com/random/600x400?new-york" alt="New York" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2">New York, USA</h3>
                    <p class="text-gray-600 mb-4">La ville qui ne dort jamais vous attend.</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">€1099</span>
                        <a href="/voyages/3" class="text-blue-600 hover:text-blue-800">Voir détails →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        </div>
    </footer>
</body>
</html>