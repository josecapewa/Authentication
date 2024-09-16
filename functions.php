<?php
    require_once('load.php');
    function first_character($str){
        $val = str_replace('-', " ", $str);
        $var = ucfirst($val);
        return $val;
    }

     
    function validate_identification($string){
        if(strlen($string)==14){
            $substring = substr($string, 9, 2);
            if(ctype_alpha($substring)){
                return true;
            }
        }
        return false;
    }
    
    function display_msg($msg = ''){
        $output = array();
        if(!empty($msg) && is_array($msg)){
            foreach($msg as $key => $value){
                $output = "<div class=\"alert alert-{$key}\">";
                $output .= "<a href=\"#\" class=\"close\" data-dismiss=\"alert\">&times;</a>";
                $output .= first_character($value);
                $output .= "</div>";
            }
            return $output;
        } else{
            return "";
        }
    }

    function find_all_user(){
        global $db;
        $sql = "SELECT u.id, u.identification,u.image,u.name,u.email,u.recuperation_email,u.password,u.user_level, u.auth,u.bi_front,u.bi_back, l.level_name FROM user u LEFT JOIN user_level l ON l.number=u.user_level ORDER BY u.name ASC";
        $result = $db->query($sql);
        $set_result = $db->while_loop($result);
        return $set_result;
    }
    function search_init($search_string){
        global $db;
        $sql = "SELECT u.id,u.image,u.name,u.email,u.recuperation_email,u.password,u.user_level, u.auth,l.level_name FROM user u LEFT JOIN user_level l ON l.number=u.user_level WHERE u.name LIKE CONCAT('%', '$search_string', '%') OR u.email LIKE CONCAT('%', '$search_string', '%') OR u.recuperation_email LIKE CONCAT('%', '$search_string', '%')  OR l.level_name LIKE CONCAT('%', '$search_string', '%') ORDER BY u.name ASC";
        return $result = $db->query($sql);
    }
    function search($search_string){
        global $db;
        $set_result = $db->while_loop(search_init($search_string));
        return $set_result;
      }
    

?>