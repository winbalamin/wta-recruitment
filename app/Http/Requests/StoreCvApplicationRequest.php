<?php

namespace App\Http\Requests;

use App\Rules\ValidMyanmarNrc;
use Illuminate\Foundation\Http\FormRequest;

class StoreCvApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $state = $this->input('nrc_state', '');
        $township = $this->input('nrc_township', '');
        $type = $this->input('nrc_type', '');
        $number = $this->input('nrc_number', '');

        if ($state !== '' && $township !== '' && $type !== '' && $number !== '') {
            $this->merge([
                'nrc' => sprintf('%s/%s(%s)%s', $state, $township, $type, $number),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name'                         => ['required', 'string', 'min:2', 'max:120'],
            'position_applied'             => ['required', 'string', 'max:120'],
            'date_of_birth'                => ['required', 'date', 'before:today'],
            'education_level'              => ['required', 'in:High School,Diploma,Bachelor,Master,PhD,Other'],
            'current_employer'             => ['nullable', 'string', 'max:120'],
            'current_job_title'            => ['nullable', 'string', 'max:120'],
            'expected_salary'              => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'nrc_state'                    => ['required', 'string'],
            'nrc_township'                 => ['required', 'string'],
            'nrc_type'                     => ['required', 'in:N,P,E'],
            'nrc_number'                   => ['required', 'string', 'regex:/^[0-9]{6}$/'],
            'nrc'                          => ['required', 'string', 'min:4', 'max:60', 'unique:cv_applications,nrc', new ValidMyanmarNrc],
            'address'                      => ['required', 'string', 'min:5', 'max:1000'],
            'email'                        => ['required', 'string', 'email:rfc', 'max:160'],
            'phone'                        => ['required', 'string', 'regex:/^[0-9+\-\s()]{6,40}$/'],
            'photo'                        => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nrc_file'                     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'work_experience'              => ['nullable', 'string', 'max:5000'],
            'skills'                       => ['nullable', 'string', 'max:2000'],
            'languages'                    => ['nullable', 'string', 'max:255'],
            'education'                    => ['nullable', 'string', 'max:5000'],
            'start_date'                   => ['nullable', 'date', 'after_or_equal:today'],
            'emergency_contact_name'       => ['required', 'string', 'max:120'],
            'emergency_contact_relationship' => ['required', 'string', 'max:40'],
            'emergency_contact_phone'      => ['required', 'string', 'regex:/^[0-9+\-\s()]{6,40}$/'],
            'portfolio_url'                => ['nullable', 'url', 'max:255'],
            'references'                   => ['nullable', 'string', 'max:3000'],
            'why_join_wta'                 => ['required', 'string', 'min:20', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Please enter your full name.',
            'position_applied.required' => 'Please select or enter the position you are applying for.',
            'date_of_birth.required' => 'Please enter your date of birth.',
            'date_of_birth.before' => 'Date of birth must be before today.',
            'education_level.required' => 'Please select your highest education level.',
            'nrc.required'         => 'NRC number is required.',
            'nrc.unique'           => 'This NRC has already been used to submit an application.',
            'address.required'     => 'Address is required.',
            'email.required'       => 'Email is required.',
            'email.email'          => 'Please provide a valid email address.',
            'phone.required'       => 'Phone number is required.',
            'phone.regex'          => 'Phone number may only contain digits, spaces, + ( ) and -.',
            'emergency_contact_name.required' => 'Please enter an emergency contact name.',
            'emergency_contact_relationship.required' => 'Please enter the relationship of your emergency contact.',
            'emergency_contact_phone.required' => 'Please enter an emergency contact phone number.',
            'emergency_contact_phone.regex' => 'Emergency contact phone may only contain digits, spaces, + ( ) and -.',
            'portfolio_url.url'    => 'Please provide a valid portfolio or LinkedIn URL.',
            'photo.mimes'          => 'Photo must be a JPG, JPEG, or PNG image.',
            'photo.max'            => 'Photo must not be larger than 2 MB.',
            'nrc_file.mimes'       => 'NRC attachment must be a JPG, JPEG, PNG, or PDF file.',
            'nrc_file.max'         => 'NRC attachment must not be larger than 4 MB.',
            'why_join_wta.required'=> 'Please tell us why you want to join WTA.',
            'why_join_wta.min'     => 'Please write at least 20 characters about why you want to join WTA.',
        ];
    }

    public function attributes(): array
    {
        return [
            'why_join_wta' => 'reason for joining',
            'nrc'          => 'NRC',
            'nrc_file'     => 'NRC attachment',
            'position_applied' => 'position applied for',
            'date_of_birth' => 'date of birth',
            'education_level' => 'education level',
            'expected_salary' => 'expected salary',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_relationship' => 'emergency contact relationship',
            'emergency_contact_phone' => 'emergency contact phone',
            'portfolio_url' => 'portfolio URL',
        ];
    }
}
