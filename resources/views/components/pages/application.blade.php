<x-layouts.table> 
    
    @php $title=__('Applications'); @endphp 
    @php $title2=__('Applications 2'); @endphp 
    <x-slot:title>{{$title}} </x-slot:title>
    <x-slot:buttons> <a href="{{ route('application.add') }}" style="margin-bottom:0.5em; width:20em; padding-top:.5em;"
            class="genric-btn
    danger success-border small">{{ __('Apply New Application') }}</a> </x-slot:buttons>
    <!-- Table Header Buttons
    Ends-->

    {{-- Table --}}
    <div class="progress-table-wrap">
        <div class="progress-table text-center bg-white">
            @if($tables->isEmpty())
            <!-- Display empty icon -->
            <div class="empty-table-icon bg-white" style="margin-top:6em; margin-bottom:6em;">
                <i class="fas fa-folder-open fa-5x" style="opacity: 0.3;"></i>
                <p style="font-size: 1.5em; opacity: 0.5;">{{ __('No Permit Available') }}</p>
            </div>
            @else
            <div class="table-head text-left column-hover">

                <div class="visit text-center" style="width:10%;">
                    <a
                        href="{{ route('application', ['sort_by' => 'id', 'sort_direction' => $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        {{_('ID')}}
                        @if(request('sort_by') === 'id' && request('sort_direction', 'asc') === 'asc')
                        <i class="fas fa-sort-up"></i>
                        @elseif(request('sort_by') === 'id' && request('sort_direction') === 'desc')
                        <i class="fas fa-sort-down"></i>
                        @endif
                    </a>
                </div>

                <div class="visit" style="width:20%;">
                    <a href="{{ route('application', ['sort_by' => 'holder_id', 'sort_direction' => $sortDirection == 'asc' ?
                'desc' : 'asc']) }}">
                        {{__('Holder Name')}}
                        @if(request('sort_by') === 'holder_id' && request('sort_direction', 'asc') === 'asc')
                        <i class="fas fa-sort-up"></i>
                        @elseif(request('sort_by') === 'holder_id' && request('sort_direction') === 'desc')
                        <i class="fas fa-sort-down"></i>
                        @endif
                    </a>
                </div>
                <div class="visit text-center" style="width:20%;">
                    <a href="{{ route('application', ['sort_by' => 'applicant_category_id', 'sort_direction' =>
                    $sortDirection == 'asc' ? 'desc' : 'asc']) }}">
                        {{__('Category')}}
                        @if(request('sort_by') === 'applicant_category_id' && request('sort_direction', 'asc') ===
                        'asc')
                        <i class="fas fa-sort-up"></i>
                        @elseif(request('sort_by') === 'applicant_category_id' && request('sort_direction') === 'desc')
                        <i class="fas fa-sort-down"></i>
                        @endif
                    </a>
                </div>
                <div class="visit text-center" style="width:20%;">

                    <a href="{{ route('application', ['sort_by' => 'status', 'sort_direction' => $sortDirection == 'asc' ? 'desc' :
            'asc']) }}">
                        {{__('Status')}}
                        @if(request('sort_by') === 'status' && request('sort_direction', 'asc') === 'asc')
                        <i class="fas fa-sort-up"></i>
                        @elseif(request('sort_by') === 'status' && request('sort_direction') === 'desc')
                        <i class="fas fa-sort-down"></i>
                        @endif
                    </a>
                </div>
                <div class="visit text-center" style="width:20%;">
                    <a href="{{ route('application', ['sort_by'=> 'created_at', 'sort_direction'=> $sortDirection=='asc' ?
                'desc' : 'asc']) }}"> {{__('Date Registered')}} @if(request('sort_by')==='created_at' &&
                        request('sort_direction', 'asc' )==='asc' ) <i class="fas fa-sort-up"></i>
                        @elseif(request('sort_by')==='created_at' && request('sort_direction')==='desc') <i
                            class="fas fa-sort-down"></i>
                        @endif
                    </a>
                </div>
                <div class="visit text-center" style="width:15%;">{{__('Action')}}</div>
            </div>
            @foreach($tables as $table)
            <div class="table-row text-left" style="@if($table->status == 1 || ($table->status == 2 &&
        $table->transaction_status == 0)) background: #e19898; @endif">
                <div class="visit justify-content-center" style="width:10%;">{{ $table->id ?? '' }}</div>
                <div class="visit text-left" style="width:20%;">{{ $table->holder->name ?? '' }}</div>
                <div class="visit justify-content-center" style="width:20%;">{{ $table->applicantcat->name ?? '' }}
                </div>
                <div class="visit justify-content-center text-center" style="width:20%;">
                    @if($table->status == 0)
                    <i class="fas fa-spinner" style="color: orange; margin-right: 5px;"></i> {{__('Reviewing')}}
                    @elseif($table->status == 1)
                    <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i> {{__('Rejected')}}
                    @elseif($table->status == 2)
                    <i class="fas fa-spinner" style="color: orange; margin-right: 5px;"></i> {{__('Pending Payment')}}
                    @elseif($table->status == 3)
                    <i class="fas fa-spinner" style="color: green; margin-right: 5px;"></i> {{__('Processing Payment')}}
                    @elseif($table->status == 4)
                    <i class="fas fa-check-circle" style="color: green; margin-right: 5px;"></i> {{__('Activated')}}
                    @elseif($table->status == 5)
                    <i class="fas fa-ban" style="color: yellow; margin-right: 5px;"></i> {{__('Disabled')}}
                    @elseif($table->status == 6)
                    <i class="fas fa-times-circle" style="color: red; margin-right: 5px;"></i> {{__('Terminated')}}
                    @endif
                </div>
                <div class="visit text-center justify-content-center">{{ $table->created_at ? \Carbon\Carbon::parse($table->created_at)->format('Y-m-d') : '' }}</div>

                {{--Action Column --}}
                <div class="action justify-content-center text-center" style="width: 15%; display: flex; align-items:
                center; justify-content: center;gap:3px;"> <a href="{{ route('application.view', $table->id) }}"
                        class="button view-button">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if($table->status == 1)
                    <a href="{{ route('application.edit', $table->id) }}" class="button edit-button">
                        <i class="fas fa-edit"></i>
                    </a>
                    @elseif($table->status == 2 && $table->transaction_status == 0)
                    <a href="{{ route('proceed.to.payment') }}" class="button payment-button">
                        <i class="fas fa-credit-card"></i>
                    </a>
                    @endif
                </div>

                {{--Action Column End --}}
            </div>
            @endforeach

            @endif
        </div>
    </div>
    {{ $tables->links() }}
    {{-- Table Ends --}}
</x-layouts.table>