<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\File;

class ValidMyanmarNrc implements ValidationRule
{
    /**
     * @var array<int, list<string>>|null
     */
    protected static ?array $townshipCodes = null;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a valid Myanmar NRC number.');
            return;
        }

        if (! preg_match('/^(\d{1,2})\/([A-Za-z]+)\((N|P|E)\)(\d{6})$/', $value, $matches)) {
            $fail('The :attribute format is invalid. Use a format like 12/HsaBaTa(N)123456.');
            return;
        }

        $region = $matches[1];
        $township = $matches[2];
        $number = $matches[4];

        // Strip a leading zero from the region number for lookup.
        $region = ltrim($region, '0');
        if ($region === '') {
            $region = '0';
        }

        $codes = $this->townshipCodes();

        if (! isset($codes[$region])) {
            $fail('The :attribute contains an invalid NRC region number.');
            return;
        }

        if (! in_array($township, $codes[$region], true)) {
            $fail('The :attribute contains an invalid NRC township code.');
            return;
        }

        if ((int) $number === 0) {
            $fail('The :attribute contains an invalid NRC registration number.');
        }
    }

    /**
     * @return array<int, list<string>>
     */
    protected function townshipCodes(): array
    {
        if (self::$townshipCodes !== null) {
            return self::$townshipCodes;
        }

        $path = base_path('nrcdb.json');

        if (! File::exists($path)) {
            return self::$townshipCodes = [];
        }

        $data = json_decode(File::get($path), true);

        return self::$townshipCodes = is_array($data) ? $data : [];
    }
}
