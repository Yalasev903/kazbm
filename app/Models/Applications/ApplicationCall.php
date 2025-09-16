<?php

namespace App\Models\Applications;

use App\Models\Application;

class ApplicationCall extends Application
{

    public function getType(): string
    {
        return 'application_call';
    }
}
