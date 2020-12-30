<?php

namespace app\common\library\sms;
class SmsBao
{
    private $status_text = array(
                            "0" => "短信发送成功",
                            "-1" => "参数不全",
                            "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
                            "30" => "密码错误",
                            "40" => "账号不存在",
                            "41" => "余额不足",
                            "42" => "帐户已过期",
                            "43" => "IP地址限制",
                            "50" => "内容含有敏感词"
                        );
    private $url = 'http://api.smsbao.com/';
    private $user = '15959395290';
    private $pass = '942099';


    public function sendSms($mobile,$code,$points)
    {
        $content = '【酷动健身】您的当前手机号帐户可用积分还有'.$points.'，绑定授权码为'.$code.',请登录小程序绑定使用。';
        $sendurl = $this->url."sms?u=".$this->user."&p=".md5($this->pass)."&m=".$mobile."&c=".urlencode($content);
        $result =file_get_contents($sendurl);
        return ['code'=>$result,'msg'=>$this->status_text[$result]];
    }
}

