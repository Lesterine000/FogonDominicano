<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Obtiene la vista / contenido que representa el componente.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
