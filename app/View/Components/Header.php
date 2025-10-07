<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\MongoService;

class Header extends Component
{
    public $contact;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        // โหลด Contact จาก MongoDB โดยตรง
        $mongo = app(MongoService::class);
        $this->contact = $mongo->findOne('contact');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // ส่งตัวแปร $contact ไปยัง Blade
        return view('components.header', [
            'contact' => $this->contact,
        ]);
    }
}
