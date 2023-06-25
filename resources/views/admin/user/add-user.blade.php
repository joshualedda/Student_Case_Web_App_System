@extends('layouts.dashboard.index')
@section('content')

<div class="mx-auto py-8">

  <h3 class="font-semibold mb-6 dark:text-gray-200 text-gray-600">Add New User</h3>

  <div class="bg-white dark:bg-gray-800 rounded shadow-lg p-10  px-4 md:p-8 mb-6 ">
    <div class="grid gap-4 gap-y-4 text-sm grid-cols-1 lg:grid-cols-3">
      <div class="text-gray-600 dark:text-gray-400">
        <p class="font-medium text-lg dark:text-gray-200 text-gray-600" >Personal Details</p>
        <p className="dark:text-gray-400">Please fill out all the fields.</p>
      </div>

      <div class="lg:col-span-2">
        <div class="grid gap-4 gap-y-4 text-sm grid-cols-1 md:grid-cols-5 ">
          <div class="md:col-span-5">
            <x-label for="full_name">Name</x-label>
<x-input type="text" />
          </div>

          <div class="md:col-span-5">
            <x-label for="email">Email Address</x-label>
<x-input type="text"/>
          </div>

        </div>
      </div>
    </div>

    <div class="grid gap-4 gap-y-4 text-sm grid-cols-1 lg:grid-cols-3 mt-5">
      <div class="text-gray-600 dark:text-gray-400">
        <p class="font-medium text-lg dark:text-gray-200 text-gray-600">Password</p>
        <p className="dark:text-gray-400">Update your password here.</p>
      </div>

      <div class="lg:col-span-2">

        <div class="grid gap-4 gap-y-4 text-sm grid-cols-1 md:grid-cols-5">


          <div class="md:col-span-5">
            <x-label for="password">New Password</x-label>
<x-input type="password"/>
          </div>
          <div class="md:col-span-5">
            <x-label for="password">Repeat Password</x-label>
<x-input type="password"/>
          </div>



          <div class="md:col-span-5">
            <x-label for="user_type" value="{{ __('User') }}" />
            <x-select id="user_type" name="user_type">
                <option value="user">User</option>
                <option value="adviser">Adviser</option>
                <option value="admin">Admin</option>
            </x-select>
            </div>

            <div class="md:col-span-5">
                <x-label for="user_type" value="{{ __('Status') }}" />
                <x-select id="user_type" name="user_type">
                    <option value="user">Active</option>
                    <option value="adviser">Inactive</option>
                </x-select>
                </div>



          <div class="md:col-span-5 text-right">
            <div class="inline-flex items-end">
              <x-button>Save</x-button>
            </div>
          </div>

        </div>
      </div>
    </div>




















  </div>
</div>


@endsection






