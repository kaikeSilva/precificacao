<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanySettings;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CompanyService
{
    /**
     * Cria uma nova empresa com suas configurações padrão.
     * Automaticamente cria um registro de CompanySettings vazio com valores padrão.
     * Vincula o usuário proprietário à empresa.
     *
     * @param array $data Dados da empresa (name, document, owner_user_id, timezone)
     * @return Company
     */
    public function createCompany(array $data): Company
    {
        return DB::transaction(function () use ($data) {
            // cria usuario para o dono da empresa
            $ownerUser = User::create([
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'password' => $data['user']['password'],
            ]);

            // Cria a empresa
            $company = Company::create([
                'name' => $data['name'],
                'document' => $data['document'],
                'timezone' => $data['timezone'] ?? 'America/Sao_Paulo',
            ]);

            // Cria as configurações padrão da empresa
            CompanySettings::create([
                'company_id' => $company->id,
                // Os demais campos usarão os valores padrão definidos no modelo
            ]);

            // Vincula o usuário proprietário à empresa
            CompanyUser::create([
                'company_id' => $company->id,
                'user_id' => $ownerUser->id,
                'role' => CompanyUser::ROLE_OWNER,
            ]);
            return $company;
        });
    }
}
