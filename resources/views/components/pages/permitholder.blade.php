<x-layouts.table>
    @php
        $title = __('Permit Holders');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>

    <x-slot:buttons>
        <a href="{{ route('permitholder.add') }}" style="margin-bottom:0.5em; width:auto; padding-top:.5em;"
            class="genric-btn danger success-border small">
            <i class="fas fa-plus" style="margin-right: 5px;"></i>
            {{ __('Add') }}
        </a>
    </x-slot:buttons>

    <!-- Table -->
    <div class="progress-table-wrap">
        <div class="progress-table text-center bg-white">
            @if($tables->isEmpty())
                <!-- Display empty icon -->
                <div class="empty-table-icon bg-white" style="margin-top:6em;margin-bottom:6em;">
                    <i class="fas fa-folder-open fa-5x" style="opacity: 0.3;"></i>
                    <p style="font-size: 1.5em; opacity: 0.5;">{{ __('No Data Available') }}</p>
                </div>
            @else
                <div class="table-head text-left column-hover">
                    <div class="visit text-center" style="width:10%;">
                        <a
                            href="{{ route('permitholder', ['sort_by' => 'id', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('ID')}}
                            @if(request('sort_by') === 'id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:30%;">
                        <a
                            href="{{ route('permitholder', ['sort_by' => 'name', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Holder Name')}}
                            @if(request('sort_by') === 'name' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'name' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:30%;">
                        <a
                            href="{{ route('permitholder', ['sort_by' => 'identification_no', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Identification No.')}}
                            @if(request('sort_by') === 'identification_no' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'identification_no' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:30%;">
                        <a
                            href="{{ route('permitholder', ['sort_by' => 'contact_no', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Contact No.')}}
                            @if(request('sort_by') === 'contact_no' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'contact_no' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>

                    <div class="visit text-center" style="width:20%;">
                        {{__('Action')}}
                    </div>
                </div>

                @foreach($tables as $table)
                    <div class="table-row">
                        <div class="visit justify-content-center" style="width:10%;">{{ $table->id }}</div>
                        <div class="visit justify-content-left" style="width:30%;">{{ $table->name }}</div>
                        <div class="visit justify-content-left" style="width:30%;">{{ $table->identification_no }}</div>
                        <div class="visit justify-content-left" style="width:30%;">{{ $table->contact_no }}</div>

                        <div class="action justify-content-center text-center" style="width:20%;">
                            <a href="{{ route('permitholder.view', $table->id) }}" class="button view-button"><i
                                    class="fas fa-eye"></i></a>
                            <a href="{{ route('permitholder.edit', $table->id) }}" class="button edit-button"><i
                                    class="fas fa-edit"></i></a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    {{ $tables->links() }}
    <!-- Table ends -->
</x-layouts.table>