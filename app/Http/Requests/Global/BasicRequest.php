<?php

namespace App\Http\Requests\Global;

use Illuminate\Foundation\Http\FormRequest;

abstract class BasicRequest extends FormRequest
{
    /**
     * Get multilingual validation rules.
     *
     * @return array
     */
    protected function langRules(array $fields = []): array
    {
        $fields = empty($fields) ? ['name', 'description'] : $fields;
        $defaultRules = 'required|string';

        return collect(config('lang.required_languages'))->flatMap(function ($lang) use ($fields , $defaultRules) {
            $rules = [];
            foreach ($fields as $field => $rule) {

                if (is_int($field)) {
                    $field = $rule;
                    $rule = $defaultRules;
                }

                $rules["$field.$lang"] = $rule;
             }
            return $rules;
        })->toArray();
    }



}
