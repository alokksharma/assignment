<?php

namespace App\Http\Middleware;

use App\Models\Partner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateSupplier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supplierId = $request->header('Supplierid');
        $token      = $request->header('Token');

        if (!$supplierId || !$token) {
            return response()->json([
                'status'  => false,
                'message' => 'Missing Supplierid or Token header.',
            ], 401);
        }

        $supplier = Partner::where('supplier_id', $supplierId)
            ->where('supplier_token', $token)
            ->where('is_active', true)
            ->first();

        if (!$supplier) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid supplier credentials.',
            ], 401);
        }

        // Make the authenticated supplier available to the controller
        $request->merge(['authenticated_supplier' => $supplier]);

        return $next($request);
    }
}
