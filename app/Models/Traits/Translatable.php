<?php

namespace App\Models\Traits;

use Cassandra\Exception\LogicException;
use Illuminate\Support\Facades\App;

trait Translatable
{
    protected string $defaultLocale = 'ua';
    public function __($fieldName)
    {
        $locale = App::getLocale() ?? $this->defaultLocale;
        if ($locale === 'en') {
            $fieldName .= '_en';
        }

        if (in_array($fieldName, $this->getAttributes)) {

        } else {
            throw new LogicException();
        }
    }

}
