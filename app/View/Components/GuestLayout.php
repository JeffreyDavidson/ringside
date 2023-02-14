<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\View;
use Illuminate\View\Component;

class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.layouts.guest');
    }
}
