<?php

declare(strict_types=1);

namespace App\View\Components\Menus;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menuItems = [
            ['icon' => 'ki-home', 'name' => 'Dashboard', 'href' => route('dashboard')],
            [
                'name' => 'Admin',
                'children' => [
                    [
                        'icon' => 'ki-people',
                        'name' => 'Roster',
                        'children' => [
                            ['name' => 'Wrestlers', 'href' => route('wrestlers.index')],
                            ['name' => 'Tag Teams', 'href' => route('tag-teams.index')],
                            ['name' => 'Managers', 'href' => route('managers.index')],
                            ['name' => 'Referees', 'href' => route('referees.index')],
                            ['name' => 'Stables', 'href' => route('stables.index')],
                        ],
                    ],
                    ['icon' => 'ki-cup', 'name' => 'Titles', 'href' => route('titles.index')],
                    ['icon' => 'ki-home-3', 'name' => 'Venues', 'href' => route('venues.index')],
                    ['icon' => 'ki-calendar', 'name' => 'Events', 'href' => route('events.index')],
                ],
            ],
        ];

        return view('components.menus.sidebar', [
            'menuItems' => $menuItems,
        ]);
    }
}
