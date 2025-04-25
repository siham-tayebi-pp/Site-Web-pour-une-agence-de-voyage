@extends('layouts.app')
<x-header />
@section('content')
<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <h1 class="text-3xl font-bold mb-6">Réservation pour Bali, Indonésie</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Form -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Informations personnelles</h2>
                    <form>
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Nom complet</label>
                            <input type="text" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Nombre de voyageurs</label>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4+</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">Demandes spéciales</label>
                            <textarea class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-bold">
                            Confirmer la réservation
                        </button>
                    </form>
                </div>
                
                <!-- Summary -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-bold mb-4">Récapitulatif</h2>
                    
                    <div class="mb-6">
                        <h3 class="font-bold mb-2">Bali, Indonésie</h3>
                        <p class="text-gray-600">14 jours / 13 nuits</p>
                        <p class="text-gray-600">Départ : 15/06/2023</p>
                    </div>
                    
                    <div class="border-t border-b border-gray-200 py-4">
                        <div class="flex justify-between mb-2">
                            <span>2 adultes x €1499</span>
                            <span>€2998</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Taxes et frais</span>
                            <span>€120</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Assurance</span>
                            <span>€89</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>€3207</span>
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-100 rounded-lg">
                        <h3 class="font-bold mb-2">Annulation gratuite</h3>
                        <p class="text-sm text-gray-600">Annulez sans frais jusqu'à 30 jours avant le départ.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection