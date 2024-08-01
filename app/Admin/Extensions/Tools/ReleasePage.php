<?php

namespace App\Admin\Extensions\Tools;

// use OpenAdmin\Admin\Grid\Tools\BatchAction;
use OpenAdmin\Admin\Actions\BatchAction;

class ReleasePage extends BatchAction
{
    protected $action;

    public function __construct($action = 1)
    {
        $this->action = $action;
    }

    public function script()
    {
        return <<<EOT

document.querySelector('{$this->getElementClass()}').addEventListener('click', function(e) {
    let url = '{$this->resource}/release';
    let data = {
        ids: admin.grid.selected,
        action: {$this->action}
    };

    admin.ajax.post(url,data,function(data){
        if(data.status == 200){
            admin.ajax.reload();
            if (data.data == 1){
                admin.toastr.success('Released');
            }else{
                admin.toastr.success('Un Released');
            }
        }
    })
    e.preventDefault();
});

EOT;

    }
}