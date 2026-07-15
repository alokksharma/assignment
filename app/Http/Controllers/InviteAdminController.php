<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
use App\Traits\CompanyTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InviteAdminController extends Controller
{
    use CompanyTrait;
    /**
     * Display the form for inviting a new admin or member.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Allow inviting Admin or Member
        $roles = [
            'Admin' => 'Admin',
            'Member' => 'Employee',
        ];
        return view('companies.invite-admin', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Member',
        ]);
        //Create Company if role is Superadmin

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
            'password' => Hash::make('12345678'),

        ]);
        $user->assignRole($request->role); // Assign the role based on the request
        $role = $request->role;

        return redirect()->route('companies.invite-list')->with('success', $request->role . ' created successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = [
            'Admin' => 'Admin',
            'Member' => 'Employee',
        ];
        return view('companies.edit', compact('user', 'roles'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
            'role' => $request->role,
        ]);

        return redirect()
            ->route('companies.invite-list')
            ->with('success', 'User updated successfully.');
    }

    public function index()
    {
        $users = User::role(['Member', 'Admin'])->paginate(10);
        return view('companies.invite-list', compact('users'));
    }
}
