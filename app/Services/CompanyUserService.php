<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanyUserService
{
    /**
     * Cria um novo usuário e o vincula à empresa com a role informada.
     * Espera dados conforme CompanyUserForm: role, username, email, password, password_confirmation.
     * Retorna o User relacionado (relacionamento companyUsers retorna User models).
     *
     * @throws ValidationException
     */
    public function createFromForm(Company $company, array $data): User
    {
        $validated = Validator::make($data, [
            'role' => ['sometimes', 'in:' . implode(',', CompanyUser::ROLES)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        return DB::transaction(function () use ($company, $validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $companyUser = CompanyUser::create([
                'company_id' => $company->id,
                'user_id' => $user->id,
                'role' => isset($validated['role']) ? $validated['role'] : CompanyUser::ROLE_OWNER,
            ]);

            return $user;
        });
    }
}
