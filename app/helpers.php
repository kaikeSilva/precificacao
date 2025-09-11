<?php

use App\Models\Company;
use App\Support\CurrentCompany;

if (! function_exists('currentCompany')) {
    function currentCompany(): ?\App\Models\Company
    {
        if (app(CurrentCompany::class)->id() === null) {
            app(CurrentCompany::class)->resetCurrentCompany();
        }
        return app(CurrentCompany::class)->get();
    }
}

if (! function_exists('currentCompanyId')) {
    function currentCompanyId(): ?int
    {
        if (app(CurrentCompany::class)->id() === null) {
            app(CurrentCompany::class)->resetCurrentCompany();
        }
        return app(CurrentCompany::class)->id();
    }
}
