@extends('layouts.dashboard.index')
@section('content')
    <x-form title="">
        <x-slot name="actions">
            <x-slot name="actions">
                @if (auth()->user()->role === 0)
                    <x-link :href="url('report/history')">
                        Back
                    </x-link>
                @elseif(auth()->user()->role === 2)
                    <x-link :href="url('adviser/report/history')">
                        Back
                    </x-link>
                @endif
            </x-slot>

            <x-slot name="slot">
                <h6 class="text-sm mt-4 px-4 font-bold uppercase">
                    Student Information
                </h6>
                <x-grid columns="2" gap="4" px="0" mt="2">

                    <div class="w-full px-4">

                        <x-label>
                            Student Name
                        </x-label>
                        <x-input type="text" name="offenses"
                            value="{{ $report->anecdotal->student->first_name }} {{ $report->anecdotal->student->last_name }}"
                            disabled />
                    </div>




                    <div class="w-full px-4">


                        <x-label>
                            Grade Level
                        </x-label>
                        <x-input type="text" name="offenses"
                            value="Grade: {{ $report->anecdotal->student->classroom->grade_level }} {{ $report->anecdotal->student->classroom->section }}"
                            disabled />
                    </div>


                </x-grid>

                <h6 class="text-sm mt-4 px-4 font-bold uppercase">
                    Case Information
                </h6>

                <x-grid columns="2" gap="4" px="0" mt="3">

                    <div class="w-full px-4">
                        <x-label>
                            Referred By
                        </x-label>
                        <x-input value="{{ $report->users?->name ?? 'No Reporter Found' }}" disabled />
                    </div>



                    <div class="w-full px-4">
                        <x-label>
                             Offenses
                        </x-label>
                        <x-input value="{{ $report->anecdotal?->offenses->offenses ?? 'No Offenses Found' }}"
                            disabled />
                            <span class="text-red-500 text-sm"> Offense type: {{ $report->anecdotal->offenses->category === 0 ? 'Minor' : 'Grave' }}</span>

                    </div>
                </x-grid>



                <x-grid columns="3" gap="4" px="0" mt="4">
                    <div class="w-full px-4">
                        <div class="relative mb-3">
                            <x-label>
                                Observation
                            </x-label>
                            <x-input value="{{ $report->anecdotal?->observation ?? 'No Observation' }}" disabled />


                        </div>
                    </div>

                    <div class="w-full px-4">
                        <div class="relative mb-3">
                            <x-label>
                                Desired
                            </x-label>
                            <x-input value="{{ $report->anecdotal?->desired ?? 'No Desired Observation' }}" disabled />
                        </div>
                    </div>

                    <div class="w-full px-4">
                        <div class="relative mb-3">
                            <x-label>
                                Outcome
                            </x-label>
                            <x-input type="text" name="outcome" disabled
                                value="{{ $report->anecdotal?->outcome ?? 'No Outcome Observation' }}" />
                        </div>
                    </div>
                </x-grid>


                <h6 class="text-sm mt-4 px-4 font-bold uppercase">
                    Additional Information
                </h6>

                <x-grid columns="3" gap="4" px="0" mt="2">


                    <div class="w-full px-4">
                        <div class="relative mb-3">
                            <x-label>
                                Gravity of offense
                            </x-label>
                            <x-input disabled type="text" name="gravity"
                                value="{{ $report->anecdotal?->getGravityTextAttribute() ?? 'No Data' }}" />
                        </div>
                    </div>

                    <div class="w-full px-4">
                        <div class="relative mb-3">
                            <x-label>
                                Remarks (Short Description)
                            </x-label>
                            <x-input disabled type="text"
                                value="{{ $report->anecdotal?->short_description ?? 'No Data' }}" />

                        </div>
                    </div>

                    <div class="w-full px-4">
                        <x-label>
                            Date Reffered
                        </x-label>
                        <x-input value="{{ $report ? $report->created_at->format('F j, Y') : 'No Data Found' }}" disabled />

                    </div>

                </x-grid>



                {{-- <div class="w-full px-4">
                    <x-label>Letter</x-label>
                    <div x-data="{ isZoomed: false }" x-clock  class="flex space-x-2 mt-2">
                        @if ($report->anecdotal->images->isNotEmpty())
                            @foreach ($report->anecdotal->images as $image)
                                <a href="{{ asset('storage/' . $image->images) }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{ asset('storage/' . $image->images) }}" alt="Anecdotal Image"
                                         class="w-32 h-32 object-cover border border-gray-200 rounded cursor-pointer">
                                </a>
                            @endforeach
                        @else
                        <div>
                            <p class=" font-medium text-sm
                            text-gray-600 text-left">No Images Uploaded</p>
                        </div>
                        @endif
                    </div>
                </div> --}}




                <div class="w-full px-4">

                    <x-label>Story</x-label>
                    <textarea id="message" rows="4" disabled
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50
rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Write the story behind the report here">{{ $report->anecdotal?->story ?? 'No Data' }}</textarea>


                </div>



                <x-grid columns="2" gap="4" px="0" mt="4">
                </x-grid>


                <h6 class="text-sm my-1 px-4 font-bold uppercase mt-3 ">
                    Actions Taken
                </h6>

                <x-grid columns="4" gap="2" px="0" mt="4">


                            @if ($report->anecdotal && $report->anecdotal->actionsTaken->isNotEmpty())
                                @foreach ($report->anecdotal->actionsTaken as $action)
                                <div class="relative mb-3 px-4">
                                    <div class="flex items-center space-x-2">
                                    <x-checkbox checked disabled />
                                    <x-label>{{ $action->actions }}</x-label>
                                </div>
                            </div>
                                @endforeach
                            @else
                                No Action Taken Found
                            @endif

                </x-grid>
            </x-slot>

            </div>

            </div>
    </x-form>




    <div class="w-full mx-auto mt-6">
        @if (
            $report->anecdotal->case_status == 2 ||
                $report->anecdotal->case_status == 3 ||
                $report->anecdotal->case_status == 4)
            <div class="relative flex flex-col min-w-0 py-4 break-words w-full mb-6 shadow-md rounded-lg border-0 ">
                <div class="flex-auto px-6 py-2 lg:px-10  pt-0">
                    <h6 class="text-sm my-1 px-4 font-bold uppercase ">
                        Meeting Outcome Update
                    </h6>

                    <x-grid columns="3" gap="4" px="0" mt="4">
                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>
                                    Meeting Outcomes
                                </x-label>
                                <x-input disabled
                                    value="{{ $report->anecdotal->actions?->getActionTextAttribute() ?? 'No Data' }}" />
                            </div>
                        </div>
                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>
                                    Remarks (Short Description)
                                </x-label>
                                <x-input disabled
                                    value="{{ $report->anecdotal->actions?->outcome_remarks ?? 'No Data' }}" />
                            </div>
                        </div>
                        <div class="w-full px-4">
                            <div class="relative mb-3">
                                <x-label>
                                    Action Taken
                                </x-label>
                                <x-input disabled value="{{ $report->anecdotal->actions?->action ?? 'No Data' }}" />
                            </div>
                        </div>


                        <div class="w-full px-4">
                            <x-label>Images</x-label>
                            <div x-data="{ isZoomed: false }" x-clock class="flex space-x-2 mt-2 ">
                                @if ($report->anecdotal->images->isNotEmpty())
                                    @foreach ($report->anecdotal->images as $image)
                                        <div class="relative">
                                            <a href="{{ asset('storage/' . $image->images) }}" target="_blank"
                                                rel="noopener noreferrer">
                                                <img src="{{ asset('storage/' . $image->images) }}" alt="Anecdotal Image"
                                                    class="w-32 h-32 object-cover border border-gray-200 rounded cursor-pointer">
                                            </a>

                                        </div>
                                    @endforeach
                                @else
                                <div>
                                    <p class="text-sm text-red-500 text-left">
                                        No promissory note uploaded</p>
                                </div>
                                @endif
                            </div>
                        </div>

                    </x-grid>


                    @if ($report->anecdotal->case_status === 2)
                        <div class="flex justify-end items-center mx-4">
                            <p class="text-md text-green-500">
                                The case was resolved on {{ $report->anecdotal->updated_at->format('F j, Y') }}
                            </p>
                        </div>
                    @elseif ($report->anecdotal->case_status === 3)
                        <div class="flex justify-end items-center mx-4">
                                 <p class="text-md text-green-500">
                                The case is still under follow-up, and the meeting occurred on
                                {{ $report->anecdotal->updated_at->format('F j, Y') }}
                            </p>
                        </div>
                    @elseif ($report->anecdotal->case_status === 4)
                        <div class="flex justify-end items-center mx-4">
                            <p class="text-md text-red-500">
                                The case requires referral to another party.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>




@endsection
