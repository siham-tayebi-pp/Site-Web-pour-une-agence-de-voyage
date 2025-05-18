@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
        <div class="text-center mb-8">
            <i class="fas fa-plane-departure text-5xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold">Connectez-vous</h1>
            <p class="text-gray-600">Accédez à votre espace VoyCom</p>
        </div>

        <form class="space-y-6">
            <div>
                <label class="block text-gray-700 mb-2">Adresse email</label>
                <input type="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 mb-2">Mot de passe</label>
                <input type="password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-blue-500">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 mt-2 inline-block">Mot de passe oublié ?</a>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-gray-700">Se souvenir de moi</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-bold text-lg">
                Se connecter
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">Pas encore membre ? <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">Créer un compte</a></p>
        </div>

        <div class="mt-8 border-t border-gray-200 pt-6">
            <p class="text-center text-gray-500 text-sm">Ou connectez-vous avec</p>
            <div class="flex justify-center space-x-4 mt-4">
                <button class="p-2 bg-red-100 rounded-full text-red-500 hover:bg-red-200">
                    <i class="fab fa-google text-xl"></i>
                </button>
                <button class="p-2 bg-blue-100 rounded-full text-blue-500 hover:bg-blue-200">
                    <i class="fab fa-facebook-f text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection