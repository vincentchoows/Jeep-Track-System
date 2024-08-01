<footer class="navbar form-footer navbar-light bg-white py-3 px-4 @if (!empty($fixedFooter))shadow fixed-bottom @endif">
    <div class="row"> {{ csrf_field() }} <div class="col-md-{{$width['label']}}"> </div>

        <div class="col-md-{{$width['field']}}"
            style="display: flex; align-items: center; justify-content: space-between;">
            <div style="flex-grow: 1; display: flex; justify-content: space-between;">
                @if(in_array('reset', $buttons))
                <div>
                    <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
                </div>
                @endif

                @if(in_array('submit', $buttons))
                <div style="display: flex;">

                    <!-- Add Back Button -->
                    <!-- <div>
                        <a href="{{ admin_url(implode('/',array_slice(Request::segments(),1,1))) }}"
                            class="btn btn-warning">{{trans('admin.back') }}</a>
                    </div> -->
                </div>

                @foreach($submit_redirects as $value => $redirect)
                @if(in_array($redirect, $checkboxes))

                <!-- Checkboxes and Submit -->
                <div class="form-check form-check-inline">
                    <input type="checkbox" class="form-check-input after-submit" id="after-save-{{$redirect}}"
                        name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}>
                    <label class="form-check-label"
                        for="after-save-{{$redirect}}">{{ trans("admin.{$redirect}") }}</label>
                </div> @endif @endforeach
            </div>

            @php
                $submitButtonStr = Admin::user()->inRoles(['phc','jkr','fin']) ? __('Approve') : __('Save');
            @endphp

            <div style="display: flex;">
                <button type="submit" name="action" value="submitvalue" class="btn btn-primary">{{$submitButtonStr}}</button>
                <!-- <button type="submit" name="action" value="submitvalue" class="btn btn-primary">{{trans('admin.submit') }}</button> -->
            </div>
            @endif
        </div>

    </div>
</footer>