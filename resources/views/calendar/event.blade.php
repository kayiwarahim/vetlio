<div class="flex flex-col items-start p-2 space-y-1 w-full overflow-hidden" >
    <!-- Time -->
    <div class="flex items-center space-x-1 w-full truncate">
        <x-filament::icon :icon="\App\Enums\Icons\PhosphorIcons::Clock" class="text-white mix-blend-difference"/>
        <span class="text-xs text-white mix-blend-difference truncate w-full">
            <span x-text="event.extendedProps.start"></span> -
            <span x-text="event.extendedProps.end"></span>
        </span>
    </div>

    <!-- Client -->
    <div class="flex items-center space-x-1 w-full truncate">
        <x-filament::icon :icon="\App\Enums\Icons\PhosphorIcons::User" class="text-white mix-blend-difference"/>
        <span class="text-2xs text-white mix-blend-difference truncate w-full" x-text="event.extendedProps.client"></span>
    </div>

    <!-- Patient -->
    <div class="flex items-center space-x-1 w-full truncate">
        <x-filament::icon :icon="\App\Enums\Icons\PhosphorIcons::Dog" class="text-white mix-blend-difference"/>
        <span class="text-2xs text-white mix-blend-difference truncate w-full" x-text="event.extendedProps.patient"></span>
    </div>

    <!-- Service -->
    <div class="flex items-center space-x-1 w-full truncate">
        <x-filament::icon :icon="\App\Enums\Icons\PhosphorIcons::Hand" class="text-white mix-blend-difference"/>
        <span class="text-2xs text-white mix-blend-difference truncate w-full" x-text="event.extendedProps.service"></span>
    </div>
</div>
