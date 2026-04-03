<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    // GET /api/wallet
    // Returns the current authenticated user's balance
    public function balance()
    {
        // auth()->user() gives us the authenticated user
        // Laravel already fetched it from the database via auth:api middleware
        $user = auth()->user();

        return response()->json([
            'solde' => $user->solde,
        ]);
    }

    // POST /api/wallet/spend
    // Deducts points from the authenticated user's balance
    public function spend(Request $request)
    {
        // Step 1: Validate the incoming request
        // 'integer' ensures it's a whole number (not 10.5)
        // 'min:1' ensures it's strictly positive (not 0 or negative)
        $request->validate([
            'montant' => 'required|integer|min:1',
        ]);

        // Step 2: Get the authenticated user
        $user = auth()->user();

        $montant = $request->montant;

        // Step 3: Check minimum spend amount (business rule: minimum 10 points)
        if ($montant < 10) {
            return response()->json([
                'message' => 'Le montant minimum est de 10 points',
            ], 422);
        }

        // Step 4: Check if user has enough balance
        if ($user->solde < $montant) {
            return response()->json([
                'message' => 'Solde insuffisant',
            ], 422);
        }

        // Step 5: Deduct the points and save to database
        // $user->solde -= $montant updates the value in PHP memory
        // $user->save() writes it to the database
        $user->solde -= $montant;
        $user->save();

        // Step 6: Return the new balance
        return response()->json([
            'message'      => 'Dépense effectuée avec succès',
            'nouveau_solde' => $user->solde,
        ]);
    }
}