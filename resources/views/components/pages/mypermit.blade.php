<x-layouts.table>
    @php
        $title = __('My Permit');
    @endphp
    <x-slot:title>
        {{$title}}
    </x-slot:title>
    <x-slot:buttons>

    </x-slot:buttons>

    <!-- Table Header Buttons Ends-->
    <style>
        .visit {
            width: 20%;
        }

        .red-row {
            background: #ffb6b694;
            /* Change this to the desired color */
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            font-size: 14px;
            transition: background-color 0.3s ease;
            color: white;
        }

        .view-button {
            background-color: #1e7e34;
        }

        .view-button:hover {
            background-color: #155724;
        }

        .edit-button {
            background-color: #e67e00;
        }

        .edit-button:hover {
            background-color: #c45d00;
        }
    </style>
    <!-- Table -->
    <div class="progress-table-wrap">
        <div class="progress-table text-center bg-white">
            @if($tables->isEmpty())
                <!-- Display empty icon -->
                <div class="empty-table-icon bg-white" style="margin-top:6em;margin-bottom:6em;">
                    <i class="fas fa-folder-open fa-5x" style="opacity: 0.3;"></i>
                    <p style="font-size: 1.5em; opacity: 0.5;">{{ __('No Permit Available') }}</p>
                </div>
            @else
                <div class="table-head column-hover">
                    <div class="visit" style="width:10%;">
                        <a
                            href="{{ route('mypermit', ['sort_by' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            {{_('ID')}}
                            @if(request('sort_by') === 'id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit text-left" style="width:20%;">
                        <a
                            href="{{ route('mypermit', ['sort_by' => 'holder_id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Holder Name')}}
                            @if(request('sort_by') === 'holder_id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'holder_id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:20%;">
                        <a
                            href="{{ route('mypermit', ['sort_by' => 'status', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Status')}}
                            @if(request('sort_by') === 'status' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'status' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:20%;">
                        <a
                            href="{{ route('mypermit', ['sort_by' => 'applicant_category_id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Category')}}
                            @if(request('sort_by') === 'applicant_category_id' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'applicant_category_id' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    <div class="visit" style="width:20%;">
                        <a
                            href="{{ route('mypermit', ['sort_by' => 'created_at', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                            {{__('Expiry Date')}}
                            @if(request('sort_by') === 'created_at' && request('sort_direction', 'asc') === 'asc')
                                <i class="fas fa-sort-up"></i>
                            @elseif(request('sort_by') === 'created_at' && request('sort_direction') === 'desc')
                                <i class="fas fa-sort-down"></i>
                            @endif
                        </a>
                    </div>
                    
                    <div class="visit" style="width:15%;">{{__('Action')}}</div>
                </div>

                @foreach($tables as $table)
                    <div class="table-row text-left"
                        style="@if($table->status == 5 || $table->status == 6) background: #CCCCCC; @endif">
                        <div class="visit justify-content-center" style="width:10%;">{{ $table->id ?? '' }}</div>
                        <div class="visit justify-content-center text-left" style="width:20%;">{{ $table->holder->name ?? '' }}</div>
                        <div class="visit justify-content-center text-center" style="width:20%;">
                            @if($table->status == 4)
                            <i class="fas fa-check-circle" style="color: green; margin-right: 5px;"></i> {{__('Activated')}}
                            @elseif($table->status == 5)
                            <i class="fas fa-ban" style="color: yellow; margin-right: 5px;"></i> {{__('Disabled')}}
                            @elseif($table->status == 6)
                            <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i> {{__('Terminated')}}
                            @else
                            <i class="fas fa-question-circle" style="color: black; margin-right: 5px;"></i> Unknown(?)
                            @endif
                        </div>
                        <div class="visit justify-content-center">{{ $table->applicantcat->name ?? '' }}</div>
                        
                        
                        <div class="visit justify-content-center">{{ \Carbon\Carbon::parse($table->end_date)->format('Y-m-d') ?? '' }}</div>



                        <div class="action justify-content-center text-center"
                            style="width: 15%; display: flex; align-items: center; justify-content: center; gap: 3px; overflow-x: auto; white-space: nowrap;">
                            <a href="{{ route('mypermit.view', $table->id) }}" class="view-button button ">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>

                    </div>
                @endforeach

            @endif


        </div>
    </div>
    {{ $tables->links() }}
    <!-- Table ends -->
</x-layouts.table>