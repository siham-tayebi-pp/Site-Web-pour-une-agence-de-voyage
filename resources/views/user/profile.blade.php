@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar -->
        <div class="md:w-1/4">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 rounded-full bg-gray-200 mx-auto mb-4 overflow-hidden">
                        <img src="https://source.unsplash.com/random/200x200?portrait" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <h2 class="text-xl font-bold">Jean Dupont</h2>
                    <p class="text-gray-600">Membre depuis 2022</p>
                </div>

                <nav class="space-y-2">
                    <a href="#" class="flex items-center space-x-3 p-3 bg-blue-50 text-blue-600 rounded-lg">
                        <i class="fas fa-user-circle w-5"></i>
                        <span>Profil</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-suitcase w-5"></i>
                        <span>Mes réservations</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-heart w-5"></i>
                        <span>Favoris</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg">
                        <i class="fas fa-cog w-5"></i>
                        <span>Paramètres</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg text-red-500">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Déconnexion</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:w-3/4">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <h1 class="text-2xl font-bold mb-6">Mon Profil</h1>

                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 mb-2">Prénom</label>
                                <input type="text" value="Jean" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Nom</label>
                                <input type="text" value="Dupont" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" value="jean.dupont@example.com" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" value="+33 6 12 34 56 78" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Date de naissance</label>
                            <input type="date" value="1985-05-15" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-medium mb-4">Changer de mot de passe</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 mb-2">Mot de passe actuel</label>
                                    <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-gray-700 mb-2">Nouveau mot de passe</label>
                                    <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-gray-700 mb-2">Confirmer le nouveau mot de passe</label>
                                    <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Mes Réservations -->
            <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <h2 class="text-2xl font-bold mb-6">Mes Réservations</h2>
                    
                    <div class="space-y-6">
                        <!-- Réservation 1 -->
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="font-bold text-lg">Bali, Indonésie</h3>
                                    <p class="text-gray-600">15/06/2023 - 29/06/2023</p>
                                    <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Confirmée</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold">€1499</p>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Voir détails</a>
                                </div>
                            </div>
                        </div>

                        <!-- Réservation 2 -->
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                <div class="mb-4 md:mb-0">
                                    <h3 class="font-bold text-lg">Rome, Italie</h3>
                                    <p class="text-gray-600">22/05/2023 - 29/05/2023</p>
                                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">En attente</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold">€699</p>
                                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Voir détails</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection