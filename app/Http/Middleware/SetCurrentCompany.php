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
        // Sem login → segue
        if (!$user) {
            return $next($request);
        }
        
        /** @var CurrentCompany $ctx */
        $ctx = app(CurrentCompany::class);
        
        // 1) Sessão tem company_id?
        if ($id = session('company_id')) {
            $company = Company::query()
            ->whereKey($id)
            ->whereHas('companyUsers', fn($q) => $q->where('user_id', $user->id))
            ->first();
            if ($company) {
                $ctx->set($company);
                // dd("Sessão tem company_id", $id, $company);
                return $next($request);
            } else {
                // limpa sessão inválida
                session()->forget('company_id');
            }
        }

        // 2) Descobrir por pivot
        $companies = Company::query()
            ->whereHas('companyUsers', fn($q) => $q->where('user_id', $user->id))
            ->get();

        if ($companies->count() === 1) {
            $ctx->set($companies->first());
            session(['company_id' => $companies->first()->id]);
            return $next($request);
        }

        if ($companies->count() > 1) {
            // Escolha sua estratégia:
            // a) Redirecionar pra página de seleção
            // return redirect()->route('companies.choose');

            // b) Fallback: primeira
            $ctx->set($companies->first());
            session(['company_id' => $companies->first()->id]);
            return $next($request);
        }

        abort(403, 'Você não está vinculado a nenhuma empresa.');
    }
}
