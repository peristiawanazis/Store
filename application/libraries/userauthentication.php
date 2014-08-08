<?php
class userauthentication {
    private
            $ci,
            $usertable = 'users',
            $userprev,
            $sqlreserved,
            $sessionlifetime= 1500; //in second 300/60= 5 minute
    public $userdata= array (
            'userid'=> null,
            'passwd'=> null,
            'usergroup'=> "",
            'reserved1'=> "",
            'reserved2'=> "",
            'defaulthomepage'=> "welcome/signin/",
            'signinstat'=> null,
            'privilegeaccess'=> 0,
            'privilegeaccesstype'=> '',
            'signinstamp'=> null,
            'lastactivestamp'=> null,
            'ipaddress'=> null,
            'encrypttype'=> "md5",
            'sessname'=> "accessauthentification",
            'sessindex'=> "ACCAUTH",
            'appkeystamp'=> "OW",
            'privilegesource'=>"db" );//config or db
    function userauthentication($params= null) {
        $this->ci =& get_instance();
        $this->ci->load->library('encrypt');
        (@$params['usertable'])?$this->usertable= $params['usertable']:null;
        (@$params['reserved1'])?$this->sqlreserved= ",".$params['reserved1']." as RESERVED1 ":null;
        (@$params['reserved2'])?$this->sqlreserved= ",".$params['reserved2']." as RESERVED1 ":null;
        (@$this->ci->config->item('sessionlifetime'))?$this->sessionlifetime= $this->ci->config->item('sessionlifetime'):null;
        //echo $this->usertable;
    }
    function signin($userdata=null) {
        @$userdata?$this->userdata = $userdata:null;
        session_name($this->userdata['sessname']);
        @session_start();
        //echo $this->userdata['passwd']."<br />";
        $passworde = $this->userdata['encrypttype']($this->userdata['passwd']);
        //echo $password_sha1."<br />";
        $sqlcmd= "select BINARY '".$this->userdata['userid']."'=USERID AS COMPRESULT,USERID,USERGROUP,DEFAULTHOMEPAGE ".@$this->sqlreserved.
                " from ".$this->usertable." INNER JOIN usergroups ON USERGROUP=USERGROUPID".
                " where USERID='".$this->userdata['userid']."' and PASSWD='".$passworde."' and ISACTIVE=1";
        //echo $sqlcmd;
        $query= $this->ci->db->query($sqlcmd)->Result_Array();
        if (count($query)>=1) { //user id found
            if ($query[0]['COMPRESULT']==1) {
                $this->userdata['usergroup']= $query[0]['USERGROUP'];
                $this->userdata['ipaddress']= $this->ci->input->ip_address();
                $this->userdata['signinstamp']= date('Y-m-d H:i:s');
                $this->userdata['lastactivestamp']= date('Y-m-d H:i:s');
                $this->userdata['reserved1']= @$query[0]['RESERVED1'];
                $this->userdata['reserved2']= @$query[0]['RESERVED2'];
                $this->userdata['defaulthomepage']= @$query[0]['DEFAULTHOMEPAGE'];
                $this->writesession();
                return true;
            } else
                return false;
        } else {
            return false;
        }
    }
    function getsessiondata() {
        if (isset($_SESSION[$this->userdata['sessindex']])) {
            //return true;
            //print_r($_SESSION);
            //echo $_SESSION[$this->userdata['sessindex']];
            $rawsession= $this->ci->encrypt->decode($_SESSION[$this->userdata['sessindex']]);
            //echo $rawsession;
            $datasessions= explode('#',$rawsession);
            //print_r($datasessions);
            if (count($datasessions)!=8) {
                $this->userdata['signinstat']= false;
            } else {
                if ((strlen($datasessions[1].$datasessions[2].$datasessions[3].$datasessions[4].@$datasessions[5].@$datasessions[6].@$datasessions[7])+6)!=$datasessions[0]) {
                    $this->userdata['signinstat']= false;
                } else {
                    $this->userdata['userid']= $datasessions[1];
                    $this->userdata['usergroup']= $datasessions[2];
                    $this->userdata['ipaddress']= $datasessions[3];
                    $this->userdata['signinstamp']= $datasessions[4];
                    $this->userdata['lastactivestamp']= $datasessions[5];
                    $this->userdata['reserved1']= @$datasessions[6];
                    $this->userdata['reserved2']= @$datasessions[7];
                    //echo $this->userdata['lastactivestamp']."<br />";
                    //print_r (date_parse($this->userdata['lastactivestamp']))."<br />";
                    $currenttime= mktime();
                    //echo $this->userdata['currentstamp']."<br />";
                    $lastactiveparsed= date_parse($this->userdata['lastactivestamp']);
                    //echo "<br />currenttime ".$currenttime;
                    $lastactivetime= mktime($lastactiveparsed['hour'],$lastactiveparsed['minute'],$lastactiveparsed['second'],$lastactiveparsed['month'],$lastactiveparsed['day'],$lastactiveparsed['year']);
                    //echo "<br />lastactivetime ".$lastactivetime."<br />";
                    //echo ($currenttime-$lastactivetime)." > ".$this->sessionlifetime;
                    if (($currenttime-$lastactivetime)>$this->sessionlifetime) { //check interval signin and current activity if there are idle duration
                        $this->userdata['signinstat']= false;
                    } else {
                        //and longer than set in configuration then sign out
                        $this->userdata['lastactivestamp']= date('Y-m-d H:i:s');
                        $sqlcmd= "update ".$this->usertable." set LASTACTIVESTAMP='".$this->userdata['lastactivestamp']."' where USERID='".$this->userdata['userid']."'";
                        $this->ci->db->simple_query($sqlcmd);
                    }
                    $this->writesession();
                }
            }
            return $this->userdata;
        }
        return null;
    }
    function checksession($accesspath= null) { //$accesspath['classdirectory'], $accesspath['classname'], $accesspath['method']
        //ENCRYPTED(LENGTH#USERID#USERGROUP#IPADDRESS#SIGNINSTAMP)
        session_name($this->userdata['sessname']);
        @session_start();
        $this->userdata['signinstat']= true;
        if (isset($_SESSION[$this->userdata['sessindex']])) {
            //return true;
            //print_r($_SESSION);
            //echo $_SESSION[$this->userdata['sessindex']];
            $rawsession= $this->ci->encrypt->decode($_SESSION[$this->userdata['sessindex']]);
            //echo $rawsession;
            $datasessions= explode('#',$rawsession);
            //print_r($datasessions);
            if (count($datasessions)!=8) {
                $this->userdata['signinstat']= false;
            } else {
                if ((strlen($datasessions[1].$datasessions[2].$datasessions[3].$datasessions[4].@$datasessions[5].@$datasessions[6].@$datasessions[7])+6)!=$datasessions[0]) {
                    $this->userdata['signinstat']= false;
                } else {
                    $this->userdata['userid']= $datasessions[1];
                    $this->userdata['usergroup']= $datasessions[2];
                    $this->userdata['ipaddress']= $datasessions[3];
                    $this->userdata['signinstamp']= $datasessions[4];
                    $this->userdata['lastactivestamp']= $datasessions[5];
                    $this->userdata['reserved1']= @$datasessions[6];
                    $this->userdata['reserved2']= @$datasessions[7];
                    //echo $this->userdata['lastactivestamp']."<br />";
                    //print_r (date_parse($this->userdata['lastactivestamp']))."<br />";
                    $currenttime= mktime();
                    //echo $this->userdata['currentstamp']."<br />";
                    $lastactiveparsed= date_parse($this->userdata['lastactivestamp']);
                    //echo "<br />currenttime ".$currenttime;
                    $lastactivetime= mktime($lastactiveparsed['hour'],$lastactiveparsed['minute'],$lastactiveparsed['second'],$lastactiveparsed['month'],$lastactiveparsed['day'],$lastactiveparsed['year']);
                    //echo "<br />lastactivetime ".$lastactivetime."<br />";
                    //echo ($currenttime-$lastactivetime)." > ".$this->sessionlifetime;
                    if (($currenttime-$lastactivetime)>$this->sessionlifetime) { //check interval signin and current activity if there are idle duration
                        $this->userdata['signinstat']= false;
                    } else {
                        //and longer than set in configuration then sign out
                        $this->userdata['lastactivestamp']= date('Y-m-d H:i:s');
                        $sqlcmd= "update ".$this->usertable." set LASTACTIVESTAMP='".$this->userdata['lastactivestamp']."' where USERID='".$this->userdata['userid']."'";
                        $this->ci->db->simple_query($sqlcmd);
                    }
                    $this->writesession();
                }
            }
            if ($this->userdata['signinstat']) {
                //check privilege
                $accesspath['usergroup']= $this->userdata['usergroup'];
                $privileges= $this->ci->mmasterdata->getaccessprivilege($accesspath);
                foreach ($privileges as $privilege) {
                    $this->userdata['defaulthomepage']= $privilege['DEFAULTHOMEPAGE'];
                    $this->userdata['privilegeaccesstype']= $privilege['ACCESSTYPE'];
                    if ($privilege['METHOD']) {
                        //echo "METHOD ". $privilege['METHOD'];
                        $this->userdata['privilegeaccess']= $privilege['PRIVILEGETYPE'];
                        break;
                    } else if ($privilege['CLASSNAME']) {
                        //echo "CLASSNAME ". $privilege['CLASSNAME'];
                        $this->userdata['privilegeaccess']= $privilege['PRIVILEGETYPE'];
                        break;
                    } else if ($privilege['CLASSDIRECTORY']) {
                        //echo "CLASSDIRECTORY ". $privilege['CLASSDIRECTORY'];
                        $this->userdata['privilegeaccess']= $privilege['PRIVILEGETYPE'];
                        break;
                    }
                    //print_r($privilege);
                }
            }
        } else {
            $this->userdata['signinstat']= false;
            //return  false;
        }
        return  $this->userdata;
    }
    function signout() {
        if (!isset($this->userdata['signinstat']))
            $this->checksession();
        if (@$this->userdata['userid']) {
            $signoutstamp = date('Y-m-d H:i:s');
            $sqlcmd= "update ".$this->usertable." set SIGNOUTSTAMP='".$signoutstamp."' where USERID='".$this->userdata['userid']."'";
            //echo $sqlcmd;
            //die();
            $this->ci->db->simple_query($sqlcmd);
        }
        @session_start();
        session_name($this->userdata['sessname']);
        if (isset($_COOKIE[session_name($this->userdata['sessname'])])) {
            setcookie(session_name(), '', time()-42000, '/');
        }
        unset($_SESSION[$this->userdata['sessindex']]);
        session_destroy();
        return true;
    }
    function checkprivilegebydocid($docid, $cmd) {
        @$this->userdata['signinstat']?null:$this->checksession();
        @$this->userdata['privilegesource']=="config"?$accessconfig= $this->obj->config->item('accessprivileges'):null;
        $usercmd = @$accessprivileges[$this->userdata['usergroup']][$docid]?$accessprivileges[$this->userdata['usergroup']][$docid]:0;
        if($usercmd>=$cmd) {
            return true;
        } else {
            return false;
        }
    }
    function checkprivilegebydb() {

    }
    function checkprivilegebyconfig($params) {
        $privilegerules= $this->ci->config->item('privilegerules');
        if ($this->checksession()) {
            //echo "SIGNED IN";
        } else {
            //print_r($params);
            //echo "NO SESSION";
            if ($params[0]!="welcome") {
                redirect ("welcome/");
            }
        }
        //print_r($params);
    }
    function writesession() { //ENCRYPTED(LENGTH#USERID#USERGROUP#IPADDRESS#SIGNINSTAMP#LASTACTIVESTAMP)
        $sqlcmd= "update ".$this->usertable." set SIGNINSTAMP= '".$this->userdata['signinstamp']."',
		LASTACTIVESTAMP= '".$this->userdata['lastactivestamp']."',
		SIGNOUTSTAMP= '0000-00-00 00:00:00',
		IPADDRESS= '".$this->userdata['ipaddress']."'
		where USERID='".$this->userdata['userid']."'";
        if ($this->ci->db->simple_query($sqlcmd)) {
            session_name($this->userdata['sessname']);
            @session_start();
            $strlength= strlen($this->userdata['userid'].'#'.$this->userdata['usergroup'].'#'.$this->userdata['ipaddress'].'#'.$this->userdata['signinstamp'].'#'.$this->userdata['lastactivestamp'].'#'.@$this->userdata['reserved1'].'#'.@$this->userdata['reserved2']);
            $rawsession= $strlength."#".$this->userdata['userid'].'#'.$this->userdata['usergroup'].'#'.$this->userdata['ipaddress'].'#'.$this->userdata['signinstamp'].'#'.$this->userdata['lastactivestamp'].'#'.@$this->userdata['reserved1'].'#'.@$this->userdata['reserved2'];
            //echo $rawsession;
            $rawsessione= $this->ci->encrypt->encode($rawsession);
            //echo "<br />ENCODE RAW SESSION: ".$rawsessione;
            //$rawdecode= $this->ci->encrypt->decode($rawsessionencr);
            //echo "<br />".$rawdecode;
            $_SESSION[$this->userdata['sessindex']] = $rawsessione;
            //print_r($_SESSION);
        }
    }
}
?>