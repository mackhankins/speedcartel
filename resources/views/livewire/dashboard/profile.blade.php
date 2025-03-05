<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <flux:heading level="1" size="xl" class="font-orbitron mb-8">Profile</flux:heading>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
                <flux:heading level="2" size="lg" class="font-orbitron">Profile Information</flux:heading>
                <flux:subheading class="mt-1">Update your account information.</flux:subheading>

                <form wire:submit="updateProfile" class="mt-6 space-y-4">
                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <div class="mt-1">
                            <input type="text" wire:model.live="name" id="name"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <div class="mt-1">
                            <div
                                class="block w-full px-4 py-3 bg-gray-50 dark:bg-dark-gray border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm text-gray-500 dark:text-gray-400">
                                {{ Auth::user()->email }}
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Your email cannot be changed.</p>
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone
                            Number</label>
                        <div class="mt-1">
                            <input type="tel" wire:model.live="phone" id="phone" x-data="{ phone: $wire.phone }"
                                x-init="$watch('phone', value => $wire.phone = value)" x-mask="(999) 999-9999"
                                x-on:input="phone = $event.target.value"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="(555) 555-5555">
                            @error('phone') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Optional. Used for order
                                notifications and shipping updates.</p>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
                <flux:heading level="2" size="lg" class="font-orbitron">Update Password</flux:heading>
                <flux:subheading class="mt-1">Ensure your account is using a secure password.</flux:subheading>

                <form wire:submit="updatePassword" class="mt-6 space-y-4">
                    <div>
                        <label for="current_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model.live="current_password" id="current_password"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="••••••••">
                            @error('current_password') <span class="mt-2 text-sm text-red-600">{{ $message }}</span
                            >                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New
                            Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model.live="password" id="password"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="••••••••">
                            @error('password') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                        <div class="mt-1">
                            <input type="password" wire:model.live="password_confirmation" id="password_confirmation"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Two Factor Authentication -->
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
                <flux:heading level="2" size="lg" class="font-orbitron">Two Factor Authentication</flux:heading>
                <flux:subheading class="mt-1">Add additional security to your account using two factor authentication.</flux:subheading>

                <div class="mt-6">
                    @if(!$enabled2FA)
                        <button type="button" wire:click="enableTwoFactorAuthentication"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Enable Two Factor Authentication
                        </button>
                    @else
                        <button type="button" wire:click="disableTwoFactorAuthentication"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Disable Two Factor Authentication
                        </button>
                    @endif
                </div>

                @if($showingQrCode)
                    <div class="mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Two factor authentication is now enabled. Scan the following QR code using your phone's
                            authenticator application.
                        </p>
                        <div class="mt-4 w-56 h-56 mx-auto">
                            {!! Auth::user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>
                @endif

                @if($showingConfirmation)
                    <div class="mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            To finish enabling two factor authentication, scan the QR code and enter the code from your
                            authenticator app.
                        </p>
                        <div class="mt-4">
                            <input type="text" wire:model="confirmationCode" placeholder="Enter code"
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('confirmationCode') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-4">
                            <button type="button" wire:click="confirmTwoFactorAuthentication"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Confirm
                            </button>
                        </div>
                    </div>
                @endif

                @if($showingRecoveryCodes)
                    <div class="mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Store these recovery codes in a secure password manager. They can be used to recover access to
                            your account if your two factor authentication device is lost.
                        </p>
                        <div class="mt-4 bg-gray-100 dark:bg-dark-gray rounded-lg p-4">
                            @foreach(json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true) as $code)
                                <div class="text-sm font-mono text-gray-600 dark:text-gray-400">{{ $code }}</div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <button type="button" wire:click="regenerateRecoveryCodes"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Regenerate Recovery Codes
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Address Management -->
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8 lg:col-span-2">
                <div class="flex justify-between items-center">
                    <div>
                        <flux:heading level="2" size="lg" class="font-orbitron">Addresses</flux:heading>
                        <flux:subheading class="mt-1">Manage your shipping and billing addresses.</flux:subheading>
                    </div>
                    @unless($showAddressForm)
                        <button type="button" wire:click="editAddress()"
                            class="flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Add New Address
                        </button>
                    @endunless
                </div>

                <!-- Address Form -->
                @if($showAddressForm)
                                    <form wire:submit="saveAddress" class="mt-6 space-y-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address
                                                    Type</label>
                                                <div class="mt-1">
                                                    <select wire:model.live="address_type" id="address_type"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white">
                                                        <option value="shipping">Shipping Address</option>
                                                        <option value="billing">Billing Address</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="full_name" id="full_name"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('full_name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span
                                                       >         @enderror
                                                </div>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line
                                                    1</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="address_line1" id="address_line1"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('address_line1') <span class="mt-2 text-sm text-red-600">{{ $message }}</span
                                                    >                    @enderror
                                                </div>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address Line
                                                    2</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="address_line2" id="address_line2"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="city" id="city"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('city') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="state" id="state"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('state') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal
                                                    Code</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="postal_code" id="postal_code"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('postal_code') <span class="mt-2 text-sm text-red-600">{{ $message }}</span
                                                    >                                @enderror
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country</label>
                                                <div class="mt-1">
                                                    <input type="text" wire:model.live="country" id="country"
                                                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                                                    @error('country') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                                                </div>
                                            </div>

                                            <div class="md:col-span-2">
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" wire:model.live="is_default" id="is_default"
                                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                        Set as default {{ $address_type }} address
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="flex justify-end space-x-3">
                                            <button type="button" wire:click="resetAddressForm"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-gray hover:bg-gray-50 dark:hover:bg-light-gray focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Save Address
                                            </button>
                                        </div>
                                    </form>
                @endif

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Shipping Addresses -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Shipping Addresses</h3>
                        <div class="mt-4 space-y-4">
                            @forelse($shippingAddresses as $address)
                                <div class="bg-gray-50 dark:bg-dark-gray rounded-xl p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-grow">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $address->full_name }}</p>
                                                @if($address->is_default)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $address->address_line1 }}<br>
                                                @if($address->address_line2)
                                                    {{ $address->address_line2 }}<br>
                                                @endif
                                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                {{ $address->country }}
                                            </p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            @unless($address->is_default)
                                                <button type="button" wire:click="setDefaultAddress({{ $address->id }})"
                                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                    Set Default
                                                </button>
                                            @endunless
                                            <button type="button" wire:click="editAddress({{ $address->id }})"
                                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                Edit
                                            </button>
                                            <button type="button" wire:click="deleteAddress({{ $address->id }})"
                                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No shipping addresses added yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Billing Addresses -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Billing Addresses</h3>
                        <div class="mt-4 space-y-4">
                            @forelse($billingAddresses as $address)
                                <div class="bg-gray-50 dark:bg-dark-gray rounded-xl p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-grow">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $address->full_name }}</p>
                                                @if($address->is_default)
                                                    <span
                                                        class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $address->address_line1 }}<br>
                                                @if($address->address_line2)
                                                    {{ $address->address_line2 }}<br>
                                                @endif
                                                {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                                                {{ $address->country }}
                                            </p>
                                        </div>
                                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                                            @unless($address->is_default)
                                                <button type="button" wire:click="setDefaultAddress({{ $address->id }})"
                                                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                    Set Default
                                                </button>
                                            @endunless
                                            <button type="button" wire:click="editAddress({{ $address->id }})"
                                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                Edit
                                            </button>
                                            <button type="button" wire:click="deleteAddress({{ $address->id }})"
                                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">No billing addresses added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
