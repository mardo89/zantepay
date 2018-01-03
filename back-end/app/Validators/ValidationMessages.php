<?php

namespace App\Validators;


class ValidationMessages
{
    /**
     * @var array List of validation messages
     */
    private static $defaultMessages = [
        'required' => '%s field is required',
        'string' => '%s field must be a string',
        'email' => '%s field must contain correct email',
        'max' => '%s field is to long',
        'unique' => '%s field must be unique',
        'numeric' => 'Amount field must be a numeric',
    ];

    /**
     * Get list of available currencies
     *
     * @return array
     */
    public static function getList($fields = [], $customMessages = [])
    {
        $validationMessages = [];

        foreach ($fields as $field => $alias) {

            foreach (self::$defaultMessages as $validator => $defaultMessage) {
                $id = $field . '.' . $validator;

                if (isset($customMessages[$id])) {
                    $validationMessages[$id] = $customMessages[$id];

                    continue;
                }

                $validationMessages[$id] = sprintf($defaultMessage, $alias);

            }

        }

        return $validationMessages;
    }

}
