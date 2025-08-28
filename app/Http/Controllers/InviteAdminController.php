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
            'Member' => 'Member',
        ];
        $company = Auth::user()->company; // Get the current user's company
        return view('companies.invite-admin', compact('company', 'roles'));
    }

    public function store(Request $request)
    {
        if(Auth::user()->hasRole('SuperAdmin')) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'company_name' => 'required|string|max:255',
                ]);
        }else{
             $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:Admin,Member',
            ]);
        }
       // dd($request->all());
        //Create Company if role is Superadmin
        if(Auth::user()->hasRole('SuperAdmin')) {
            $company = $request->company_name;
            $newCompany = \App\Models\Company::create(['name' => $company]);
            $companyId = $newCompany->id;
        } else {
            $companyId = Auth::user()->company_id; // Use the current user's company_id
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $companyId,
            'password' => Hash::make('12345678'),

        ]);
        if(Auth::user()->hasRole('SuperAdmin')) {
            $user->assignRole('Admin'); // Default role for SuperAdmin inviting
            $role = 'Admin';
        } else {
            $user->assignRole($request->role); // Assign the role based on the request
             $role = $request->role;
        }
     //   $user->assignRole($request->role);
        // Store invite record
        //update query using Invite models
      //  if(Auth::user()->roles == 'Admin' ) {
             Invite::create([
                    'user_id' => $user->id,
                    'invite_id' => Auth::user()->id,
                ]);
      //  }

        return redirect()->route('companies.invite-list')->with('success', $request->role . ' created successfully!');
    }

    public function index()
    {
        // Show a list of invited users for the current user's company
        $company = Auth::user()->company;
       // dd(Auth::user()->id); // Debugging line to check the company data
           $users = $this->inviteList();
             //   dd($users); // Debugging line to check the users data
        return view('companies.invite-list', compact('company', 'users'));
    }
}
