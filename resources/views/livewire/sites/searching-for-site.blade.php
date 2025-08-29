<div>
    <form wire:submit="show" method="POST">


        <flux:input.group>
            <flux:input placeholder="Site code..." wire:model='search' />



            <flux:button type="submit">Search</flux:button>
        </flux:input.group>



    </form>
</div>
