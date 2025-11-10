<div
    x-data="{ index: @entangle('index') }"
    class="p-4 space-y-3">

    <template x-if="$wire.announcements.length">
        <div class="space-y-2">
            <h2 class="text-lg font-semibold text-primary-700"
                x-text="$wire.announcements[index].title"></h2>

            <p class="text-sm text-gray-600"
               x-html="$wire.announcements[index].content"></p>

            <div class="flex justify-between items-center mt-4">
                <button
                    x-show="$wire.announcements.length > 1"
                    @click="index = (index - 1 + $wire.announcements.length) % $wire.announcements.length"
                    class="text-sm text-gray-500 hover:text-primary-600"
                >&lt; Previous</button>

                <button
                    wire:click="markAsRead"
                    class="px-4 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition"
                >Mark as read</button>

                <button
                    x-show="$wire.announcements.length > 1"
                    @click="index = (index + 1) % $wire.announcements.length"
                    class="text-sm text-gray-500 hover:text-primary-600"
                >Next &gt;</button>
            </div>

            <p class="text-xs text-gray-400 text-center mt-2"
               x-text="`Announcement ${index + 1} from ${$wire.announcements.length}`"></p>
        </div>
    </template>

    <template x-if="!$wire.announcements.length">
        <p class="text-sm text-gray-500 text-center py-4">
            🎉 You are all caught up!
        </p>
    </template>
</div>
