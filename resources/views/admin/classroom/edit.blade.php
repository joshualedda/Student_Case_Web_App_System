@extends('layouts.dashboard.index')
@section('content')

    <div>
        <x-form title="">
            <x-slot name="actions">
                <x-link href="{{ url('admin/settings/classrooms') }}">
                    Back
                </x-link>
            </x-slot>

            <x-slot name="slot">
                <form id="classForm" action="{{ route('classrooms.update.single', ['classroom' => $classroom]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <h6 class="text-sm mt-3 mb-6 px-4 font-bold uppercase">
                        Update Classroom
                    </h6>

                    <!-- Personal information form fields -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>Classroom</x-label>
                                <x-select name="classroom" required>
                                    @foreach ($classrooms as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $class->id == $classroom->id ? 'selected' : '' }}>
                                            Grade: {{ $class->grade_level }} {{ $class->section }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-error fieldName="classroom" />
                            </div>
                        </div>

                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>Adviser</x-label>
                                <x-select name="employee_id" required>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ $emp->id == $classroom->employee_id ? 'selected' : '' }}>
                                            {{ $emp->employees }}
                                        </option>
                                    @endforeach
                                </x-select>
                                <x-error fieldName="employee_id" />
                            </div>
                        </div>

                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>Total Cases</x-label>
                                <x-input value="{{ $classroom->countStudentsAnecdotal() }}" disabled/>
                            </div>
                        </div>
                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>Status</x-label>
                                <x-select name="status" required>
                                    <option value="0" {{ $classroom->status == 0 ? 'selected' : '' }}>Active</option>
                                    <option value="1" {{ $classroom->status == 1 ? 'selected' : '' }}>Inactive</option>
                                </x-select>
                                <x-error fieldName="status" />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center ">
                        <span id="classAlert" class="text-green-500 mx-2"></span>
                        <x-button type="submit">Update Classroom</x-button>
                    </div>
                </form>
            </x-slot>


        </x-form>


        @if ($classroom->students)

        <x-form title="">
            <x-slot name="actions">

            </x-slot>

            <x-slot name="slot">
                <form id="updateStudentsForm" action="{{ route('classrooms.students.update', ['classroom' => $classroom]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6 class="text-sm mt-3 mb-6  font-bold uppercase">
                        Referr Students
                    </h6>


                    @forelse ($classroom->students as $student)
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="relative mb-3">
                                    <x-label>Student Name</x-label>
                                    <x-input value="{{ $student->first_name }} {{ $student->last_name }}" disabled />
                                </div>


                                <div class="relative mb-1">
                                    <x-label>Total Cases </x-label>
                                    <x-input
                        value="Pending: {{ $student->anecdotal->where('case_status', 0)->count() }}, Ongoing: {{ $student->anecdotal->where('case_status', 1)->count() }}, Resolved: {{ $student->anecdotal->where('case_status', 2)->count()}}, Follow-Up {{ $student->anecdotal->where('case_status', 3)->count() }}, Reffer: {{ $student->anecdotal->where('case_status', 4)->count() }} " disabled />
                                </div>



                                <div class="relative mb-3">
                                    <x-label>Referred to Classroom</x-label>
                                    <x-select name="students[{{ $student->id }}][classroom_id]">
                                        @foreach ($higherClass as $class)
                                            <option value="{{ $class->id }}"
                                                {{ $class->id == $classroom->id ? 'selected' : '' }}>
                                                Grade: {{ $class->grade_level }} {{ $class->section }}
                                            </option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                            @empty
                            <div class="flex justify-center mx-auto ">
                                <p>No student found for this classroom</p>
                            </div>
                            @endforelse

                    <div class="flex justify-end items-center">
                        <span class="text-red-500">Please review the reffered student before reloading the page</span>
                        <span id="successMessage" class="text-green-500 mx-4"></span>
                        <span id="loadingMessage" class="text-green-500 mx-4"></span>
                        <x-button id="updateStudentsButton" type="submit">Submit</x-button>
                    </div>

                </form>


            </x-slot>

        </x-form>



        @endif

    </div>
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#updateStudentsForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#successMessage').text(response.message);
                        setTimeout(function () {
                            $('#successMessage').text('');
                        }, 5000);
                    },
                    error: function (error) {
                        console.log('Error:', error);
                        alert('An error occurred while updating students');
                    }
                });
            });
        });
    </script>
    {{-- Classroom Update --}}
    <script>
        $(document).ready(function () {
            $('#classForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'PUT',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#classAlert').text(response.success);
                        setTimeout(function () {
                            $('#classAlert').text('');
                        }, 5000);
                    },
                });
            });
        });
    </script>
<script>
    $(document).ready(function () {
        $('#updateStudentsForm').submit(function (e) {
            e.preventDefault();

            $('#loadingMessage').text('Loading...');

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (response) {
                    $('#successMessage').text(response.message);
                    setTimeout(function () {
                        $('#successMessage').text('');
                    }, 5000);

                    $('#loadingMessage').text('');
                },
                error: function (error) {
                    console.log('Error:', error);
                    alert('An error occurred while updating students');

                    $('#loadingMessage').text('');
                }
            });
        });
    });
</script>

@endsection
