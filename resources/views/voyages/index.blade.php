@extends('layouts.app')
{{-- <x-header /> --}}
@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Filtres -->
        <div class="md:w-1/4 bg-white p-6 rounded-lg shadow">
            <h3 class="font-bold text-xl mb-4">Filtrer les résultats</h3>
            
            <!-- Destination -->
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Destination</label>
                <select class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option>Toutes</option>
                    <option>Europe</option>
                    <option>Asie</option>
                    <option>Amérique</option>
                </select>
            </div>
            
            <!-- Prix -->
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Prix max</label>
                <input type="range" min="0" max="5000" step="100" class="w-full mb-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>0€</span>
                    <span>5000€</span>
                </div>
            </div>
            
            <!-- Durée -->
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Durée (jours)</label>
                <div class="flex space-x-4">
                    <button class="px-3 py-1 border rounded-lg hover:bg-blue-50">1-7</button>
                    <button class="px-3 py-1 border rounded-lg hover:bg-blue-50">8-14</button>
                    <button class="px-3 py-1 border rounded-lg hover:bg-blue-50">15+</button>
                </div>
            </div>
            
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Appliquer les filtres
            </button>
        </div>
        
        <!-- Liste des voyages -->
        <div class="md:w-3/4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Nos Voyages</h2>
                <div class="flex items-center space-x-2">
                    <span>Trier par :</span>
                    <select class="border rounded-lg px-3 py-1">
                        <option>Pertinence</option>
                        <option>Prix croissant</option>
                        <option>Prix décroissant</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Carte Voyage 1 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
                    <img src="https://source.unsplash.com/random/400x300?bali" alt="Bali" class="md:w-1/3 h-48 md:h-auto object-cover">
                    <div class="p-6 md:w-2/3">
                        <div class="flex justify-between">
                            <h3 class="text-xl font-bold">Bali, Indonésie</h3>
                            <span class="text-lg font-bold text-blue-600">€1499</span>
                        </div>
                        <div class="flex items-center mt-2 text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span class="text-gray-600 ml-2">4.5 (128 avis)</span>
                        </div>
                        <p class="mt-3 text-gray-600">14 jours / 13 nuits - Vol inclus - Petit déjeuner</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Plage</span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">Aventure</span>
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">Romantique</span>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-1"></i> Prochain départ : 15/06/2023
                            </div>
                            <a href="/voyages/4" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Carte Voyage 2 -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
                    <img src="https://source.unsplash.com/random/400x300?rome" alt="Rome" class="md:w-1/3 h-48 md:h-auto object-cover">
                    <div class="p-6 md:w-2/3">
                        <div class="flex justify-between">
                            <h3 class="text-xl font-bold">Rome, Italie</h3>
                            <span class="text-lg font-bold text-blue-600">€699</span>
                        </div>
                        <div class="flex items-center mt-2 text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <span class="text-gray-600 ml-2">4.0 (89 avis)</span>
                        </div>
                        <p class="mt-3 text-gray-600">7 jours / 6 nuits - Vol inclus - Petit déjeuner</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Culture</span>
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Gastronomie</span>
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-1"></i> Prochain départ : 22/05/2023
                            </div>
                            <a href="/voyages/5" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                                Réserver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <a href="#" class="px-3 py-1 border rounded-lg hover:bg-blue-50">&laquo;</a>
                    <a href="#" class="px-3 py-1 border rounded-lg bg-blue-600 text-white">1</a>
                    <a href="#" class="px-3 py-1 border rounded-lg hover:bg-blue-50">2</a>
                    <a href="#" class="px-3 py-1 border rounded-lg hover:bg-blue-50">3</a>
                    <a href="#" class="px-3 py-1 border rounded-lg hover:bg-blue-50">&raquo;</a>
                </nav>
            </div>
        </div>
    </div>
</div>
@endsection