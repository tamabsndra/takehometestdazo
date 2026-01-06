@props(['show' => 'false', 'title' => 'Confirm', 'alpineTitle' => null, 'message' => '', 'onClose' => null, 'maxWidth' => 'sm:max-w-lg'])

<div 
    x-show="{{ $show }}" 
    class="relative z-50" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    x-cloak
>
    <!-- Backdrop -->
    <div 
        x-show="{{ $show }}"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500/75 transition-opacity"
    ></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!-- Modal Panel -->
            <div 
                x-show="{{ $show }}"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full {{ $maxWidth }} sm:p-6"
                @click.away="{{ $onClose ?? $show .' = false' }}"
            >
                <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                    <button 
                        @click="{{ $onClose ?? $show .' = false' }}"
                        type="button" 
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                        <h3 
                            class="text-base font-bold leading-6 text-gray-900" 
                            id="modal-title"
                            @if($alpineTitle) x-text="{{ $alpineTitle }}" @endif
                        >
                            @if(!$alpineTitle)
                                {{ $title }}
                            @endif
                        </h3>
                        <div class="mt-2 text-sm text-gray-500">
                            @if($message)
                                <p x-text="{{ $message }}"></p>
                            @else
                                {{ $slot }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                    {{ $footer }}
                </div>
            </div>
        </div>
    </div>
</div>
