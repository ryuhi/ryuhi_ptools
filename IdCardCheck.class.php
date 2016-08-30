<?php
namespace Org\CrmLib;

/**
 * 中国身份证号码验证 含大陆、香港、澳门
 * @author 张晖<tobutatu@gmail.com>
 */
class IdCardCheck {

    public function check($idcard) {
        if (empty($idcard)) {
            return array('code'=>0, 'msg'=>"身份证号不能为空");
        }
        $hkpattern = "/^[A-Z]{1,2}\d{6}\([A0-9]\)$/";
        $mopattern = "/^[157]\d{6}\([\d]\)$/";
        if (strlen($idcard) == 18) {
            return $this->prcCheck($idcard);
        } elseif (preg_match($hkpattern, $idcard) === 1) {
            return $this->hkCheck($idcard);
        } elseif(preg_match($mopattern,$idcard) ===1) {
            return $this->macroCheck($idcard);
        } else {
            return array('code'=>0, 'msg'=>"身份证号不正确");
        }
    }
    
    /**
     *  大陆身份证号码验证
     * @param type $idcard 身份证号码
     * @return Array
     * @author 张晖<tobutatu@gmail.com>
     */
    private  function prcCheck($idcard) {
        $arr = str_split($idcard);
        $sum = 7 * $arr[0] + 9 * $arr[1] + 10 * $arr[2] + 5 * $arr[3] + 8 * $arr[4] + 4 * $arr[5] + 2 * $arr[6] + $arr[7] + 6 * $arr[8] + 3 * $arr[9] + 7 * $arr[10] + 9 * $arr[11] + 10 * $arr[12] + 5 * $arr[13] + 8 * $arr[14] + 4 * $arr[15] + 2 * $arr[16];
        $res = $sum % 11;
        switch ($res) {
            case 0:
                $rs = 1;
                break;

            case 1:
                $rs = 0;
                break;

            default:
                $rs = 12 - $res;
                if ($rs == 10) {
                    $rs = 'X';
                }
                break;
        }
        if (strtolower($rs) == strtolower($arr[17])) {
            return array('code'=>1, 'msg'=>"身份证号正确");
        } else {
            return array('code'=>0, 'msg'=>"身份证号不正确");
        }
    }
    
    /**
     * 香港身份证号码验证
     * @param String $idcard 香港身份证号码验证
     * @return Array
     * @author 张晖<tobutatu@gmail.com>
     */
    private function hkCheck($idcard) {
        $range = range('A', 'Z');
        $str = strrev(str_replace(array('(',')'),'',$idcard));
        $arr = str_split($str);
        $count = count($arr);
        if ($arr[0] == 'A') {
            $lastInt = 10;
        } else {
            $lastInt = $arr[0];
        }
        $sum = $lastInt * 1 + $arr[1] * 2 + $arr[2] * 3 + $arr[3] * 4 + $arr[4] * 5 + $arr[5] * 6 + $arr[6] * 7;
        $res = $count -7;
        switch ($res) {
            case 1:
                $index = array_search($arr[$count - 1], $range) + 1;
                $sum = $sum + $index * 8;
                break;

            case 2:
                $index1 = array_search($arr[$count - 1], $range) + 1;
                $index2 = array_search($arr[$count - 2], $range) + 1;
                $sum = $sum + $index1 * 9 + $index2 * 8;
                break;
        }
        $result = $sum % 11;
        if ($result === 0) {
            return array('code'=>1, 'msg'=>"身份证号正确");
        } else {
            return array('code'=>0, 'msg'=>"身份证号不正确");
        }
    }

    /**
     * 检验澳门身份证号码规则
     * 澳门身份证编码规则不明，待明确后再补写相应规则。
     * @param String $idCard 澳门身份证号码
     * @return Array
     * @author 张晖<tobutatu@gmail.com>
     */
    private function macroCheck($idCard) {
        $pattern = "/^[157]\d{6}\([\d]\)$/";
        if (preg_match($pattern, $idCard) === 1) {
            return array('code'=>1, 'msg'=>"身份证号正确");
        } else {
            return array('code'=>0, 'msg'=>"身份证号不正确");
        }
    }
    
    /**
     * 半角和全角转换函数
     * @param String $str 欲转换的字符串
     * @param Boolean $flag 标志，如果是0,则是半角到全角；如果是1，则是全角到半角
     * @return String
     */
    public function full2half($str, $flag = 1) { 
        $full = Array(
            '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', 'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ', 'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ', 'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
            'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ', 'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ', 'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ', 'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ', 'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
            'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ', 'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ', 'ｙ', 'ｚ', '－', '　', '：', '．', '，', '／', '％', '＃', '！', '＠', '＆', '（', '）',
            '＜', '＞', '＂', '＇', '？', '［', '］', '｛', '｝', '＼', '｜', '＋', '＝', '＿', '＾', '￥', '￣', '｀'
        );
        $half = Array(//半角
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y',
            'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '-', ' ', ':', '.', ',', '/', '%', '#', '!', '@', '&', '(', ')',
            '<', '>', '"', '\'', '?', '[', ']', '{', '}', '\\', '|', '+', '=', '_', '^', '$', '~', '`'
        );
        switch  ($flag) {
            case 0:
                $return = str_replace($half, $full, $str);  //半角到全角
                break;

            case 1:
                $return = str_replace($full, $half, $str);  //全角到半角
                break;

            default:
                $return = $str;
        }
        return $return;
    }
}

