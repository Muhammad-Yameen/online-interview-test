<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class Password implements Rule
{
    /**
     * Determine if the Length Validation Rule passes.
     *
     * @var boolean
     */
    public $lengthPasses = true;

    /**
     * Determine if the Uppercase Validation Rule passes.
     *
     * @var boolean
     */
    public $uppercasePasses = true;

    /**
     * Determine if the Numeric Validation Rule passes.
     *
     * @var boolean
     */
    public $numericPasses = true;

    /**
     * Determine if the Special Character Validation Rule passes.
     *
     * @var boolean
     */
    public $specialCharacterPasses = true;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->lengthPasses = (Str::length($value) >= config('constants.password.length'));
        $this->uppercasePasses = (Str::lower($value) !== $value);
        $this->numericPasses = ((bool) preg_match(config('constants.numeric'), $value));
        $this->specialCharacterPasses = ((bool) preg_match(config('constants.special_characters'), $value));

        return ($this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch (true) {
            case !$this->uppercasePasses
                && $this->numericPasses
                && $this->specialCharacterPasses:
                return  trans('validation.custom.password.min.uppercase.self', ['min' => config('constants.password.length')]);

            case !$this->numericPasses
                && $this->uppercasePasses
                && $this->specialCharacterPasses:
                return trans('validation.custom.password.min.numeric.self', ['min' => config('constants.password.length')]);

            case !$this->specialCharacterPasses
                && $this->uppercasePasses
                && $this->numericPasses:
                return trans('validation.custom.password.min.special_character.self', ['min' => config('constants.password.length')]);

            case !$this->uppercasePasses
                && !$this->numericPasses
                && $this->specialCharacterPasses:
                return trans('validation.custom.password.min.uppercase.numeric', ['min' => config('constants.password.length')]);

            case !$this->uppercasePasses
                && !$this->specialCharacterPasses
                && $this->numericPasses:
                return trans('validation.custom.password.min.uppercase.special_character.self', ['min' => config('constants.password.length')]);

            case !$this->uppercasePasses
                && !$this->numericPasses
                && !$this->specialCharacterPasses:
                return trans('validation.custom.password.mixed', ['min' => config('constants.password.length')]);

            default:
                return trans('validation.min.string', ['min' => config('constants.password.length')]);
        }
    }
}
