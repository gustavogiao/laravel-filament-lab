<?php

namespace App\Modules\User\Livewire\Settings;

use App\Modules\Auth\Livewire\Actions\Logout;
use App\Modules\User\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteUserForm extends Component
{
    use PasswordValidationRules;

    public string $password = '';

    public function render()
    {
        return view('livewire.settings.delete-user-form');
    }

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}
