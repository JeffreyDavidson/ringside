<?php

namespace App\Models\Contracts;

interface Manageable
{
    /**
     * Undocumented function
     */
    public function managers();

    /**
     * Undocumented function
     */
    public function currentManagers();

    /**
     * Undocumented function
     */
    public function previousManagers();
}
