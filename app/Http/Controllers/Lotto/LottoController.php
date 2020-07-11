<?php

namespace App\Http\Controllers\Lotto;

use App\Http\Controllers\AppController;
use App\Models\Trnf\Banks;
use App\Models\Trnf\Sms;
use Illuminate\Http\Request;

/**
 *
 * Class ContactsApiController
 *
 * @package Modules\Api\Http\Controllers
 */
class LottoController extends AppController
{

    private $date;
    private $results = [];
    
    public function getCurl($url){

        $ch = curl_init();
        $timeout= 300;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_AUTOREFERER, true );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36');
        $data = curl_exec($ch);

        return $data;
    }

    public function getResults ($type, Request $request){

        $this->date = date('Y-m-d');

        return $this->{$type}($request);

    }

    public function marketthai($request){

        $url = 'https://marketdata.set.or.th/mkt/marketsummary.do?language=th&country=TH';
        $date = date('d/m', strtotime($this->date))."/".(date('Y')+543);
        $web = $this->getCurl($url);
        $html = str_get_html($web);
        $box = $html->find('table.table-info', 0);
        $title = $box->find('caption', 0)->plaintext;
        $title = preg_replace('/(\v|\s)+/', '', $title);
        if (!strpos($title, $date)) {
            $this->results[0] = null;
        }else{
            $nb1 = $box->find('tr', 1)->find('td', 1)->plaintext;
            $nb2 = $box->find('tr', 2)->find('td', 1)->plaintext;
            $nb3 = $box->find('tr', 1)->find('td', 2)->plaintext;

            $arrData = [
                'url' => $url,
                'title' => $title,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
                'n3' => preg_replace('/(\v|\s)+/', '', $nb3),
            ];
            $this->results[0] = $arrData;
        }

        $ck_n1 = $this->results[0]['n1'];
        $ck_n2 = $this->results[0]['n2'];
        $ck_n3 = $this->results[0]['n3'];
        $check = 0;
        $arrResult = null;
        if($check == 0) {
            $s3top = substr($ck_n2, -1).substr($ck_n1, -2);
            $s2top = null;
            $s3btm = null;
            $s2btm = substr($ck_n3, -2);
            $arrResult = [
                's3top' => $s3top,
                's2top' => $s2top,
                's3btm' => $s3btm,
                's2btm' => $s2btm,
            ];
        }

        $return = [
            'type' => 'SET Thai',
            'date' => $this->date,
            'trust' => (!$check) ? true : false,
            'number' => $this->results,
            'result' => $arrResult
        ];

        return $return;

    }

    public function singapore($request){

        // $this->date = date('2020-03-18');

        /**
         * Web 1
         */
        $url = 'http://www.singaporepools.com.sg/DataFileArchive/Lottery/Output/fourd_result_top_draws_en.html';
        $date = date('d M Y', strtotime($this->date));
        $web1 = file_get_contents($url);
        $html = str_get_html($web1);
        $box = $html->find('table', 0);
        $title = $box->find('thead tr', 0)->find('th', 0)->plaintext;
        if (!strpos($title, $date)) {
            $this->results[0] = null;
        }else{
            $nb1 = $box->find('.tdFirstPrize', 0)->plaintext;
            $nb2 = $box->find('.tdSecondPrize', 0)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[0] = $arrData;
        }

        /**
         * Web 2
         */
        $url = 'http://mobile.4dking.com.my/v2/4d-results-singapore.php';
        $date = date('Y-m-d', strtotime($this->date));
        $web2 = $this->getCurl($url);
        $html = str_get_html($web2);
        $box = $html->find('table.DataTable', 0);
        $title = $box->find('#SG_ddate', 0)->plaintext;
        if (!preg_match('/'.$date.'/', $title)) {
            $this->results[1] = null;
        }else{
            $nb1 = $box->find('tr', 2)->find('td', 0)->plaintext;
            $nb2 = $box->find('tr', 3)->find('td', 0)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[1] = $arrData;
        }

        /**
         * Web 3
         */
        $url = 'https://www.live4d2u.com/singapore-4d-results/';
        $date = date('d-m-Y', strtotime($this->date));
        $web3 = $this->getCurl($url);
        $html = str_get_html($web3);
        $box = $html->find('#sg4d', 0);
        $title = $box->find('.resultdrawdate', 0)->plaintext;
        if (!preg_match('/'.$date.'/', $title)) {
            $this->results[2] = null;
        }else{
            $nb1 = $box->find('.resulttop', 0)->plaintext;
            $nb2 = $box->find('.resulttop', 1)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[2] = $arrData;
        }

        $ck_n1 = $this->results[0]['n1'];
        $ck_n2 = $this->results[0]['n2'];
        $check = 0;
        if($ck_n1 == $this->results[1]['n1'] && $ck_n2 == $this->results[1]['n2']){
            $check += 0;
            if($ck_n1 == $this->results[2]['n1'] && $ck_n2 == $this->results[2]['n2']){
                $check += 0;
            }else{
                $check += 1;
            }
        }else{
            $check += 1;
        }

        if(empty($this->results[0]) || empty($this->results[1]) || empty($this->results[2])){
            $check = 1;
        }

        $arrResult = null;
        if($check == 0) {
            $s3top = substr($ck_n1, -3);
            $s2top = substr($ck_n1, -2);
            $s3btm = substr($ck_n2, -3);
            $s2btm = substr($ck_n2, -2);
            $arrResult = [
                's3top' => $s3top,
                's2top' => $s2top,
                's3btm' => $s3btm,
                's2btm' => $s2btm,
            ];
        }

        $return = [
            'type' => 'Singapore',
            'date' => $this->date,
            'trust' => (!$check) ? true : false,
            'number' => $this->results,
            'result' => $arrResult
        ];

        return $return;
    }

    public function vietnam($request){

        /**
         * Web 1
        */
        $url = 'https://xskt.com.vn/xsmb';
        $date = date('d/m', strtotime($this->date));
        $web1 = file_get_contents($url);
        $html = str_get_html($web1);
        $box = $html->find('.box-ketqua', 0);
        $title = $box->find('h2', 0)->plaintext;
        if (!strpos($title, $date)) {
            $this->results[0] = null;
        }else{
            $nb1 = $box->find('table', 0)->find('tr', 1)->find('td', 1)->plaintext;
            $nb2 = $box->find('table', 0)->find('tr', 2)->find('td', 1)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[0] = $arrData;
        }

        /**
         * Web 2
        */
        $url = 'https://www.minhngoc.net/ket-qua-xo-so/mien-bac.html';
        $date = date('d/m/Y', strtotime($this->date));
        $web2 = file_get_contents($url);
        $html = str_get_html($web2);
        $box = $html->find('.bkqtinhmienbac', 0);
        $title = $box->find('tr', 0)->plaintext;
        if (!strpos($title, $date)) {
            $this->results[1] = null;
        }else{
            $nb1 = $box->find('tr', 1)->find('td', 1)->plaintext;
            $nb2 = $box->find('tr', 2)->find('td', 1)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[1] = $arrData;
        }

        /**
         * Web 3
         */
        $url = 'https://xosodaiphat.com/xsmb-xo-so-mien-bac.html';
        $date = date('d/m/Y', strtotime($this->date));
        $web3 = file_get_contents($url);
        $html = str_get_html($web3);
        $box = $html->find('.block-main-content', 0);
        $title = $html->find('.class-title-list-link', 0)->plaintext;
        if (!strpos($title, $date)) {
            $this->results[2] = null;
        }else{
            $tb = $box->find('table', 0);
            $nb1 = $tb->find('tr', 1)->find('td', 1)->plaintext;
            $nb2 = $tb->find('tr', 2)->find('td', 1)->plaintext;

            $arrData = [
                'url' => $url,
                'n1' => preg_replace('/(\v|\s)+/', '', $nb1),
                'n2' => preg_replace('/(\v|\s)+/', '', $nb2),
            ];
            $this->results[2] = $arrData;
        }

        $ck_n1 = $this->results[0]['n1'];
        $ck_n2 = $this->results[0]['n2'];
        $check = 0;
        if($ck_n1 == $this->results[1]['n1'] && $ck_n2 == $this->results[1]['n2']){
            $check += 0;
            if($ck_n1 == $this->results[2]['n1'] && $ck_n2 == $this->results[2]['n2']){
                $check += 0;
            }else{
                $check += 1;
            }
        }else{
            $check += 1;
        }

        if(empty($this->results[0]) || empty($this->results[1]) || empty($this->results[2])){
            $check = 1;
        }

        $arrResult = null;
        if($check == 0) {
            $s3top = substr($ck_n1, -3);
            $s2top = substr($ck_n1, -2);
            $s3btm = substr($ck_n2, -3);
            $s2btm = substr($ck_n2, -2);
            $arrResult = [
                's3top' => $s3top,
                's2top' => $s2top,
                's3btm' => $s3btm,
                's2btm' => $s2btm,
            ];
        }

        $return = [
            'type' => 'Vietnam',
            'date' => $this->date,
            'trust' => (!$check) ? true : false,
            'number' => $this->results,
            'result' => $arrResult
        ];

        return $return;

    }

}