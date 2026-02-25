<?php

namespace App\Livewire\Alumni;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Feed extends Component
{

    #[Layout('components.layouts.master-alumni')]

    public function render()
    {
        return view('livewire.alumni.feed');
    }
}
