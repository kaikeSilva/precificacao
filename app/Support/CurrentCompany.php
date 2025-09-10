<?php

namespace App\Support;

use App\Models\Company;

class CurrentCompany
{
    public function __construct(
        protected ?Company $company = null,
    ) {}

    public function set(?Company $company): void
    {
        $this->company = $company;
    }

    public function get(): ?Company
    {
        return $this->company;
    }

    public function id(): ?int
    {
        return $this->company?->id;
    }

    public function ensure(): Company
    {
        if (!$this->company) {
            abort(403, 'Nenhuma empresa selecionada.');
        }

        return $this->company;
    }
}
