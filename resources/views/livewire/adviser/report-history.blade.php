<x-form title="">
    <x-slot name="actions">
      @if (auth()->user()->role == 0)
      <x-link :href="url('report/history')">
        Back
    </x-link>
        @elseif(auth()->user()->role == 2)
    <x-link :href="url('adviser/report/history')">
        Back
    </x-link>
    @endif
</x-slot>

    <x-slot name="slot">
        <form wire:submit.prevent="update" enctype="multipart/form-data">
            @csrf
            <h6 class="text-sm mt-2 px-4 font-bold uppercase ">
                Student Information
            </h6>
            <x-grid columns="2" gap="4" px="0" mt="2">

                <div class="w-full px-4 inline">
                    <div x-data="{ isOpen: @entangle('isOpen'), studentName: @entangle('studentName') }">
                        <x-label for="studentName">
                            First Name
                        </x-label>
                        <div class="relative">
                            <x-input wire:model.debounce.300ms="studentName" @focus="isOpen = true"
                                @click.away="isOpen = false" @keydown.escape="isOpen = false" @keydown="isOpen = true"
                                type="text" id="studentName" name="studentName"
                                placeholder="Start typing to search..." />

                            <x-error fieldName="studentId" />
                            <span x-show="studentName !== ''" @click="studentName = ''; isOpen = false"
                                class="absolute right-3 top-2 cursor-pointer text-red-600 font-bold">
                                &times;
                            </span>
                            @if ($studentName && count($students) > 0)
                                <ul class="bg-white border border-gray-300 mt-2 rounded-md w-full max-h-48 overflow-auto absolute z-10"
                                    x-show="isOpen">
                                    @foreach ($students as $student)
                                        <li class="px-4 py-2 cursor-pointer hover:bg-gray-200"
                                            wire:click="selectStudent('{{ $student->id }}', '{{ $student->first_name }} {{ $student->last_name }}')"
                                            x-on:click="isOpen = false">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <input type="hidden" name="studentId" wire:model="studentId">
                    </div>
                </div>


                <div class="w-full px-4">
                    <x-label>
                        Grade Level
                    </x-label>
            <x-input disabled wire:model="grade_level"/>
                </div>



            </x-grid>


            <h6 class="text-sm my-1 px-4 font-bold uppercase mt-5">
                Case Information
            </h6>
            <x-grid columns="2" gap="4" px="0" mt="2">

                <div class="w-full px-4">
                    <x-label>
                        Referred By
                    </x-label>
                    <x-input wire:model="user_id" disabled />
                </div>


                <div class="w-full px-4">
                    <x-label>
                        Offenses
                    </x-label>
                    <x-select name="offenses_id" wire:model="offense_id">
                        <option>Select an offense</option>
                        @foreach ($offenses as $offenseId => $offenseName)
                            <option value="{{ $offenseId }}"
                                {{ $report->anecdotal->offense_id == $offenseId ? 'selected' : '' }}>
                                {{ $offenseName }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-error fieldName="offenses_id" />
                </div>



            </x-grid>



            <x-grid columns="3" gap="4" px="0" mt="4">
                <div class="w-full px-4">
                    <div class="relative mb-3">
                        <x-label>
                            Observation
                        </x-label>
                        <x-input wire:model="observation" />

                        <x-error fieldName="observation" />

                    </div>
                </div>

                <div class="w-full px-4">
                    <div class="relative mb-3">
                        <x-label>
                            Desired
                        </x-label>
                        <x-input wire:model="desired" />
                        <x-error fieldName="desired" />

                    </div>
                </div>

                <div class="w-full px-4">
                    <div class="relative mb-3">
                        <x-label>
                            Outcome
                        </x-label>
                        <x-input wire:model="outcome" type="text" name="outcome" />
                        <x-error fieldName="outcome" />

                    </div>
                </div>
            </x-grid>


            <h6 class="text-sm my-1 px-4 font-bold uppercase mt-3 ">
                Additional Information
            </h6>

            <x-grid columns="2" gap="4" px="0" mt="2">


                <div class="w-full px-4">
                    <div class="relative mb-3">
                        <x-label>
                            Gravity of offense
                        </x-label>
                        <x-select wire:model="gravity">
                            @foreach($gravityOptions as $value => $name)
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endforeach
                        </x-select>
                        <x-error fieldName="gravity" />
                    </div>
                </div>


                <div class="w-full px-4">
                    <div class="relative mb-3">
                        <x-label>
                            Remarks (Short Description)
                        </x-label>
                        <x-input wire:model="short_description" type="text" />
                        <x-error fieldName="short_description" />

                    </div>
                </div>
            </x-grid>


                {{-- <div class="w-full px-4">
                    <x-label>Letter</x-label>
                    <input type="file" name="images[]" wire:model="letter" multiple
                    class="block w-full border border-gray-200 shadow-sm rounded-md text-sm
                    file:bg-transparent file:border-0
                    file:bg-gray-100 file:mr-4
                    file:py-2.5 file:px-4">
                    <x-error fieldName="letter" />
                    <div x-data="{ isZoomed: false }" x-clock class="flex space-x-2 mt-2">
                        @if ($report->anecdotal->images->isNotEmpty())
                            @foreach ($report->anecdotal->images as $image)
                                <div class="relative">
                                    <a href="{{ asset('storage/' . $image->images) }}" target="_blank" rel="noopener noreferrer">
                                        <img src="{{ asset('storage/' . $image->images) }}" alt="Anecdotal Image"
                                             class="w-32 h-32 object-cover border border-gray-200 rounded cursor-pointer">
                                    </a>
                                    <button type="button" class="absolute top-0 right-0 text-red-500 font-bold text-xs hover:underline"
                                            wire:click="deleteImage({{ $image->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                             fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                              </svg>

                                        </button>
                                </div>


                                {{-- <button type="button" class="text-red-500 hover:underline"
                                    style="background: none; border: none; padding: 0; cursor: pointer;"
                                    wire:click="deleteImage({{ $image->id }})">
                                Delete
                            </button> -
                            @endforeach
                        @else
                            <div>
                                <p class="font-medium text-sm text-gray-600 text-left">No Images Uploaded</p>
                            </div>
                        @endif
                    </div>
                </div> --}}


                <div class="w-full px-4">

                    <x-label>Story</x-label>
                        <textarea id="message" rows="4" wire:model="story"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50
rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Write the story behind the report here"></textarea>
                        <x-error fieldName="story" />



                </div>

            <h6 class="text-sm my-1 px-4 font-bold uppercase mt-5 ">
                Actions Taken <x-error fieldName="selectedActions" />
            </h6>

            <x-grid columns="4" gap="4" px="0" mt="4">
                @foreach ($actions as $action)
                    <div class="relative mb-1 px-4">
                        <div class="flex items-center space-x-2">
                            <x-checkbox wire:model="selectedActions" value="{{ $action->action_taken }}" />
                            <x-label>{{ $action->action_taken }}</x-label>
                        </div>
                    </div>
                @endforeach
            </x-grid>



            <div class="flex justify-end items-center px-4">
                <x-text-alert />
                <div wire:loading wire:target="update" class="mx-4">
                    Loading
                </div>
                <x-button type="submit" wire:loading.attr="disabled">Update</x-button>
            </div>
        </form>
    </x-slot>

    </div>

    </div>
</x-form>
