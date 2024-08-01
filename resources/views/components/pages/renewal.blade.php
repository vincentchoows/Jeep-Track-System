<x-layouts.table>
    @php
        $title = __('Renewal');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>

    <x-slot:buttons>
    </x-slot:buttons>


    <!-- Table -->
    <div class="progress-table-wrap table-striped">
        <div class="progress-table text-center bg-white">
            @if($tables->isEmpty())
                <!-- Display empty icon -->
                <div class="empty-table-icon bg-white" style="margin-top:6em;margin-bottom:6em;">
                    <i class="fas fa-folder-open fa-5x" style="opacity: 0.3;"></i>
                    <p style="font-size: 1.5em; opacity: 0.5;">{{ __('No Permit Available') }}</p>
                </div>
            @else
                <div class="table-head column-hover">
                    <div class="visit text-center" style="width:10%;">
                        <a
                            href="{{ route('renewal', ['sort_by' => 'id', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('ID')}}
                            @if(request('sort_by') === 'id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit text-left" style="width:25%;">
                        <a
                            href="{{ route('renewal', ['sort_by' => 'holder_id', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Holder Name')}}
                            @if(request('sort_by') === 'name' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'name' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>

                    <div class="visit text-center" style="width:15%;">
                        <a
                            href="{{ route('renewal', ['sort_by' => 'applicant_category_id', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Category')}}
                            @if(request('sort_by') === 'applicant_category_id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'applicant_category_id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit text-center" style="width:15%;">
                        <a
                            href="{{ route('renewal', ['sort_by' => 'end_date', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('End Date')}}
                            @if(request('sort_by') === 'end_date' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'end_date' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    
                    <div class="visit text-center" style="width:20%;">
                        <a
                            href="{{ route('renewal', ['sort_by' => 'renewal_status', 'sort_direction' => $sort_direction == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Renewal Status')}}
                            @if(request('sort_by') === 'renewal_status' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'renewal_status' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>

                    <div class="visit text-center" style="width:20%;">
                        {{__('Action')}}
                    </div>
                </div>

                @foreach($tables as $table)
                    <div class="table-row" style="@if($table->days_left < 10) background: #ffb6b694; @endif">
                        <div class="visit justify-content-center" style="width:10%;">{{ $table->id ?? '' }}</div>
                        <div class="visit justify-content-left text-left" style="width:25%;">{{ $table->holder->name ?? '' }}
                        </div>
                        <div class="visit justify-content-center" style="width:15%;">{{ $table->applicantcat->name ?? '' }}
                        </div>
                        <div class="visit justify-content-center" style="width:15%;">{{ $table->end_date ?? ''}}</div>
                        
                        <div class="visit justify-content-center" style="width:20%;">
                            @if($table->renewal_status == 0)
                                <i class="fas fa-exclamation-circle"  style="color: orange; margin-right: 5px;"></i> {{__('Pending Renewal')}}
                            @elseif($table->renewal_status == 1)
                                <i class="fas fa-spinner" style="color: blue; margin-right: 5px;"></i> {{__('Approving')}}
                            @else
                                <i class="fas fa-ban" style="color: grey; margin-right: 5px;"></i> {{__('Error')}}
                            @endif
                        </div>

                        <div class="action justify-content-center text-center"
                            style="width: 20%; display: flex; align-items: center; justify-content: center; gap:.2em;">
                            <a href="{{ route('renewal.view', $table->id) }}" class="button view-button"><i class="fas fa-eye"></i></a>
                            <a href="#" class="button edit-button"><i class="fas fa-sync-alt"></i></a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    {{ $tables->links() }}
    <!-- Table ends -->



</x-layouts.table>