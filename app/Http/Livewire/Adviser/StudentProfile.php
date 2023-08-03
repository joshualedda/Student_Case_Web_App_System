<?php

namespace App\Http\Livewire\Adviser;

use App\Models\Profile;
use Livewire\Component;
use App\Models\Anecdotal;
use Illuminate\Support\Facades\Auth;

class StudentProfile extends Component
{
    public function render()
    {
        $user = Auth::user();
        $students = $user->students;

        $studentProfile = Profile::whereIn('student_id', $students->pluck('id'))->get();

        return view('livewire.adviser.student-profile', compact('studentProfile'))
            ->extends('layouts.dashboard.index')
            ->section('content');
    }


}
