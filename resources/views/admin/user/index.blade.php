@extends('layouts.dashboard.index')

@section('content')
<div x-data="{ showTable: true, showForm: false }">
    <div x-show="showTable">
        <h2 class="m-1 text-2xl font-semibold text-gray-700  mb-3">
            Users
        </h2>
        <x-bread :breadcrumbs="[
            ['url' => url('admin/dashboard'), 'label' => 'Admin'],
            ['url' => url('admin/user/accounts'), 'label' => 'Settings'],
            ['url' => url('admin/user/accounts'), 'label' => 'User Accounts'],
        ]"/>
        <div class="flex items-center justify-between my-2">
            <h6 class="text-lg font-semibold text-gray-600 dark:text-gray-300 flex-shrink-0">
                {{-- List Of Students --}}
            </h6>
            <div class="flex justify-end  mt-4">
                <x-button x-on:click="showTable = false; showForm = true">
                    Add
                </x-button>
            </div>
        </div>
        <div>

          <livewire:admin.user.users-table />
        </div>

    </div>


    <div x-cloak x-show="showForm">

            <div class="flex justify-end">

                <x-button x-on:click="showForm = false; showTable = true">
                    Back
                </x-button>
            </div>


            <div>
            <livewire:admin.add-user/>
            </div>



    </div>

</div>

@endsection
