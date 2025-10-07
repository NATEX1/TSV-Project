<?php

namespace App\View\Components;

use App\Services\MongoService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Footer extends Component
{

    public $contact;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $mongo = app(MongoService::class);
        $this->contact = $mongo->findOne('contact');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.footer', [
            'contact' => $this->contact,
        ]);
    }
}
