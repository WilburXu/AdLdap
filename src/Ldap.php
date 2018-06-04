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

    /**
     * connection
     * @param null $host
     * @param int $port
     * @return resource
     */
    public function connect($host = null, $port = 389)
    {
        $this->_conn = @ldap_connect($host, $port);

        return $this;
    }

    /**
     * user binding
     * @param null $username
     * @param null $password
     * @return bool
     */
    public function bind($username = null, $password = null)
    {
        if ($username && $password) {
            @ldap_bind($this->_conn, $username, $password);
        } else {
            @ldap_bind($this->_conn);
        }
        return $this;
    }

    /**
     * get the error from ldap
     * @return string
     */
    public function ldapError()
    {
        return ldap_error($this->_conn);
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