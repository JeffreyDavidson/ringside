<?php

namespace App\Presenters;

use App\Models\Event;

class EventPresenter extends Presenter
{
    /**
     * @var Event
     */
    protected $model;

    /**
     * Retrieve the formatted event date.
     *
     * @return ?string
     */
    public function date()
    {
        return $this->model->date?->format('F j, Y');
    }
}
