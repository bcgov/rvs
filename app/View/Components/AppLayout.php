<?php

namespace App\View\Components;

use Override;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return View
     */
    #[Override]
    public function render(): View {
        return view('layouts.app');
    }
}
