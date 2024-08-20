<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterThan implements Rule
{
    /**
     * @var int
     */
    private $lowerBound;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($lowerBound=0)
    {
        //
        $this->lowerBound = $lowerBound;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return is_numeric($value) && $value > $this->lowerBound;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.greater-than', ['value'=>$this->lowerBound]);
    }
}
