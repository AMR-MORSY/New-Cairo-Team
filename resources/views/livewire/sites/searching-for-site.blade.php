<div>
    <form wire:submit="show" method="POST">


        <x-ui.input.group>
            <x-ui.input placeholder="Site code..." wire:model='search' />



            <x-ui.button type="submit">Search</x-ui.button>
        </x-ui.input.group>



    </form>
</div>
