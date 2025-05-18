<x-header/>
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4">Contactez-nous</h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">Notre équipe est à votre disposition pour répondre à toutes vos questions</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Formulaire -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Prénom</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Nom</label>
                        <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Sujet</label>
                    <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                        <option>Choisir un sujet</option>
                        <option>Réservation</option>
                        <option>Information</option>
                        <option>Problème technique</option>
                        <option>Autre</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Message</label>
                    <textarea rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-bold text-lg">
                    Envoyer le message
                </button>
            </form>
        </div>

        <!-- Informations -->
        <div class="space-y-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-xl font-bold mb-4">Coordonnées</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-blue-600 mt-1 mr-4"></i>
                        <div>
                            <h3 class="font-medium">Adresse</h3>
                            <p class="text-gray-600">123 Avenue des Voyages, 75001 Paris</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-phone-alt text-blue-600 mt-1 mr-4"></i>
                        <div>
                            <h3 class="font-medium">Téléphone</h3>
                            <p class="text-gray-600">+33 1 23 45 67 89</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-envelope text-blue-600 mt-1 mr-4"></i>
                        <div>
                            <h3 class="font-medium">Email</h3>
                            <p class="text-gray-600">contact@voycom.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-xl font-bold mb-4">Horaires d'ouverture</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Lundi - Vendredi</span>
                        <span class="font-medium">9h - 19h</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Samedi</span>
                        <span class="font-medium">10h - 18h</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Dimanche</span>
                        <span class="font-medium">Fermé</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-xl font-bold mb-4">FAQ</h2>
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="font-medium mb-2">Comment annuler une réservation ?</h3>
                        <p class="text-gray-600">Vous pouvez annuler via votre espace client ou en nous contactant par téléphone.</p>
                    </div>
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="font-medium mb-2">Quels sont les modes de paiement acceptés ?</h3>
                        <p class="text-gray-600">Carte bancaire, virement, PayPal et chèques vacances.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection