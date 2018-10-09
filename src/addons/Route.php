<?php
// +----------------------------------------------------------------------
// | thinkphp5 Addons [FORM 5ini99/think-addons]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.yfketang.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yifeng <576617109@qq.com>
// +----------------------------------------------------------------------
namespace think\addons;

use think\Hook;
use think\Request;

/**
 * 插件执行默认控制器
 * Class AddonsController
 * @package think\addons
 */
class Route extends Controller
{
    /**
     * 插件执行
     */
    public function execute()
    {
        if (!empty($this->addon) && !empty($this->controller) && !empty($this->action)) {
            // 获取类的命名空间
            $class = get_addon_class($this->addon, 'controller', $this->controller);
            if (class_exists($class)) {
                $model = new $class();
                if ($model === false) {
                    abort(500, lang('addon init fail'));
                }
                // 调用操作
                if (!method_exists($model, $this->action)) {
                    abort(500, lang('Controller Class Method Not Exists'));
                }
                // 监听addons_init
                Hook::listen('addons_init', $this);
                return call_user_func_array([$model, $this->action], [Request::instance()]);
            } else {
                abort(500, lang('Controller Class Not Exists'));
            }
        }
        abort(500, lang('addon cannot name or action'));
    }
}
