<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Partner;
use App\Models\Proof;
use App\Notifications\PartnerApiCredentialsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PartnerController extends Controller
{
    public function index()
    {
       $users = User::role('Partner')->with('partner')->search(request('q'))->paginate(10);
        return view('masters.partner.index', compact('users'));
    }

    public function create()
    {
         return view('masters.partner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
            'partner_nick_name' => 'nullable|string|max:255',
            'partner_api_url' => 'nullable|string|max:255',
            'supplier_id' => ['nullable','string', Rule::unique('partners','supplier_id')],
            'supplier_token' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['Active','NonActive'])],
            'expired_at' => 'nullable|date',
            'complete_redirect' => 'nullable|string|max:255',
            'terminate_redirect' => 'nullable|string|max:255',
            'quotafull_redirect' => 'nullable|string|max:255',
            'quality_redirect' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
            'password' => Hash::make('12345678'),
        ]);

        $partnerData = [
            'user_id' => $user->id,
            'partner_nick_name' => $request->partner_nick_name,
            'partner_api_url' => $request->partner_api_url,
            'supplier_id' => $request->supplier_id,
            'supplier_token' => $request->supplier_token,
            'status' => $request->status ?? 'Active',
            'is_active' => ($request->status ?? 'Active') === 'Active' ? 1 : 0,
            'complete_redirect' => $request->complete_redirect,
            'terminate_redirect' => $request->terminate_redirect,
            'quotafull_redirect' => $request->quotafull_redirect,
            'quality_redirect' => $request->quality_redirect,
        ];

        if ($request->filled('expired_at')) {
            try {
                $partnerData['expired_at'] = Carbon::parse($request->expired_at);
            } catch (\Exception $e) {
                Log::warning('Invalid expired_at provided: ' . $request->expired_at);
            }
        }

        $partner = $user->partner()->create($partnerData);

        $user->assignRole('Partner'); // Assign the role
        $subject = 'Partner API Login & Integration Details – 3AS Research';

        $message = view('emails.partner-api-credentials', [
            'partner_name' => $user->name,
            'partner_company_name' => $request->partner_nick_name ?? $request->name,
            'partner_id' => $partner->supplier_id ?? 'N/A',
            'api_username' => $request->partner_nick_name ?? $request->name,
            'login_email' => $user->email,
            'account_status' => $partner->status ?? 'Active',
            'activation_date' => $partner->expired_at ? Carbon::parse($partner->expired_at)->format('d M, Y') : now()->format('d M, Y'),
            'partner_portal_url' => url('/'),
            'partner_login_link' => route('login'),
            'api_base_url' => rtrim(config('app.url', url('/')), '/'),
            'api_documentation_url' =>  url('/'),
            'authentication_method' => 'API Key',
            'client_id' => $partner->supplier_id ?? 'N/A',
            'client_secret_or_secure_link' => $partner->supplier_token ?? 'N/A',
            'production_or_sandbox' =>'Production',
            'api_version' => 'v1',
            'postback_url' =>$partner->complete_redirect ?? url('/'),
             'account_security_link' => url('/profile'),
            'support_email' => config('mail.from.address', 'support@example.com'),
            'technical_support_email' => config('mail.from.address', 'support@example.com'),
            'company_name' => config('app.name', '3AS Research'),
            'company_website' => rtrim(config('app.url', 'https://3asresearch.com')),
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
            \Log::error('Partner credentials mail failed', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
        // $user->notify(new PartnerApiCredentialsNotification(
        //     $user->name,
        //     $request->partner_nick_name ?? $request->name,
        //     $partner->supplier_id ?? 'N/A',
        //     $request->partner_nick_name ?? $request->name,
        //     $user->email,
        //     $partner->status ?? 'Active',
        //     $partner->expired_at ? Carbon::parse($partner->expired_at)->format('d M, Y') : now()->format('d M, Y'),
        //     url('/'),
        //     route('login'),
        //     rtrim(config('app.url', url('/')), '/'),
        //     url('/'),
        //     'API Key',
        //     $partner->supplier_id ?? 'N/A',
        //     $partner->supplier_token ?? 'N/A',
        //     'Production',
        //     'v1',
        //     $partner->complete_redirect ?? url('/'),
        //     url('/profile'),
        //     config('mail.from.address', 'support@example.com'),
        //     config('mail.from.address', 'support@example.com')
        // ));

        return redirect()->route('partners.index')->with('success', 'Partner created successfully!');
    }

    public function show($id)
    {
        $user = User::with('partner.proofs')->findOrFail($id);
        return view('masters.partner.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('partner')->findOrFail($id);
        return view('masters.partner.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('partner')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:20',
            'partner_nick_name' => 'nullable|string|max:255',
            'partner_api_url' => 'nullable|string|max:255',
            'supplier_id' => ['nullable','string', Rule::unique('partners','supplier_id')->ignore(optional($user->partner)->id)],
            'supplier_token' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['Active','NonActive'])],
            'expired_at' => 'nullable|date',
            'complete_redirect' => 'nullable|string|max:255',
            'terminate_redirect' => 'nullable|string|max:255',
            'quotafull_redirect' => 'nullable|string|max:255',
            'quality_redirect' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'contact' => $request->contact,
        ]);

        $partnerData = [
            'partner_nick_name' => $request->partner_nick_name,
            'partner_api_url' => $request->partner_api_url,
            'supplier_id' => $request->supplier_id,
            'supplier_token' => $request->supplier_token,
            'status' => $request->status ?? (optional($user->partner)->status ?? 'Active'),
            'is_active' => ($request->status ?? (optional($user->partner)->status ?? 'Active')) === 'Active' ? 1 : 0,
            'complete_redirect' => $request->complete_redirect,
            'terminate_redirect' => $request->terminate_redirect,
            'quotafull_redirect' => $request->quotafull_redirect,
            'quality_redirect' => $request->quality_redirect,
        ];

        if ($request->filled('expired_at')) {
            try {
                $partnerData['expired_at'] = Carbon::parse($request->expired_at);
            } catch (\Exception $e) {
                Log::warning('Invalid expired_at provided for update: ' . $request->expired_at);
            }
        } else {
            $partnerData['expired_at'] = null;
        }

        if ($user->partner) {
            $user->partner->update($partnerData);
            $partner = $user->partner->refresh();
        } else {
            $partner = $user->partner()->create(array_merge(['user_id' => $user->id], $partnerData));
        }
        $subject = 'Partner API Login & Integration Details – 3AS Research';
        $message = view('emails.partner-api-credentials', [
                'partner_name' => $user->name,
                'partner_company_name' => $request->partner_nick_name ?? $request->name,
               'partner_id' =>  $partner->supplier_id ?? 'N/A',
                'api_username' => $request->partner_nick_name ?? $request->name,
                'login_email' => $user->email,
                'account_status' => $partner->status ?? 'Active',
                'activation_date' => $partner->expired_at ? Carbon::parse($partner->expired_at)->format('d M, Y') : now()->format('d M, Y'),
                'partner_portal_url' => url('/'),
                'partner_login_link' => route('login'),
                'api_base_url' => rtrim(config('app.url', url('/')), '/'),
               'api_documentation_url' =>  url('/'),
                'authentication_method' => 'API Key',
                'client_id' => $partner->supplier_id ?? 'N/A',
                'client_secret_or_secure_link' => $partner->supplier_token ?? 'N/A',
                'production_or_sandbox' =>'Production',
                'api_version' => 'v1',
                'postback_url' =>$partner->complete_redirect ?? url('/'),
                 'account_security_link' => url('/profile'),
                'support_email' => config('mail.from.address', 'support@example.com'),
                'technical_support_email' => config('mail.from.address', 'support@example.com'),
                'company_name' => config('app.name', '3AS Research'),
                'company_website' => rtrim(config('app.url', 'https://3asresearch.com')),
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

        return redirect()->route('partners.index')->with('success', 'Partner updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('partners.index')->with('success', 'Partner deleted successfully.');
    }

}
