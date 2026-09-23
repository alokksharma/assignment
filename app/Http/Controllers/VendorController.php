<?php

namespace App\Http\Controllers;

use App\Notifications\VendorLoginDetailsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-vendor')->only('index');

        $this->middleware('can:create-vendor')->only([
            'create',
            'store',
        ]);

        $this->middleware('can:edit-vendor')->only([
            'edit',
            'update',
        ]);

        $this->middleware('can:delete-vendor')->only('destroy');
    }
    public function index()
    {
       $users = User::role('Vendor')->with('vendor.vendorProjectAssigns')->search(request('q'))->paginate(10);
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

        $user = null;
        $vendor = null;

        DB::transaction(function () use ($request, &$user, &$vendor) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'password' => Hash::make('12345678'),

            ]);

            $user->assignRole('Vendor');

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
                'vendor_id' => now()->format('mY') . '-' . str_pad($vendor->id, 3, '0', STR_PAD_LEFT),
            ]);
             $message = view('emails.vendor-login-details', [
                'vendor_name' => $user->name,
                'vendor_company_name' => $request->company_name ?? $request->name,
                'vendor_id' => $vendor->vendor_id ?? $user->id,
                'login_email' =>$user->email,
                'username' =>$user->email,
                'vendor_portal_url' => url('/'),
                'vendor_login_link' => route('login'),
                'support_email' => config('mail.from.address', 'support@example.com'),
                'company_website' =>  rtrim(config('app.url', 'https://3asresearch.com')),
                'company_name' => config('app.name', '3AS Research'),
        ])->render();
        $message = preg_replace("/(?<!\r)\n/", "\r\n", $message);
         // Headers must also use CRLF
        $headers = "From: no-reply@3asresearchsolutions.in\r\n";
        $headers .= "Reply-To: support@3asresearchsolutions.in\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        try {
            mail($user->email, $subject, $message, $headers);
        } catch (\Throwable $e) {
            dd( $e);
            \Log::error('Password changed mail failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
        });

        // if ($user && $vendor) {
        //     $user->notify(new VendorLoginDetailsNotification(
        //         $user->name,
        //         $request->company_name ?? $request->name,
        //         $vendor->vendor_id ?? $user->id,
        //         $user->email,
        //         $user->email,
        //         url('/'),
        //         route('login')
        //     ));
        // }

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully!');
    }

    public function edit($id)
    {
        $user = User::with('vendor')->findOrFail($id);

        return view('companies.vendors.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'contact' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'complete' => 'nullable|string|max:255',
            'terminate' => 'nullable|string|max:255',
            'quotafull' => 'nullable|string|max:255',
            'speedster' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'contact' => $request->contact,
            'address' => $request->address,
        ]);
        $user->vendor()->update([
                'currency'   => $request->currency,
                'complete'   => $request->complete,
                'terminate'  => $request->terminate,
                'quotafull'  => $request->quotafull,
                'speedster'  => $request->speedster,
                'expired_at' => $request->expired_at,
            ]);

        // if ($user->vendor) {
        //     $user->vendor->update([
        //         'name'  => $request->company_name,
        //         'phone' => $request->phone,
        //     ]);
        // }

        return redirect()
           ->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        if ($user->hasRole('Vendor')) {

            $vendor = $user->vendor;

            // Check vendor assigned projects
            if ($vendor && $vendor->vendorProjectAssigns()->exists()) {
                return redirect()->back()
                    ->with('error', 'Vendor cannot be deleted because projects are assigned.');
            }

            // Delete vendor record
            if ($vendor) {
                $vendor->delete();
            }
        }

        // Delete user record
        $user->delete();

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
