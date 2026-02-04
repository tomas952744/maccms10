<?php
namespace app\common\model;
use think\Db;

class AppImage extends Base {
    // 设置数据表（不含前缀）
    protected $name = 'app_image';
    protected $pk = 'image_id';

    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = '';

    // 自动完成
    protected $auto       = [];
    protected $insert     = [];
    protected $update     = [];

    public function listData($where,$order,$page=1,$limit=20,$start=0)
    {
        $page = $page > 0 ? (int)$page : 1;
        $limit = $limit ? (int)$limit : 20;
        $start = $start ? (int)$start : 0;
        if(!is_array($where)){
            $where = json_decode($where,true);
        }
        $limit_str = ($limit * ($page-1) + $start) .",".$limit;
        $total = $this->where($where)->count();
        $list = Db::name('AppImage')->where($where)->order($order)->limit($limit_str)->select();

        return ['code'=>1,'msg'=>lang('data_list'),'page'=>$page,'pagecount'=>ceil($total/$limit),'limit'=>$limit,'total'=>$total,'list'=>$list];
    }

    public function infoData($where,$field='*')
    {
        if(!is_array($where)){
            $where = json_decode($where,true);
        }
        $info = Db::name('AppImage')->where($where)->field($field)->find();
        if(empty($info)){
            return ['code'=>1001,'msg'=>lang('data_not_exist')];
        }
        return ['code'=>1,'msg'=>lang('data_info'),'info'=>$info];
    }

    public function saveData($data)
    {
        $validate = \think\Loader::validate('AppImage');
        if(!$validate->check($data)){
            return ['code'=>1001,'msg'=>lang('param_err').'：'.$validate->getError() ];
        }
        if(empty($data['image_id'])){
            $data['image_time_add'] = time();
            $data['image_time'] = time();
            $res = Db::name('AppImage')->insert($data);
        }
        else{
            $data['image_time'] = time();
            $res = Db::name('AppImage')->where('image_id',$data['image_id'])->update($data);
        }
        if(false === $res){
            return ['code'=>1002,'msg'=>lang('save_err')];
        }
        return ['code'=>1,'msg'=>lang('save_ok')];
    }

    public function delData($where)
    {
        if(!is_array($where)){
            $where = json_decode($where,true);
        }
        $res = Db::name('AppImage')->where($where)->delete();
        if($res===false){
            return ['code'=>1001,'msg'=>lang('del_err')];
        }
        return ['code'=>1,'msg'=>lang('del_ok')];
    }

}
