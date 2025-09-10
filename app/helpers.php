<?php

use App\Support\CurrentCompany;

if (! function_exists('currentCompany')) {
    function currentCompany(): ?\App\Models\Company
    {
        return app(CurrentCompany::class)->get();
    }
}

if (! function_exists('currentCompanyId')) {
    function currentCompanyId(): ?int
    {
        return app(CurrentCompany::class)->id();
    }
}
