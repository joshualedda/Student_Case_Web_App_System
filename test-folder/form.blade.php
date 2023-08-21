<div class="container mx-auto px-4 py-8">
    <div x-data="{ step: 1 }">
      <div x-show="step === 1" x-cloak>
        <h1 class="text-2xl font-bold mb-4">Step 1</h1>
        <form>
          <!-- Step 1 form fields -->
          <div class="mb-4">
            <label for="name" class="block font-bold mb-1">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="mb-4">
            <label for="email" class="block font-bold mb-1">Email:</label>
            <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded">
          </div>
          <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded"
          x-on:click="step = 2">Next</button>
        </form>
      </div>


      <div x-show="step === 2" x-cloak>
        <h1 class="text-2xl font-bold mb-4">Step 2</h1>
        <form>
          <!-- Step 2 form fields -->
          <div class="mb-4">
            <label for="address" class="block font-bold mb-1">Address:</label>
            <input type="text" id="address" name="address" class="w-full p-2 border border-gray-300 rounded">
          </div>
          <div class="mb-4">
            <label for="phone" class="block font-bold mb-1">Phone:</label>
            <input type="tel" id="phone" name="phone" class="w-full p-2 border border-gray-300 rounded">
          </div>
          <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" x-on:click="step = 1">Previous</button>
          <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Submit</button>
        </form>
      </div>
    </div>
  </div>











  <div x-data="{ step: 1 }">
    <div x-show="step === 1" x-cloak>
<x-slot name="slot">
    <form action="" method="POST">


        <h6 class="text-sm mt-3 mb-6 px-4 font-bold uppercase">
        Report Student
        </h6>
        <!-- Personal information form fields -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>
                        First Name
                    </x-label>
                    <x-input type="text" name="offenses" />
                </div>
            </div>

            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>
                      Last Name
                    </x-label>
                    <x-input type="text" name="offenses" />
                </div>
            </div>

            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>
                        Offense Information
                    </x-label>
                    <x-input type="text" name="offenses" />
                </div>
            </div>



            <div class="w-full px-4">
                <div class="relative mb-3">
                    <x-label>
                        Reffered By
                    </x-label>
                    <x-input type="text" name="offenses" value="{{ Auth()->user()->name }}" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <x-button type="submit" type="button"
            x-on:click="step = 2">Next</x-button>
        </div>
</div>





<div x-show="step === 2" x-cloak>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
<div class="w-full px-4">
    <div class="relative mb-3">
        <x-label>
            First Namedsadasd
        </x-label>
        <x-input type="text" name="offenses" />
    </div>
</div>

<div class="w-full px-4">
    <div class="relative mb-3">
        <x-label>
          Last Name
        </x-label>
        <x-input type="text" name="offenses" />
    </div>
</div>

<div class="w-full px-4">
    <div class="relative mb-3">
        <x-label>
            Offense Information
        </x-label>
        <x-input type="text" name="offenses" />
    </div>
</div>



<div class="w-full px-4">
    <div class="relative mb-3">
        <x-label>
            Reffered By
        </x-label>
        <x-input type="text" name="offenses" value="{{ Auth()->user()->name }}" />
    </div>
</div>
</div>



<button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" x-on:click="step = 1">Previous</button>
</div>


</div>

    </form>




















    <div x-data="{ plans: @entangle('plans') }">
        <h6 class="text-sm my-1 px-4 font-bold uppercase mt-3">
            What do you plan to do after graduating Senior High School?
            <x-error fieldName="plans" />

        </h6>
        <x-grid columns="3" gap="4" px="0" mt="0">
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Go to College" />
                <x-label class="inline-block">Go to College</x-label>
            </div>
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Work as a skilled worker" />
                <x-label class="inline-block">Work as a skilled worker</x-label>
            </div>
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Pursue TESDA certificates" />
                <x-label class="inline-block">Pursue TESDA certificates</x-label>
            </div>
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Engage in Business" />
                <x-label class="inline-block">Engage in Business</x-label>
            </div>
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Work to help parents" />
                <x-label class="inline-block">Work to help parents</x-label>
            </div>
            <div class="relative mb-3 px-4">
                <input type="radio" wire:model="plans" value="Undecided" />
                <x-label class="inline-block">Undecided</x-label>
            </div>
        </x-grid>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('refreshCard', function () {
                Livewire.emit('updateCard');
            });

            document.addEventListener('start-polling', function (event) {
                var interval = event.detail.interval;

                setInterval(function () {
                    Livewire.emit('updateCard');
                }, interval);
            });
        });
    </script>
