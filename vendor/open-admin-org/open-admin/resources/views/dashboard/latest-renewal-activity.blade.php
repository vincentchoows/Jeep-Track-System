<div class="card box-default">
    <div class="card-header with-border">
        <h3 class="card-title">{{ $widgetTitle}}</h3>

        <div class="card-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-bs-toggle="collapse" href="#renewal-activity" role="button" aria-expanded="true" aria-controls="renewal-activity">
                <i class="icon-minus"></i>
            </button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="card-body collapse show" id="renewal-activity">
        <div class="table-responsive">
            <table class="table table-striped">

            @foreach ($envs->take($rowQuantity) as $env)
                <tr>
                    <td width="120px">{{ $env->created_at }}</td>
                    <td><b>{{ $env->adminUser->username }}:</b>{{ $env->description }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>