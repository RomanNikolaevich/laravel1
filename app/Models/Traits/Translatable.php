<?php

namespace App\Models\Traits;


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

//        if (in_array($fieldName, $this->getAttributes)) {
//
//        } else {
//            throw new \LogicException('no such attribute for model '. get_class($this));
//        }
        return $this->fieldName;
    }

}
