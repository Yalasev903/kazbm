<?php

namespace App\Models\Applications;

use App\Models\Application;

class ApplicationConsultation extends Application
{

    public function getType(): string
    {
        return 'application_consult';
    }
}
