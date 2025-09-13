<div class="tabs tabs-border mt-7">
    {{--  /////////////////////////////////////////Site Details view//////////////////////////////////// --}}
    <input type="radio" name="my_tabs_2" class="tab" aria-label="View POs" checked="checked" />


    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">
        <livewire:modifications.components.view-p-os />
    </div>

    <input type="radio" name="my_tabs_2" class="tab" aria-label="Create POs" />

    <div class="tab-content border-2 border-gray-200 bg-base-100 p-10">
        <livewire:modifications.components.create-p-o />
    </div>
</div>
