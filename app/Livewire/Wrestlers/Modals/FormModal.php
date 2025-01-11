<?php

declare(strict_types=1);

namespace App\Livewire\Wrestlers\Modals;

use App\Livewire\Concerns\BaseModal;
use App\Livewire\Wrestlers\WrestlerForm;
use App\Models\Wrestler;

class FormModal extends BaseModal
{
    protected string $modelType = Wrestler::class;

    protected string $modalLanguagePath = 'wrestlers';

    protected string $modalFormPath = 'wrestlers.modals.form-modal';

    public WrestlerForm $modelForm;
}
