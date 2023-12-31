@props(['id', 'name', 'active'])

<li 
    class="relative px-8 text-sm text-center pb-2 cursor-pointer transition-all border-b tabs-link"
    id="tab-{{ $id }}"
    data-id="tab-{{ $id }}"
    data-tabs="{{ $id }}"
    x-on:click="active = '{{ $id }}'"
    x-bind:class="active != '{{ $id }}' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
    {{ $attributes }}
>
    @if ($slot->isNotEmpty())
        {!! $slot !!}
    @else
        {{ $name }}
    @endif
</li>
