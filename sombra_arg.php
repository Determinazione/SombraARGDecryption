<?php
$action = $argv[1];
$input = $argv[2]; // 取得要做的資料from command line.
$result = new authentication;
echo "\n";
if($action == "encrypt") {
    $output = $result->encrypt($input);
    echo "Result: " .$output;
} else if($action == "decrypt") {
    $output = $result->decrypt($input);
    echo  "Result: " .$output;
} else {
    echo "Sorry, let's try out again.";
}
echo "\n\n";

/***** from class.authentication.php*****/
class authentication {
    
    public function login($username, $password) {
        $decrypted = $this->decrypt($password);
        if($this->valid_password($username, $decrypted)) {
            return true;
        }
        return false;
    }
    
    public function encrypt($password) {
        $passArray = str_split($password);
        $encrypted = array();
        foreach($passArray as $char) {
            $salt = count($encrypted);
            $char = base64_encode(dechex(ord($this->str_rot($char, ($salt + 3))) * 3));
            if($salt % 2 == 0) $char = strrev($char);
            array_push($encrypted, $char);
        }
        $encrypted = implode(":", $encrypted);
        $encrypted = str_replace("=", "?", $encrypted);
        return $encrypted;
    }
    
    public function decrypt($password) { // $password is a string.
        /**************/
        
        $password = str_replace("?", "=", $password);
        $password = explode(":", $password);
        $decrypted = array();
        
        foreach($password as $char) {
            $salt = count($decrypted);
            echo "[" .$salt ."] => " .$char ."\t";
            
            if ($salt % 2 == 0) {
                $char = strrev($char);
            }
            //$char = $this->str_rot(chr( hexdec(base64_decode($char)) / 3), 26 - ($salt + 3));
            echo $char ."\t";
            
            $char = base64_decode($char);
            echo $char ."\t"; // 將base64編碼轉換回來
            
            $char = hexdec($char);
            echo $char ."\t"; // 十六進位換回十進位
            
            $char = chr($char / 3);
            echo $char ."\t"; // 轉回ASCII編碼
            
            $char = $this->str_rot($char, 26 - ($salt + 3));
            echo $char ."\n"; // 輪轉字元
            
            array_push($decrypted, $char);
        }
        $decrypted = implode("", $decrypted);
        return $decrypted;
    }
    
    public function str_rot($word, $offset) {
        $ASCII = ord($word); // 轉換為ASCII value
        
        // 只針對字母做位移(Caesar Cipher)
        if(($ASCII >= 65) && ($ASCII <= 90)) {
            $ASCII = (($ASCII - 65 + $offset) % 26) + 65;
        } else if(($ASCII >= 97) && ($ASCII <= 122)) {
            $ASCII = (($ASCII - 97 + $offset) % 26) + 97;
        }
        $word = chr($ASCII);
        return $word;
    }
}
?>
