<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $produits = collect([]); // temporaire (évite l'erreur)

        return view('shop', compact('produits'));
    }
}