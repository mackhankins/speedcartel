<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-orbitron mb-8">Profile</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8">
                <h2 class="text-2xl font-orbitron">Profile Information</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Update your account information.</p>

                <form wire:submit="updateProfile" class="mt-6 space-y-4">
                    <x-input wire:model.live="name" label="Name" placeholder="Enter your name" />

                    <x-input label="Email" value="{{ Auth::user()->email }}" disabled
                        hint="Your email cannot be changed." />

                    <x-input wire:model.live="phone" label="Phone Number" placeholder="(555) 555-5555"
                        x-data="{ phone: $wire.phone }" x-init="$watch('phone', value => $wire.phone = value)"
                        x-mask="(999) 999-9999" x-on:input="phone = $event.target.value"
                        hint="Optional. Used for order notifications and shipping updates." />

                    <x-button type="submit" primary class="w-full" label="Save Changes" />
                </form>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8">
                <h2 class="text-2xl font-orbitron">Update Password</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Ensure your account is using a secure password.</p>

                <form wire:submit="updatePassword" class="mt-6 space-y-4">
                    <x-password wire:model.live="current_password" label="Current Password" placeholder="••••••••" />

                    <x-password wire:model.live="password" label="New Password" placeholder="••••••••" />

                    <x-password wire:model.live="password_confirmation" label="Confirm Password"
                        placeholder="••••••••" />

                    <x-button type="submit" primary class="w-full" label="Update Password" />
                </form>
            </div>

            <!-- Two Factor Authentication -->
            <div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8">
                <h2 class="text-2xl font-orbitron">Two Factor Authentication</h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400">Add additional security to your account using two
                    factor authentication.</p>

                <div class="mt-6">
                    @if(!$enabled2FA)
                        <x-button wire:click="enableTwoFactorAuthentication" primary class="w-full"
                            label="Enable Two Factor Authentication" />
                    @else
                        <x-button wire:click="disableTwoFactorAuthentication" negative class="w-full"
                            label="Disable Two Factor Authentication" />
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
                            <x-input wire:model="confirmationCode" placeholder="Enter code" />
                        </div>
                        <div class="mt-4">
                            <x-button wire:click="confirmTwoFactorAuthentication" primary class="w-full" label="Confirm" />
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
                            <x-button wire:click="regenerateRecoveryCodes" primary class="w-full"
                                label="Regenerate Recovery Codes" />
                        </div>
                    </div>
                @endif
            </div>

            <!-- Address Management -->
            <div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8 lg:col-span-2">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-orbitron">Addresses</h2>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">Manage your shipping and billing addresses.</p>
                    </div>
                    @unless($showAddressForm)
                        <x-button wire:click="editAddress" primary label="Add New Address" />
                    @endunless
                </div>

                <!-- Address Form -->
                @if($showAddressForm)
                    <form wire:submit="saveAddress" class="mt-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-select wire:model.live="address_type" label="Address Type"
                                    placeholder="Select address type">
                                    <x-select.option label="Shipping Address" value="shipping" />
                                    <x-select.option label="Billing Address" value="billing" />
                                </x-select>
                            </div>

                            <x-input wire:model.live="full_name" label="Full Name" />

                            <div class="md:col-span-2">
                                <x-input wire:model.live="address_line1" label="Address Line 1" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input wire:model.live="address_line2" label="Address Line 2" />
                            </div>

                            <x-input wire:model.live="city" label="City" />

                            <x-input wire:model.live="state" label="State" />

                            <x-input wire:model.live="postal_code" label="Postal Code" />

                            <x-input wire:model.live="country" label="Country" />

                            <div class="md:col-span-2">
                                <x-checkbox wire:model.live="is_default"
                                    label="Set as default {{ $address_type }} address" />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <x-button wire:click="resetAddressForm" secondary label="Cancel" />
                            <x-button type="submit" primary label="Save Address" />
                        </div>
                    </form>
                @endif

                <!-- Add this before the address cards -->
                <x-dialog id="confirm-address-deletion" title="Delete Address">
                    <p class="text-gray-600 dark:text-gray-400">
                        Are you sure you want to delete this address? This action cannot be undone.
                    </p>

                    <x-slot name="footer">
                        <div class="flex justify-end gap-x-4">
                            <x-button flat label="Cancel" x-on:click="close" />
                            <x-button negative label="Delete" wire:click="confirmDelete" />
                        </div>
                    </x-slot>
                </x-dialog>

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
                                                    {{ $address->full_name }}
                                                </p>
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
                                        <div class="ml-4 flex-shrink-0 flex space-x-1">
                                            @unless($address->is_default)
                                                <x-button wire:click="setDefaultAddress({{ $address->id }})" icon="star" primary
                                                    flat size="sm" x-tooltip="Set as Default" />
                                            @endunless
                                            <x-button wire:click="editAddress({{ $address->id }})" icon="pencil" primary
                                                flat size="sm" x-tooltip="Edit Address" />
                                            <x-button wire:click="confirmDelete({{ $address->id }})" icon="trash" negative
                                                flat size="sm" x-tooltip="Delete Address" />
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
                                                    {{ $address->full_name }}
                                                </p>
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
                                        <div class="ml-4 flex-shrink-0 flex space-x-1">
                                            @unless($address->is_default)
                                                <x-button wire:click="setDefaultAddress({{ $address->id }})" icon="star" primary
                                                    flat size="sm" x-tooltip="Set as Default" />
                                            @endunless
                                            <x-button wire:click="editAddress({{ $address->id }})" icon="pencil" primary
                                                flat size="sm" x-tooltip="Edit Address" />
                                            <x-button wire:click="confirmDelete({{ $address->id }})" icon="trash" negative
                                                flat size="sm" x-tooltip="Delete Address" />
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
