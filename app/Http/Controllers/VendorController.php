<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VendorController extends Controller
{
    public function index()
    {
       $users = User::role('Vendor')->paginate(10);
        return view('companies.vendors.index', compact('users'));
    }

    public function create()
    {
         return view('companies.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'currency' => 'required',
            'contact' => 'nullable|max:20',
            'address' => 'nullable|string|max:255',
            'complete' => 'nullable|string|max:255',
            'terminate' => 'nullable|string|max:255',
            'quotafull' => 'nullable|string|max:255',
            'speedster' => 'nullable|string|max:255',
           // 'vendor_id' => 'required|digits:6|unique:vendors,vendor_id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'password' => Hash::make('12345678'),

            ]);

            $user->assignRole('Vendor');
            $role = 'Vendor';

            // Store vendor record
            $vendor = $user->vendor()->create([
                'user_id' => $user->id,
                'currency' => $request->currency,
                'complete' => $request->complete,
                'terminate' => $request->terminate,
                'quotafull' => $request->quotafull,
                'speedster' => $request->speedster,
                'expired_at' => $request->expired_at,
            ]);

            $vendor->update([
                'vendor_id' =>  now()->format('mY') . '-' . str_pad($vendor->id, 3, '0', STR_PAD_LEFT),
            ]);
        });

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully!');
    }
}
