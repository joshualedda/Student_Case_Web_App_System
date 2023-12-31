<div {{ $attributes->merge(['class' => 'mx-auto ']) }}>
    <div class="flex justify-between items-center ">
        <h6 class="text-xl font-bold text-left ">
            {{ $title }}
        </h6>
        <div class="ml-aut o">
            {{ $actions }}
        </div>
    </div>
    <div class="w-full mx-auto mt-6">
        <div class="relative flex flex-col min-w-0 py-4 break-words w-full mb-4 shadow-md rounded-lg border-0 ">

            <div class="flex-auto lg:px-10 py-5 pt-0 ">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
