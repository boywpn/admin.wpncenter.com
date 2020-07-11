
    <li class="dd-item dd3-item" data-id="{{$menu->id}}">
        <div class="dd-handle dd3-handle"></div>

        <div class="dd3-content">
            <span class="menu-label" id="menu-label-{{$menu->id}}">{{ $menu->label  }}</span>
            <a href="#" data-id="{{$menu->id}}" class="edit-menu float-right btn btn-xs btn-warning m-l-10 m-t-5">@lang('menumanager::menu_manager.edit')</a>
            <a href="#" data-id="{{$menu->id}}" class="delete-menu float-right btn btn-xs btn-danger m-t-5">@lang('menumanager::menu_manager.delete')</a>
        </div>
        @if($menu->children->count() > 0)
            <ol class="dd-list">
            @foreach($menu->children as $menu )
                @include('menumanager::partial.menuItem', array('menu' => $menu))
            @endforeach
            </ol>
        @endif
    </li>

