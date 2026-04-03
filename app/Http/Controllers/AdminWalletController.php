<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminWalletController extends Controller
{
    // POST /api/admin/wallet/{user}/credit
    // Adds points to a target user's balance
    public function credit(Request $request, User $user)
    {
        // Step 1: Validate the incoming request
        $request->validate([
            'montant' => 'required|integer|min:1',
        ]);

        // Step 2: Add points to the target user's balance
        $user->solde += $request->montant;
        $user->save();

        // Step 3: Return the updated balance
        return response()->json([
            'message'       => 'Crédit effectué avec succès',
            'user'          => $user->name,
            'nouveau_solde' => $user->solde,
        ]);
    }

    // POST /api/admin/wallet/{user}/debit
    // Removes points from a target user's balance
    public function debit(Request $request, User $user)
    {
        // Step 1: Validate the incoming request
        $request->validate([
            'montant' => 'required|integer|min:1',
        ]);

        $montant = $request->montant;

        // Step 2: Check if target user has enough balance
        if ($user->solde < $montant) {
            return response()->json([
                'message' => 'Solde insuffisant pour ce débit',
            ], 422);
        }

        // Step 3: Deduct points and save
        $user->solde -= $montant;
        $user->save();

        // Step 4: Return the updated balance
        return response()->json([
            'message'       => 'Débit effectué avec succès',
            'user'          => $user->name,
            'nouveau_solde' => $user->solde,
        ]);
    }
}