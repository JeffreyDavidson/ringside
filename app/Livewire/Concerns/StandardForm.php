<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\Gate;

trait StandardForm
{
    public function save(): bool
    {
        $validated = $this->validate();

        try {
            if (isset($this->formModel)) {
                Gate::authorize('update', $this->formModel);
                $this->formModel->fill($validated);
            } else {
                Gate::authorize('create', $this->formModelType);
                $this->formModel = new $this->formModelType;
                $this->formModel->fill($validated);
            }

            // It Should be set now - whether created or updating
            if (isset($this->formModel)) {
                $this->runExtraPreSaveMethods();
                $this->formModel->save();

                return $this->runExtraPostSaveMethods();
            }

            return false;

        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }

        return false;
    }

    protected function runExtraPreSaveMethods(): void {}

    protected function runExtraPostSaveMethods(): bool
    {
        return true;
    }
}
