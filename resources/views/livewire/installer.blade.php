<div class="w-full max-w-5xl bg-white shadow-xl rounded-2xl overflow-hidden flex flex-col border border-gray-100">
    <!-- Top Stepper (Horizontal) -->
    <!-- Top Stepper (Horizontal) -->
    <div class="bg-white border-b border-gray-100 p-6">
        <div class="flex items-center justify-between max-w-lg mx-auto relative px-4">
            <!-- Connecting Line -->
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-100 -z-10 rounded"></div>
            <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-violet-600 -z-10 rounded transition-all duration-500 ease-in-out" style="width: {{ ($currentStep - 1) / 3 * 100 }}%"></div>
            
            @foreach (['Requirements', 'Permissions', 'Database', 'Admin'] as $index => $label)
                @php 
                    $step = $index + 1; 
                    $isActive = $currentStep >= $step;
                    $isCompleted = $currentStep > $step;
                @endphp
                <div class="relative bg-white p-1">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 {{ $isActive ? 'bg-violet-600 text-white shadow-lg shadow-violet-200 ring-4 ring-white' : 'bg-gray-100 text-gray-400 ring-4 ring-white' }}">
                        @if ($isCompleted)
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        @elseif($step === 1)
                            <!-- Server / Requirements Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2' : '1.5' }}" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M9 16h6"></path></svg>
                        @elseif($step === 2)
                             <!-- Permissions / Folder Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2' : '1.5' }}" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        @elseif($step === 3)
                            <!-- Database Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2' : '1.5' }}" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 2.21 0 4m0 5c0 2.21 3.582 4 8 4s8-1.79 8-4"></path></svg>
                        @elseif($step === 4)
                            <!-- User / Admin Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive ? '2' : '1.5' }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 md:p-12 relative bg-white">
        <div class="max-w-xl mx-auto">
            @if($currentStep === 1)
                <div class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Server Requirements</h2>
                    <p class="text-gray-500 mb-8 text-center">Please check that your server meets the requirements.</p>

                    <div class="space-y-3">
                        @foreach($requirements as $req)
                            <div class="flex items-center justify-between p-3 bg-white border rounded-lg {{ $req['status'] ? 'border-gray-200' : 'border-red-300 bg-red-50' }}">
                                <span class="font-medium {{ $req['status'] ? 'text-gray-700' : 'text-red-700' }}">
                                    {{ $req['label'] }}
                                </span>
                                @if($req['status'])
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @else
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($currentStep === 2)
                 <div class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Folder Permissions</h2>
                    <p class="text-gray-500 mb-8 text-center">Ensure the application can write to these directories.</p>
                     
                    <div class="space-y-3">
                        @foreach($permissions as $perm)
                            <div class="flex items-center justify-between p-3 bg-white border rounded-lg {{ $perm['isSet'] ? 'border-gray-200' : 'border-red-300 bg-red-50' }}">
                                <div class="flex flex-col">
                                    <span class="font-medium {{ $perm['isSet'] ? 'text-gray-700' : 'text-red-700' }}">
                                        {{ $perm['folder'] }}
                                    </span>
                                    <span class="text-xs text-gray-400">Required: {{ $perm['permission'] }}</span>
                                </div>
                                 @if($perm['isSet'])
                                    <div class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">Writable</div>
                                @else
                                    <div class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded">Not Writable</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($currentStep === 3)
                 <div class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Database Setup</h2>
                    <p class="text-gray-500 mb-8 text-center">Configure your database connection details.</p>

                     <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Host</label>
                             <input wire:model="db_host" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('db_host') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Port</label>
                             <input wire:model="db_port" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('db_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Database Name</label>
                             <input wire:model="db_database" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('db_database') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Username</label>
                             <input wire:model="db_username" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('db_username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="col-span-1 md:col-span-2 space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Password</label>
                             <input wire:model="db_password" type="password" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                              @error('db_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @error('connection') 
                        <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium border border-red-100 flex items-start">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>{{ $message }}</div>
                        </div>
                    @enderror
                    @error('migration') 
                        <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium border border-red-100 flex items-start">
                             <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>{{ $message }}</div>
                        </div>
                    @enderror

                    @if($isMigrating)
                        <div class="mt-6 flex flex-col items-center justify-center p-6 border-2 border-dashed border-violet-200 rounded-lg bg-violet-50">
                            <svg class="animate-spin h-8 w-8 text-violet-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-violet-700 font-medium">Running database migrations...</span>
                            <span class="text-violet-500 text-xs mt-1">This may take a moment.</span>
                        </div>
                    @endif
                </div>
            @elseif($currentStep === 4)
                 <div wire:key="step-4" class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 text-center">Admin Account</h2>
                    <p class="text-gray-500 mb-8 text-center">Create your administrator account.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                         <div class="col-span-1 md:col-span-2 space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Full Name</label>
                             <input wire:model="admin_name" type="text" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('admin_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="col-span-1 md:col-span-2 space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Email Address</label>
                             <input wire:model="admin_email" type="email" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('admin_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Password</label>
                             <input wire:model="admin_password" type="password" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                             @error('admin_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                         </div>
                         <div class="space-y-1">
                             <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                             <input wire:model="admin_password_confirmation" type="password" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-transparent outline-none transition-all placeholder-gray-400 text-sm">
                         </div>
                    </div>
                    </div>
                    @error('admin') 
                        <div class="mt-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium border border-red-100 flex items-start">
                             <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>{{ $message }}</div>
                        </div>
                    @enderror
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mt-12 pt-6 border-t border-gray-100">
                @if($currentStep > 1)
                    <button wire:click="previousStep" type="button" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Back
                    </button>
                @else
                    <div></div>
                @endif

                @if($currentStep < 4)
                    <button wire:click="nextStep" wire:loading.attr="disabled" type="button" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove target="nextStep">Continue</span>
                        <span wire:loading target="nextStep">Processing...</span>
                    </button>
                @else
                    <button wire:click="finish" wire:loading.attr="disabled" type="button" class="px-6 py-2.5 rounded-lg text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove target="finish">Install</span>
                        <span wire:loading target="finish">Finalizing...</span>
                    </button>
                @endif
            </div>

             @if($errors->any() && $currentStep < 3)
             <div class="mt-4 text-center">
                 <span class="text-red-500 text-sm">Please fix the issues above to continue.</span>
             </div>
            @endif
        </div>
    </div>
</div>
