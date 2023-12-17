<?php
namespace App\Traits;

trait ProfileValidationTrait
{

    protected $rules = [
        'studentName' => 'required',
        'studentId' => 'required',
        'suffix' => 'nullable',
        'nickname' => 'nullable',
        'age' => 'required|numeric',
        'sex' => 'required',
        'birthdate' => 'required|date',
        'contact' => 'required',
        'religion' => 'required',
        'mother_tongue' => 'nullable',
        'four_ps' => 'required',
        'birth_order' => 'required',
        'number_of_siblings' => 'nullable',
        'selectedBarangay' => 'required',
        'selectedCity' => 'required',
        'selectedMunicipality' => 'required',
        'birth_place' => 'required',
        'living_with' => 'required',
        'guardian_name' => 'required',
        'relationship' => 'required',
        'guardian_contact' => 'required',
        'occupation' => 'nullable',
        'guardian_age' => 'required|numeric',
        'favorite_subject' => 'nullable',
        'difficult_subject' => 'nullable',
        'school_organization' => 'nullable',
        'plans' => 'required',
        // Need more revision if it's optional -josh
        'height' => 'required|numeric',
        'weight' => 'required|numeric',
        'bmi' => 'required',
        'disability' => 'nullable',
        'foodAllergy' => 'nullable',
        'hasDisability' => 'nullable',
        'hasFoodAllergy' => 'nullable',
        'father_type' => 'nullable',
        'father_name' => 'required',
        'father_age' => 'required|numeric',
        'father_occupation' => 'required',
        'father_contact' => 'required',
        'father_office_contact' => 'nullable',
        'father_birth_place' => 'required',
        'father_work_address' => 'required',
        'father_monthly_income' => 'nullable|numeric',
        'mother_type' => 'nullable',
        'mother_name' => 'required',
        'mother_age' => 'required|numeric',
        'mother_occupation' => 'required',
        'mother_contact' => 'required',
        'mother_office_contact' => 'nullable',
        'mother_birth_place' => 'required',
        'mother_work_address' => 'nullable',
        'mother_monthly_income' => 'nullable|numeric',
    ];
}
