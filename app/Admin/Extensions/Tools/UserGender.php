<?php

namespace App\Admin\Extensions\Tools;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class UserGender extends AbstractTool
{
    protected function script()
    {
        $url = Request::fullUrlWithQuery(['gender' => '_gender_']);

        return <<<EOT
document.querySelectorAll('.user-gender').forEach(el => {
    el.addEventListener('click',function () {
        var url = "$url".replace('_gender_', this.dataset.option);
        admin.ajax.navigate( url);
    })
});
EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $options = [
            'all'   => 'All',
            'm'     => 'Male',
            'f'     => 'Female',
        ];

        return view('admin.tools.gender', compact('options'));
    }
}