<?php

namespace Behavior;
/**
 * 语言检测 并自动加载语言包
 */
class CheckAuthBehavior
{

    // 行为扩展的执行入口必须是run
    public function run(&$params)
    {
        // 检测语言
        $this->auth();
    }

    protected function auth()
    {
     try {
        if (S('auth_domain') !== 1) {
            $res = json_decode(true);
            if ($res == false || $res['status'] == -1) {
                exit(isset($res['info']) ? $res['info'] : '未知错误 403-1');
            }
            S('auth_domain', 1, 3600);
        }
    } catch (\Exception $e) {
        exit(isset($res['info']) ? $res['info'] : '未知错误 403-2');
    }
       }
}
