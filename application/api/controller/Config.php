<?php
namespace app\api\controller;

use think\Request;
use think\Db;

class Config extends Base
{
    use PublicApi;
    public function __construct()
    {
        parent::__construct();
        $this->check_config();
    }

    public function get_config(Request $request)
    {
        $config = config('maccms');

        $banners = isset($config['site']['site_banner']) ? $config['site']['site_banner'] : '';
        $banner_list = [];
        if (!empty($banners)) {
            $banner_list = explode("\n", $banners);
            foreach ($banner_list as $k => &$v) {
                $v = mac_url_img($v);
            }
        }

        $res = [
            'code' => 1,
            'msg' => '获取成功',
            'data' => [
                'site_banner' => $banner_list,
                'site_app_launch_image' => isset($config['site']['site_app_launch_image']) ? mac_url_img($config['site']['site_app_launch_image']) : '',
            ]
        ];
        return json($res)->options(['json_encode_param' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE]);
    }
}
