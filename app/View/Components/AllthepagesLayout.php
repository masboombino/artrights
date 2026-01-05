<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AllthepagesLayout extends Component
{
    public $pageTitle;
    public $disableZoom;

    /**
     * Create a new component instance.
     */
    public function __construct($pageTitle = 'Dashboard', $disableZoom = false)
    {
        $this->pageTitle = $pageTitle;
        $this->disableZoom = $disableZoom;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.allthepages');
    }
}

