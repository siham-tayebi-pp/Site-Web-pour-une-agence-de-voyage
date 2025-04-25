@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <i class="fas fa-suitcase-rolling text-5xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold">Créez votre compte</h1>
            <p class="text-gray-600">Commencez votre aventure avec VoyCom</p>
        </div>

        <form class="space-y-6">
            <div class="grid grid-cols-2 gap-4">
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
                <label class="block text-gray-700 mb-2">Mot de passe</label>
                <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-2">Minimum 8 caractères avec chiffres et symboles</p>
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Confirmer le mot de passe</label>
                <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="terms" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-gray-700">J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800">conditions d'utilisation</a></label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-bold text-lg">
                S'inscrire
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">Déjà membre ? <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium">Se connecter</a></p>
        </div>
    </div>
</div>
@endsection