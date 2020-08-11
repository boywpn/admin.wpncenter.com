<?php

namespace App\Http\Controllers\Games\Trnf\Games\Ufa;

use App\Http\Controllers\Games\Trnf\BetsController;

class UFAController extends BetsController
{

    public $agname;
    public $username;
    public $password;
    public $custid;
    public $amount;
    public $ch;
    public $url;
    public $redirect_url;
    public $filename;
    public $agcredit;
    public $urllevel;
    public $role;
    public $rootUser;
    public $agurl = "https://ag.ufabet.com/";
    public $dir;

    public function setEnv($data){

        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->role = $data['role'];
        $this->rootUser = $data['rootUser'];

    }

    public function __construct($key = null)
    {
        $this->dir = dirname(__FILE__);
    }

    public function login(){

        $this->ch = curl_init();
        $timeout= 120;
        $cookie_file = $this->dir . '/cookies/' . $this->username . '.txt';
        curl_setopt($this->ch, CURLOPT_URL, $this->agurl.'Public/Default11.aspx');
        curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, true );
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt($this->ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
        $data = curl_exec($this->ch);

        $info = curl_getinfo($this->ch);
        $url = $info['url'];
        $temp = parse_url($url);

//        print_r($info);
//        exit;

        $this->agurl = "https://".$temp['host']."/";
        $this->redirect_url = $info['url'];

        $captcha = $this->getCaptcha();

        $html = str_get_html($data);
        $form_field = array();
        foreach ($html->find('form#form1 input') as $element) {
            $form_field[$element->name] = $element->value;
        }
        $form_field['__EVENTTARGET'] = 'btnSignIn';
        $form_field['txtUserName'] = $this->username;
        $form_field['txtPassword'] = $this->password;
        $form_field['txtCode'] = $captcha;
        $form_field['lstLang'] = 'Default11.aspx?lang=EN-GB';
        unset($form_field['btnSignIn2']);

//        print_r($form_field);
//        exit;

        $post_string = http_build_query($form_field);
        curl_setopt($this->ch, CURLOPT_URL, $this->agurl.'Public/Default11.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);
        $data = curl_exec($this->ch);

        $this->checkURLInfo();

        if($this->filename=="ChgPwd2.aspx"){
            return ['status' => false, 'code' => 103, 'msg' => 'Please Change Password!'];
        }

        if($this->filename=="Main.aspx"){
            // Check User
            curl_setopt($this->ch, CURLOPT_URL, $this->agurl.'AccInfo.aspx');
            $data = curl_exec($this->ch);
            $html = str_get_html($data);
            if($this->role=='pa'){
                $agname = $html->find('span[id=lblpUserName]', 0)->plaintext;
            }elseif($this->role=='ss'){
                $agname = $html->find('span[id=lblssUserName]', 0)->plaintext;
            }else{
                $agname = $html->find('span[id=lblaUserName]', 0)->plaintext;
            }
            $this->agname = trim($agname);
            return ['status' => true, 'code' => 0, 'msg' => ':Login Success! Agent: '.$this->agname];
        }else{
            return ['status' => false, 'code' => 101, 'msg' => 'Cannot Login!'];
        }

    }

    public function getTotalBets(){

        $form_field = [];
        $form_field['id'] = 'FT';
        $form_field['role'] = $this->role;
        $form_field['userName'] = $this->rootUser;
        $form_field['r'] = time();

        $post_string = http_build_query($form_field);
        curl_setopt($this->ch, CURLOPT_URL, $this->agurl.'Stock_G_cm.aspx');
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post_string);
        $data = curl_exec($this->ch);

        $html_file = $this->dir . '/html/' . $this->username . '.txt';
        file_put_contents($html_file, $data);

        return $this->getTotalBetsCalculate($data);
//        $html = str_get_html($data);
    }

    public function getTotalBetsCalculate($data = null){

        if(empty($data)) {
            $html_file = $this->dir . '/html/' . $this->username . '.txt';
            $data = file_get_contents($html_file);
        }

        $html = str_get_html($data);

        $table = $html->find('table', 2);
        $rows = $table->find('tr');
        $arrData = [];
        $group = 0;
        $sub_group = 0;

        foreach ($rows as $i => $row){

            if($i <= 1) continue;

            if(preg_match('/GridSubHeader2/', $row)){
                $league = $row->plaintext;
                $group = md5($league);
                $sub_group = $group;

                $arrData[$group]['league'] = $league;
            }
            else{

                $no = $row->find('td', 0)->plaintext;
                $time = $row->find('td', 1)->plaintext;
                $event = $row->find('td', 2)->plaintext;
                $f_hdp = $row->find('td', 3)->plaintext;
                $f_ou = $row->find('td', 5)->plaintext;
                $h_hdp = $row->find('td', 7)->plaintext;
                $h_ou = $row->find('td', 9)->plaintext;

                $key = md5($no.$time.$event);

                $arrData[$group]['event'][$key] = [
                    'no' => $no,
                    'time' => $time,
                    'event' => trim($event),
                    'f_hdp' => preg_replace('/[ ,]+/', '', trim($f_hdp)),
                    'f_ou' => preg_replace('/[ ,]+/', '', trim($f_ou)),
                    'h_hdp' => preg_replace('/[ ,]+/', '', trim($h_hdp)),
                    'h_ou' => preg_replace('/[ ,]+/', '', trim($h_ou)),
                ];

            }

        }

        return $arrData;

    }

    public function getCaptcha(){

        $url = $this->agurl."Public/img.aspx";
        curl_setopt($this->ch, CURLOPT_REFERER, $this->redirect_url);
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $data = curl_exec($this->ch);

        $dir = dirname(__FILE__);
        $captcha_file = $dir . '/captcha/' . $this->username . '.jpg';
        file_put_contents($captcha_file, $data);

        $image64 = base64_encode($data);

        $apikey = 'AIzaSyC8GOrRPDuLNjHZpZUemL_UewndUDlBQa8';

        $post = '{
          "requests": [
            {
              "image": {
                "content": "'.$image64.'"
              },
              "features": [
                {
                  "type": "TEXT_DETECTION"
                }
              ]
            }
          ]
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://vision.googleapis.com/v1/images:annotate?key='.$apikey);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT,  "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36");
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TCP_NODELAY, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, TRUE);
        $captcha = $response['responses'][0]['textAnnotations'][1]['description'];

        return $captcha;
    }

    public function checkURLInfo(){
        $info = curl_getinfo($this->ch);
        $this->redirect_url = $info['url'];
        $this->getFilename($info['url']);
    }

    public function getFilename($url){
        $path = parse_url($url, PHP_URL_PATH);
        $this->filename = basename($path);
    }


}
