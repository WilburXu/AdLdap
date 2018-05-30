<?php
/**
 * User: WilburXu
 * Date: 2018/5/29
 * Time: 17:21
 */

namespace AdLdap;

class Ldap
{
    protected $_conn = null;

    public function connect($host = null, $port = 389)
    {
        return $this->_conn = ldap_connect($host, $port);
    }

    /**
     * __destruct
     */
    public function __destruct()
    {
        if ($this->_conn) {
            
            ldap_close($this->_conn);

        }
    }
}