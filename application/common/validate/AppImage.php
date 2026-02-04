<?php
namespace app\common\validate;
use think\Validate;

class AppImage extends Validate
{
    protected $rule = [
        'image_type'  => 'require|in:1,2',
        'image_title' => 'require|max:255',
        'image_pic'   => 'require',
    ];

    protected $message = [
        'image_type.require' => '图片类型不能为空',
        'image_type.in'      => '图片类型错误',
        'image_title.require'=> '图片标题不能为空',
        'image_title.max'    => '图片标题最多255个字符',
        'image_pic.require'  => '图片地址不能为空',
    ];
}
