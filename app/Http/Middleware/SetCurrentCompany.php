<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Support\CurrentCompany;
use Closure;
use Illuminate\Http\Request;

class SetCurrentCompany
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Sem login → segue normalmente
        if (! $user) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();

        /**
         * Rotas que devem ser permitidas mesmo sem empresa:
         * - logout
         * - tela de criação de empresa
         */
        $allowedRoutes = [
            'filament.admin.auth.logout',
            'filament.admin.resources.companies.create',
        ];

        if (in_array($currentRoute, $allowedRoutes, true)) {
            return $next($request);
        }

        /** @var CurrentCompany $ctx */
        $ctx = app(\App\Support\CurrentCompany::class);

        // 1) Sessão tem company_id?
        if ($id = session('company_id')) {
            $company = Company::query()
                ->whereKey($id)
                ->whereHas('companyUsers', fn ($q) => $q->where('user_id', $user->id))
                ->first();

            if ($company) {
                $ctx->set($company);
                return $next($request);
            } else {
                session()->forget('company_id');
            }
        }

        // 2) Buscar empresas do usuário
        $companies = Company::query()
            ->whereHas('companyUsers', fn ($q) => $q->where('user_id', $user->id))
            ->get();

        if ($companies->count() === 1) {
            $ctx->set($companies->first());
            session(['company_id' => $companies->first()->id]);
            return $next($request);
        }

        if ($companies->count() > 1) {
            $ctx->set($companies->first());
            session(['company_id' => $companies->first()->id]);
            return $next($request);
        }

        // 3) Nenhuma empresa → redirecionar para criar empresa
        return redirect()->route('filament.admin.resources.companies.create');
    }

}
