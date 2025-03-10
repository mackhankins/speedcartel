<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\User;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;

class Settings extends Component
{
    use WireUiActions;

    // Account Info
    public $name = '';
    public $phone = '';
    public $counter = 0;
    public $testMessage = '';

    // Password Change
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    // Address Form
    public $showAddressForm = false;
    public $address_type = 'shipping';
    public $address_id = null;
    public $full_name = '';
    public $address_line1 = '';
    public $address_line2 = '';
    public $city = '';
    public $state = '';
    public $postal_code = '';
    public $country = '';
    public $address_phone = '';
    public $is_default = false;

    // 2FA Properties
    public $enabled2FA = false;
    public $showingQrCode = false;
    public $showingConfirmation = false;
    public $showingRecoveryCodes = false;
    public $confirmationCode = '';
    public $showingPasswordConfirmation = false;
    public $action = ''; // 'enable' or 'disable'
    public $addressToDelete = null;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->enabled2FA = $user->two_factor_secret !== null && $user->two_factor_confirmed_at !== null;
    }

    public function increment()
    {
        $this->counter++;
    }

    public function decrement()
    {
        $this->counter--;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->save();

        $this->dispatch('notify', message: 'Profile updated successfully');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('notify', message: 'Password updated successfully');
    }

    public function enableTwoFactorAuthentication()
    {
        $this->action = 'enable';
        $this->showingPasswordConfirmation = true;
    }

    public function disableTwoFactorAuthentication()
    {
        $this->action = 'disable';
        $this->showingPasswordConfirmation = true;
    }

    public function closePasswordConfirmation()
    {
        $this->showingPasswordConfirmation = false;
        $this->password_confirmation = '';
    }

    public function confirmPassword($action)
    {
        $this->resetErrorBag();

        if (!Hash::check($this->password_confirmation, Auth::user()->password)) {
            $this->addError('password_confirmation', 'The provided password is incorrect.');
            return;
        }

        if ($action === 'enable') {
            app(EnableTwoFactorAuthentication::class)(Auth::user());
            $this->enabled2FA = true;
            $this->showingQrCode = true;
            $this->showingConfirmation = true;
        } else {
            app(DisableTwoFactorAuthentication::class)(Auth::user());
            $this->enabled2FA = false;
            $this->showingQrCode = false;
            $this->showingConfirmation = false;
            $this->showingRecoveryCodes = false;
        }

        $this->showingPasswordConfirmation = false;
        $this->password_confirmation = '';
    }

    public function showRecoveryCodes()
    {
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes()
    {
        app(GenerateNewRecoveryCodes::class)(Auth::user());
        $this->showingRecoveryCodes = true;
    }

    public function editAddress($addressId = null)
    {
        $this->showAddressForm = true;
        $this->address_id = $addressId;

        if ($addressId) {
            $address = Address::findOrFail($addressId);
            $this->address_type = $address->type;
            $this->full_name = $address->full_name;
            $this->address_line1 = $address->address_line1;
            $this->address_line2 = $address->address_line2;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->postal_code = $address->postal_code;
            $this->country = $address->country;
            $this->address_phone = $address->phone;
            $this->is_default = $address->is_default;
        } else {
            $this->resetAddressForm();
            $this->showAddressForm = true;
            $this->full_name = Auth::user()->name;
            $this->address_phone = Auth::user()->phone;
        }
    }

    public function resetAddressForm()
    {
        $this->reset([
            'address_id',
            'address_type',
            'full_name',
            'address_line1',
            'address_line2',
            'city',
            'state',
            'postal_code',
            'country',
            'address_phone',
            'is_default'
        ]);
        $this->showAddressForm = false;
    }

    public function saveAddress()
    {
        $this->validate([
            'address_type' => 'required|in:shipping,billing',
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'address_phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        if ($this->is_default) {
            $user->addresses()
                ->where('type', $this->address_type)
                ->update(['is_default' => false]);
        }

        if ($this->address_id) {
            $address = Address::findOrFail($this->address_id);
            $address->update([
                'type' => $this->address_type,
                'full_name' => $this->full_name,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'phone' => $this->address_phone,
                'is_default' => $this->is_default,
            ]);
        } else {
            $user->addresses()->create([
                'type' => $this->address_type,
                'full_name' => $this->full_name,
                'address_line1' => $this->address_line1,
                'address_line2' => $this->address_line2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'phone' => $this->address_phone,
                'is_default' => $this->is_default,
            ]);
        }

        $this->resetAddressForm();
        $this->dispatch('notify', message: 'Address saved successfully');
    }

    public function confirmDelete($addressId)
    {
        $this->addressToDelete = $addressId;
        $this->dialog()->confirm([
            'title' => 'Delete Address',
            'description' => 'Are you sure you want to delete this address? This action cannot be undone.',
            'accept' => [
                'label' => 'Delete',
                'method' => 'deleteAddress',
                'params' => $addressId,
                'color' => 'negative'
            ],
            'reject' => [
                'label' => 'Cancel',
                'color' => 'slate'
            ]
        ]);
    }

    public function deleteAddress($addressId)
    {
        $address = Address::findOrFail($addressId);
        $address->delete();
        $this->addressToDelete = null;
        $this->dispatch('notify', message: 'Address deleted successfully');
    }

    public function setDefaultAddress($addressId)
    {
        $address = Address::findOrFail($addressId);
        $user = Auth::user();

        $user->addresses()
            ->where('type', $address->type)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);
        $this->dispatch('notify', message: 'Default address updated successfully');
    }

    public function testLivewire()
    {
        $this->testMessage = 'Livewire is working! ' . now()->format('H:i:s');
    }

    public function confirmTwoFactorAuthentication()
    {
        $this->validate([
            'confirmationCode' => 'required|string',
        ]);

        try {
            app(ConfirmTwoFactorAuthentication::class)(Auth::user(), $this->confirmationCode);
            $this->showingQrCode = false;
            $this->showingConfirmation = false;
            $this->showingRecoveryCodes = true;
        } catch (\Exception $e) {
            $this->addError('confirmationCode', $e->getMessage());
        }
    }

    #[Title('Settings')]
    public function render()
    {
        $user = Auth::user();
        
        return view('livewire.dashboard.settings', [
            'shippingAddresses' => $user->addresses()->where('type', 'shipping')->get(),
            'billingAddresses' => $user->addresses()->where('type', 'billing')->get(),
        ])->layout('components.layouts.dashboard');
    }
} 