<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Statistik Ringkas untuk Dashboard Admin
    public function dashboardStats()
    {
        $totalUsers = User::where('role', 'user')->count();
        // Misal ada model Transaction:
        // $totalTransactions = Transaction::count();
        // $totalIncome = Transaction::where('type', 'income')->sum('amount');

        return response()->json([
            'status' => true,
            'data'   => [
                'total_users' => $totalUsers,
                // 'total_transactions' => $totalTransactions,
            ],
        ]);
    }

    // Kelola User
    public function getAllUsers()
    {
        $users = User::where('role', 'user')->latest()->get();

        return response()->json([
            'status' => true,
            'data'   => $users,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil dihapus oleh admin.',
        ]);
    }
}