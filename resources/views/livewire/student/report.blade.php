<div>
    <div class="mx-auto">
        <div class="flex justify-end  items-center">
            @if (auth()->user()->role == 1)
                <x-link href="{{ url('admin/reports') }}">
                    Back
                </x-link>
            @endif
        </div>

        <div class="w-full mx-auto mt-6">
            <div class="relative flex flex-col min-w-0 py-2 break-words w-full mb-2 shadow-md rounded-lg border-0 ">

                <div class="flex-auto px-6 lg:px-10 py-10 pt-0">

                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        @csrf
                        <h6 class="text-sm mt-4 px-1 md:px-4 font-bold uppercase">
                            Reffer Student
                        </h6>
                        <x-grid columns="2" gap="4" px="0" mt="2">


                            <div class="w-full px-1 md:px-4">
                                <div x-data="{ isOpen: @entangle('isOpen'), studentName: @entangle('studentName') }">
                                    <x-label for="studentName">
                                        Student Name  <x-required/>
                                    </x-label>
                                    <div class="relative">
                                        <x-input required wire:model.debounce.300ms="studentName" @focus="isOpen = true"
                                            @click.away="isOpen = false" @keydown.escape="isOpen = false"
                                            @keydown="isOpen = true" type="text" id="studentName" name="studentName"
                                            placeholder="Type at least 3 words to search" />
                                        <x-error fieldName="studentName" />
                                        <x-error fieldName="studentId" />

                                        @if ($studentName && $studentId)

                                            @if (count($cases) > 0)
                                                @php
                                                    $totalOffenses = 0;
                                                    $totalPending = 0;
                                                    $totalProcess = 0;
                                                    $totalResolved = 0;
                                                    $totalFollowUp = 0;
                                                    $totalRefferral = 0;
                                                @endphp
                                                @foreach ($cases as $case)
                                                    @if ($case->offenses)
                                                        @php
                                                            $totalOffenses += 1;
                                                            if ($case->case_status == 0) {
                                                                $totalPending += 1;
                                                            } elseif ($case->case_status == 1) {
                                                                $totalProcess += 1;
                                                            } elseif ($case->case_status == 2) {
                                                                $totalResolved += 1;
                                                            } elseif ($case->case_status == 3) {
                                                                $totalFollowUp += 1;
                                                            } elseif ($case->case_status == 4) {
                                                                $totalRefferral += 1;
                                                            }
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                {{-- Default: text-md --}}
                                                <span class="text-red-500 text-sm text-center mt-3">Total offense:
                                                    {{ $totalOffenses }}.
                                                    Pending: {{ $totalPending }}, Ongoing: {{ $totalProcess }},
                                                    Resolved: {{ $totalResolved }},
                                                    FollowUp: {{ $totalFollowUp }}, Refferal: {{ $totalRefferral }}
                                                </span>
                                            @else
                                                <span class="text-green-500 text-md text-center">
                                                    No cases found for {{ $studentName }}
                                                </span>
                                            @endif

                                        @endif

                                        <span x-show="studentName !== ''" @click="studentName = ''; isOpen = false"
                                            class="absolute right-3 top-2 cursor-pointer text-red-600 font-bold">
                                            &times;
                                        </span>

                                        <div wire:loading wire:target="selectStudent">
                                            <span class="text-sm text-green-500">
                                                Loading...
                                            </span>
                                        </div>
                                        @if ($studentName && count($students) > 0)
                                            <ul class="bg-white border border-gray-300 mt-2 rounded-md w-full max-h-48 overflow-auto absolute z-10"
                                                x-show="isOpen">
                                                @foreach ($students as $student)
                                                    <li class="px-4 py-2 cursor-pointer hover:bg-gray-200"
                                                        wire:click="selectStudent('{{ $student->id }}',
                                                         '{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}')"
                                                        x-on:click="isOpen = false">
                                                        {{ $student->first_name }} {{ $student->middle_name }}
                                                        {{ $student->last_name }}
                                                    </li>
                                                @endforeach
                                            @elseif ($studentName)
                                                <div
                                                    class="px-4 py-2 cursor-pointer hover:bg-gray-200 bg-white border border-gray-300 mt-2 rounded-md w-full">
                                                    No Student Found

                                                </div>
                                            </ul>
                                        @endif
                                    </div>
                                    <input type="hidden" name="studentId" wire:model="studentId">
                                </div>
                            </div>




                            <div class="w-full px-1 md:px-4">
                                <x-label>
                                    Classroom<x-required/>
                                </x-label>
                                <x-input disabled :value="$classroom && $classroom->section
                                    ? 'Grade: ' . $classroom->grade_level . ' ' . $classroom->section
                                    : ''" />
                            </div>


                        </x-grid>



                        <h6 class="text-sm mt-4 px-1 md:px-4 font-bold uppercase">

                            Case Information
                        </h6>

                        <x-grid columns="2" gap="4" px="0" mt="2">



                            <div class="w-full px-1 md:px-4">
                                <div class="relative mb-3">
                                    <x-label>
                                        Referred By <x-required/>
                                    </x-label>
                                    <x-input type="text" name="offenses" placeholder="{{ Auth()->user()->name }}"
                                        wire:model="user_id" value="{{ Auth()->user()->id }}" disabled />
                                </div>
                            </div>


                            <div class="w-full px-1 md:px-4">
                                <x-label>
                                    Offense  <x-required/>
                                </x-label>
                                <x-select name="offense_id" wire:model="offense_id" required>
                                    @if ($offenses)
                                        @foreach ($offenses as $id => $offense)
                                            <option value="{{ $id }}">{{ $offense }}</option>
                                        @endforeach
                                    @endif
                                </x-select>

                                <x-error fieldName="offense_id" />

                            </div>



                        </x-grid>


                        <x-grid columns="3" gap="4" px="0" mt="4">
                            <div class="w-full px-1 md:px-4">
                                    <x-label>
                                        Observation <span id="popover-trigger" class="cursor-pointer text-red-500">?</span>
                                    </x-label>


                                    <div id="popover" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">

                                        <div class="px-3 py-2">
                                            <p>Your obervation from that student</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>


                                    <x-input type="text" name="observation" wire:model="observation" />
                                    <x-error fieldName="observation" />

                                </div>


                            <div class="w-full px-1 md:px-4">
                                <div class="relative mb-3">
                                    <x-label>
                                        Desired <span id="desired-popover-trigger" class="cursor-pointer text-red-500">?</span>
                                    </x-label>
                                    <div id="desired-popover" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">

                                        <div class="px-3 py-2">
                                            <p>Your desired thing that you want to happen to that student</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                    <x-input type="text" name="desired" wire:model="desired" />
                                    <x-error fieldName="desired" />

                                </div>
                            </div>

                            <div class="w-full px-1 md:px-4">
                                <div class="relative mb-3">
                                    <x-label>
                                        Outcome <span id="outcome-popover-trigger" class="cursor-pointer text-red-500">?</span>
                                    </x-label>

                                    <div id="outcome-popover" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">

                                        <div class="px-3 py-2">
                                            <p>The outcome you want happen.</p>
                                        </div>
                                        <div data-popper-arrow></div>
                                    </div>
                                    <x-input type="text" name="outcome" wire:model="outcome" />
                                    <x-error fieldName="outcome" />

                                </div>
                            </div>
                        </x-grid>



                        <h6 class="text-sm my-1 px-1 md:px-4 font-bold uppercase mt-3 ">
                            Additional Information
                        </h6>

                        <x-grid columns="2" gap="4" px="0" mt="2">


                            <div class="w-full px-1 md:px-4">
                                <div class="relative mb-3">
                                    <x-label>
                                        Gravity of offense  <x-required/>
                                    </x-label>
                                    <x-select name="gravity" wire:model="gravity" required>
                                        <option value="0">Low Severity</option>
                                        <option value="1">Moderate Severity</option>
                                        <option value="2">Medium Severity</option>
                                        <option value="3">High Severity</option>
                                        <option value="4">Critical Severity</option>
                                    </x-select>
                                    <x-error fieldName="gravity" />


                                </div>
                            </div>

                            <div class="w-full px-1 md:px-4">
                                <div class="relative mb-3">
                                    <x-label>
                                        Remarks (Short Description)
                                    </x-label>
                                    <x-input type="text" name="short_description"
                                        wire:model="short_description" />
                                    <x-error fieldName="short_description" />

                                </div>
                            </div>




                        </x-grid>

                        <div class="w-full px-1 md:px-4">

                            <x-label>Story</x-label>
                            <textarea id="message" rows="4" wire:model="story"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50
rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Write the story behind the report here"></textarea>
                            <x-error fieldName="story" />


                        </div>


                        <h6 class="text-sm mt-4 px-1 md:px-4 font-bold uppercase">


                            Actions Taken <x-required/> <x-error fieldName="selectedActions" />
                        </h6>

                        <x-grid columns="3" gap="4" px="0" mt="4">
                            @forelse ($actions as $action)
                                <div class="w-full px-1 md:px-4 inline-flex space-x-3">
                                    <x-checkbox wire:model="selectedActions" value="{{ $action->action_taken }}" />
                                    <x-label>{{ $action->action_taken }}</x-label>

                                </div>
                            @empty
                                <p>No Data</p>
                            @endforelse

                        </x-grid>

                        <div class="flex justify-end items-center space-x-2 px-4 mt-10">
                            <x-text-alert />
                            <div wire:loading wire:target="store" class="mx-4">
                                Loading...
                            </div>
                    {{-- functoin secetc --}}
                            {{-- <x-buttontype wire:loading.attr="disabled" wire:click="resetReport"
                                class="bg-red-500 text-white hover:bg-red-600">Clear</x-buttontype> --}}
                            <x-button type="submit" wire:loading.attr="disabled">Submit</x-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const trigger = document.getElementById('popover-trigger');
            const popover = document.getElementById('popover');

            trigger.addEventListener('click', function () {
                popover.classList.toggle('invisible');
                popover.classList.toggle('opacity-0');
            });

            document.addEventListener('click', function (event) {
                if (!popover.contains(event.target) && !trigger.contains(event.target)) {
                    popover.classList.add('invisible');
                    popover.classList.add('opacity-0');
                }
            });
        });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const desiredPopoverTrigger = document.getElementById('desired-popover-trigger');
        const desiredPopover = document.getElementById('desired-popover');

        desiredPopoverTrigger.addEventListener('click', function () {
            desiredPopover.classList.toggle('invisible');
            desiredPopover.classList.toggle('opacity-0');
        });

        document.addEventListener('click', function (event) {
            if (!desiredPopover.contains(event.target) && !desiredPopoverTrigger.contains(event.target)) {
                desiredPopover.classList.add('invisible');
                desiredPopover.classList.add('opacity-0');
            }
        });



    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const outcomePopoverTrigger = document.getElementById('outcome-popover-trigger');
        const outcomePopover = document.getElementById('outcome-popover');

        outcomePopoverTrigger.addEventListener('click', function () {
            outcomePopover.classList.toggle('invisible');
            outcomePopover.classList.toggle('opacity-0');
        });

        document.addEventListener('click', function (event) {
            if (!outcomePopover.contains(event.target) && !outcomePopoverTrigger.contains(event.target)) {
                outcomePopover.classList.add('invisible');
                outcomePopover.classList.add('opacity-0');
            }
        });
    });
</script>

</div>


