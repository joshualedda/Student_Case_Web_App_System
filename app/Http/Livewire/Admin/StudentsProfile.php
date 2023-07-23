<?php

namespace App\Http\Livewire\Admin;

use App\Models\Profile;
use Livewire\Component;
use App\Models\Barangay;
use App\Models\Province;
use App\Models\Students;
use App\Models\Municipal;
use Faker\Provider\sv_SE\Municipality;

class StudentsProfile extends Component
{
    public $student_id;
    public $last_name;
    public $selectedResult;
    public $recentReports;
    //profile
    public $m_name, $suffix, $nickname, $age, $sex, $birthdate,
    $contact, $birth_order, $number_of_siblings, $religion, $mother_tongue, $four_ps,
    $guardian_name, $relationship, $guardian_contact, $occupation, $guardian_address,
    $guardian_age, $favorite_subject, $difficult_subject, $school_organization, $graduation_plan,
    $height, $weight, $bmi;
    //father
    public $father_type, $father_name, $father_age, $father_occupation, $father_contact, $father_office_contact,
    $father_monthly_income, $father_birth_place, $father_work_address;
    //mother
    public $mother_type, $mother_name, $mother_age, $mother_occupation, $mother_contact, $mother_office_contact,
    $mother_monthly_income, $mother_birth_place, $mother_work_address;
    public $medicines;
    public $parent_statuses = [];
    public $vitamins = [];
    public $education = [];
    public $operations = [];
    public $accidents = [];
    public $hasDisability = 'No'; //
    public $disability;
    public $hasFoodAllergy = 'No';
    public $foodAllergy;
    public $plans = [];
    public $selectedCity;
    public $selectedMunicipality;
    public $selectedBarangay;
    public $municipalities = []; // Initialize as empty array
    public $barangays = [];
    public $rewards = [];
    public $siblings = [
        ['name' => '', 'age' => '', 'gradeSection' => ''],
    ];
    public $living_with = null;

    public function updatedLivingWith($value)
    {
        // Set the other checkboxes to null when one is selected
        if ($value === 'both-parents') {
            $this->living_with = 'both-parents';
        } elseif ($value === 'father-only') {
            $this->living_with = 'father-only';
        } elseif ($value === 'mother-only') {
            $this->living_with = 'mother-only';
        } elseif ($value === 'na') {
            $this->living_with = 'na';
        }
    }

    public function mount()
    {

        if (empty($this->rewards)) {
            $this->rewards = [['award' => '', 'year' => '']];
        }
        // Check if there are existing siblings, if not, add an initial empty sibling
        if (empty($this->siblings)) {
            $this->siblings = [['name' => '', 'age' => '', 'gradeSection' => '']];
        }
    }

    public function addSibling()
    {
        $this->siblings[] = ['name' => '', 'age' => '', 'gradeSection' => ''];
    }

    public function removeSibling($index)
    {
        unset($this->siblings[$index]);
        $this->siblings = array_values($this->siblings); // Re-index the array after removing a sibling
    }

    public function updatedSelectedCity($cityId)
    {
        $this->municipalities = Municipality::where('city_id', $cityId)->get();
        $this->selectedMunicipality = null;
        $this->selectedBarangay = null;
        $this->barangays = [];
    }
    public function updatedSelectedMunicipality($municipalityId)
    {
        $this->barangays = Barangay::where('municipality_id', $municipalityId)->get();
        $this->selectedBarangay = null;
    }

    public function getSearchResults()
    {
        if (strlen($this->student_id) >= 3) {
            $searchTerm = '%' . strtolower($this->student_id) . '%';
            return Students::whereRaw('LOWER(CONCAT(first_name, " ", last_name)) LIKE ?', [$searchTerm])
                ->get(['id', 'first_name', 'last_name']);
        }

        return collect([]);
    }
    public function selectResult($result)
    {
        $this->selectedResult = $result;

        // Fetch the student record based on the selected first name
        $student = Students::with('anecdotal')->where('first_name', $this->selectedResult)->first();

        if ($student) {
            $this->selectedName = $student->first_name . ' ' . $student->last_name;
            $this->student_id = $student->id; // Set the student_id with the ID of the selected student
            // Fetch all reports for the selected student
            $this->allReports = $student->anecdotal()->with('student')->get();
        }
    }
    public function render()
    {
        $provinces = Province::all();
        $searchResults = $this->getSearchResults();
        return view('livewire.admin.students-profile', compact('searchResults', 'provinces'))
            ->extends('layouts.dashboard.index')
            ->section('content');
    }
    public function save()
    {
        $combinedDisability = $this->hasDisability === 'Yes' ? 'Yes: ' . $this->disability : 'No';
        $combinedFoodAllergy = $this->hasFoodAllergy ? 'Yes: ' . $this->foodAllergy : 'No';
        $profile = Profile::create([
            'student_id' => $this->student_id,
            'm_name' => $this->m_name,
            'suffix' => $this->suffix,
            'nickname' => $this->nickname,
            'age' => $this->age,
            'sex' => $this->sex,
            'birthdate' => $this->birthdate,
            'contact' => $this->contact,
            'religions' => $this->religion,
            'mother_tongue' => $this->mother_tongue,
            '4ps' => $this->four_ps,
            'living_with' => $this->living_with,
            'guardian_name' => $this->guardian_name,
            'relationship' => $this->relationship,
            'guardian_contact' => $this->guardian_contact,
            'occupation' => $this->occupation,
            'guardian_age' => $this->guardian_age,
            'favorite_subject' => $this->favorite_subject,
            'difficult_subject' => $this->difficult_subject,
            'school_organization' => $this->school_organization,
            'graduation_plan' => $this->graduation_plan,
            'height' => $this->height,
            'weight' => $this->weight,
            'bmi' => $this->bmi,
            'disability' => $combinedDisability,
            'food_allergy' => $combinedFoodAllergy,
        ]);

        $profile->parents()->create([
            'type' => $this->type,
            'parent_name' => $this->parent_name,
            'parent_age' => $this->parent_age,
            'parent_occupation' => $this->parent_occupation,
            'parent_contact' => $this->parent_contact,
            'parent_office_contact' => $this->parent_office_contact,
            'parent_monthly_income' => $this->parent_monthly_income
        ]);

        $profile->siblings()->create([
            'sibling_name' => $this->sibling_name,
            'sibling_age' => $this->sibling_age,
            'sibling_grade_section' => $this->sibling_grade_section
        ]);

        $profile->parent_status()->create([
            'parent_status' => $this->parent_status
        ]);

        $profile->awards()->create([
            'award_name' => $this->award_name,
            'award_year' => $this->award_year
        ]);

        $profile->vitamins()->create([
            'vitamins' => $this->vitamins
        ]);
        $profile->medicines()->create([
            'medicines' => $this->medicines
        ]);
        $profile->operations()->create([
            'operations' => $this->operations
        ]);

        $this->redirect('dashboard');

    }

}
