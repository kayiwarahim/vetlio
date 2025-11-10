<?php

namespace App\Livewire;

use App\Models\Announcement;
use Livewire\Component;

class AnnouncementCarousel extends Component
{
    public function render()
    {
        return view('livewire.announcement-carousel');
    }

    public array $announcements = [];

    public int $index = 0;

    public function mount(): void
    {
        $user = auth()->user();

        $this->announcements = Announcement::get()->toArray();
    }

    public function markAsRead(): void
    {
        if (empty($this->announcements)) {
            return;
        }

        $user = auth()->user();
        $current = $this->announcements[$this->index] ?? null;

        if (!$current) {
            return;
        }

        $user->readAnnouncements()->syncWithoutDetaching([
            $current['id'] => ['read_at' => now()],
        ]);

        // Ukloni pročitanog iz niza
        unset($this->announcements[$this->index]);

        // Reindeksiraj i resetiraj pokazivač
        $this->announcements = array_values($this->announcements);
        $this->index = 0;
    }


}
