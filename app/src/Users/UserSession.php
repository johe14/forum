<?php

namespace Anax\Users;
 
class UserSession implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    /**
     * Login and get user data
     *
     */
    public function login($acronym, $password)
    {
        $this->db
            ->select('*')
            ->from('user')
            ->where("acronym = '$acronym'")
            ->execute();
        $userdata = $this->db->fetchAll();
        
        if (!$userdata) {
            return false;
        } else {
            if (password_verify($password, $userdata[0]->password)) {
                $content = "OK login for " . $acronym;
                $_SESSION['user'] = $userdata[0];
                return true;
            }
        }
    }
}
