<?php

declare(strict_types=1);
namespace App\Livewire\Wrestlers;

use Livewire\Attributes\Validate;
use Livewire\Form;

class WrestlerForm extends Form
{
    #[Validate('required|min:5')]
    public $name = '';

    public int $height_feet;

    public int $height_inches;

    #[Validate('required|min:1')]
    public $weight = '';

    #[Validate('required|min:1')]
    public $hometown = '';

    #[Validate('required|min:1')]
    public $signature_move = '';

    #[Validate('required|min:1')]
    public $status = '';

    public function getHeight()
    {
        return ($this->height_feet*12)+$this->height_inches;
    }
}
