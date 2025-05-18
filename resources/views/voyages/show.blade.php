
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Gallery -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div class="lg:col-span-2">
                <img src="https://source.unsplash.com/random/1200x800?bali" alt="Bali" class="w-full h-96 object-cover">
            </div>
            <div class="grid grid-cols-2 gap-2">
                <img src="https://source.unsplash.com/random/600x400?bali-beach" alt="Bali Beach" class="h-48 object-cover">
                <img src="https://source.unsplash.com/random/600x400?bali-temple" alt="Bali Temple" class="h-48 object-cover">
                <img src="https://source.unsplash.com/random/600x400?bali-food" alt="Bali Food" class="h-48 object-cover">
                <img src="https://source.unsplash.com/random/600x400?bali-resort" alt="Bali Resort" class="h-48 object-cover">
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">Bali, Indonésie</h1>
                    <div class="flex items-center mt-2">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ml-2 text-gray-600">4.5 (128 avis)</span>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">€1499 <span class="text-lg text-gray-500">/personne</span></p>
                    <p class="text-gray-500">Vol inclus - 14 jours</p>
                </div>
            </div>
            
            <!-- Highlights -->
            <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    <span>15/06/2023</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-moon text-blue-600 mr-2"></i>
                    <span>13 nuits</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-utensils text-blue-600 mr-2"></i>
                    <span>Petit déjeuner</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-plane text-blue-600 mr-2"></i>
                    <span>Vol inclus</span>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Description</h2>
                <p class="text-gray-700 leading-relaxed">
                    Découvrez Bali, l'île des dieux, avec ce circuit de 14 jours qui vous fera découvrir les plus beaux paysages de l'île. 
                    Entre rizières en terrasses, temples majestueux et plages de sable fin, vous serez émerveillés par la beauté de Bali.
                    Notre circuit comprend la visite des sites incontournables comme Ubud, les temples d'Uluwatu et de Tanah Lot, 
                    ainsi que des moments de détente à Seminyak.
                </p>
            </div>
            
            <!-- Itinerary -->
            <div class="mt-8">
                <h2 class="text-xl font-bold mb-4">Itinéraire détaillé</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-600 pl-4 py-2">
                        <h3 class="font-bold">Jour 1 : Arrivée à Denpasar</h3>
                        <p class="text-gray-600">Transfert à votre hôtel à Seminyak et journée libre.</p>
                    </div>
                    <div class="border-l-4 border-blue-600 pl-4 py-2">
                        <h3 class="font-bold">Jour 2 : Découverte d'Ubud</h3>
                        <p class="text-gray-600">Visite des rizières de Tegallalang et du temple d'Ubud.</p>
                    </div>
                    <!-- More days... -->
                </div>
            </div>
            
            <!-- Booking CTA -->
            <div class="mt-12 bg-blue-50 rounded-lg p-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold">Prêt à réserver votre voyage ?</h3>
                        <p class="text-gray-600">Profitez de notre offre spéciale jusqu'au 30/04/2023</p>
                    </div>
                    <a href="/reservation/4" class="mt-4 md:mt-0 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-bold">
                        Réserver maintenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection