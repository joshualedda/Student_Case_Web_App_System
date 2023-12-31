@extends('layouts.dashboard.index')
@section('content')

<x-form title="">
    <x-slot name="actions">

        <x-link :href="url('admin/settings/audit-trail')">
            Back
        </x-link>
    </x-slot>

    <x-slot name="slot">

        <h6 class="text-sm mt-3 mb-6 px-4 font-bold uppercase">
           Details
        </h6>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>Causer Name</x-label>
                    <x-input  disabled value="{{ $activity->users->name }}" />
                </div>
            </div>


            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label for="section">Desciption</x-label>
                    <x-input value="{{ $activity->description }}" disabled/>

                </div>
            </div>

            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>Date</x-label>
                    @if($activity->event === 'updated')
                    <x-input value="{{ $activity->updated_at->format('F j, Y') }}" disabled />
                     @elseif($activity->event === 'created')
                    <x-input value="{{ $activity->created_at->format('F j, Y') }}" disabled />
                    @endif
                </div>
            </div>

        </div>

        @if ($activity->event === 'updated')
        <h6 class="text-sm mt-3 mb-2 px-4 font-bold uppercase">
            Changes  <span class="text-green-500 text-sm text-center mt-3">
                0: Active | 1: Inactive
                </span>
        </h6>

        <div class="w-full px-4">
            <div class="relative mb-3">
                <x-label>Old Properties</x-label>
                <x-input disabled value="{{ $formattedOld }}"/>
            </div>
        </div>

        <div class="w-full px-4">
            <div class="relative mb-3">
                <x-label>New Properties</x-label>
                <x-input disabled value="{{ $formattedNew }}"/>
            </div>
        </div>
    @elseif ($activity->event === 'created')
        <h6 class="text-sm mt-3 mb-2 px-4 font-bold uppercase">
            Data
        </h6>
        <div class="w-full px-4">
            <div class="relative mb-3">
                <x-label>Properties</x-label>
                <x-textarea disabled>{{ $formattedNew }}</x-textarea>
            </div>
        </div>
    @endif




        </div>

    </x-slot>
</x-form>


@endsection
