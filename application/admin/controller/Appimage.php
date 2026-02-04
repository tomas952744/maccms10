<?php
namespace app\admin\controller;
use think\Db;

class Appimage extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : $param['page'];
        $param['limit'] = intval($param['limit']) <1 ? $this->_pagesize : $param['limit'];
        $where=[];

        if(!empty($param['wd'])){
            $param['wd'] = htmlspecialchars(urldecode($param['wd']));
            $where['image_title'] = ['like','%'.$param['wd'].'%'];
        }

        if(isset($param['image_type']) && $param['image_type'] !== ''){
            $where['image_type'] = ['eq',$param['image_type']];
        }

        if(isset($param['image_status']) && $param['image_status'] !== ''){
            $where['image_status'] = ['eq',$param['image_status']];
        }

        $order='image_sort asc,image_id desc';
        $res = model('AppImage')->listData($where,$order,$param['page'],$param['limit']);

        $this->assign('list',$res['list']);
        $this->assign('total',$res['total']);
        $this->assign('page',$res['page']);
        $this->assign('limit',$res['limit']);

        $param['page'] = '{page}';
        $param['limit'] = '{limit}';
        $this->assign('param',$param);
        $this->assign('title','图片管理');
        return $this->fetch('admin@appimage/index');
    }

    public function info()
    {
        if (Request()->isPost()) {
            $param = input();
            
            // 处理图片上传
            if(empty($param['image_pic'])){
                return $this->error('请上传图片');
            }
            
            $res = model('AppImage')->saveData($param);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }

        $id = input('id');
        $info = [];
        if($id){
            $where=[];
            $where['image_id'] = ['eq',$id];
            $res = model('AppImage')->infoData($where);
            $info = $res['info'];
        }

        $this->assign('info',$info);
        $this->assign('title','图片管理');
        return $this->fetch('admin@appimage/info');
    }

    public function del()
    {
        $param = input();
        $ids = $param['ids'];

        if(!empty($ids)){
            $where=[];
            $where['image_id'] = ['in',$ids];
            $res = model('AppImage')->delData($where);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }
        return $this->error(lang('param_err'));
    }

    public function batch()
    {
        $param = input();
        $ids = $param['ids'];
        $col = $param['col'];
        $val = $param['val'];

        if(!empty($ids) && !empty($col)){
            $where = [];
            $where['image_id'] = ['in',$ids];
            $data = [];
            $data[$col] = $val;
            $data['image_time'] = time();
            
            $res = Db::name('AppImage')->where($where)->update($data);
            if($res===false){
                return $this->error(lang('save_err'));
            }
            return $this->success(lang('save_ok'));
        }
        return $this->error(lang('param_err'));
    }

}
