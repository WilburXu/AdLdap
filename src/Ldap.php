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

    protected $_errorInfo = null;

    protected $_errorNo = null;

    /**
     * connection
     * @param null $host
     * @param int $port
     * @return resource
     */
    public function connect($host = null, $port = 389)
    {
        $this->_conn = @ldap_connect($host, $port);
        ldap_set_option($this->_conn, LDAP_OPT_DEBUG_LEVEL, 7);
        ldap_set_option($this->_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
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


    public function search($baseDn = null, $filter = null)
    {
        if (empty($baseDn) || empty($filter)) {
            return false;
        }

        $searchResults = ldap_search($this->_conn, $baseDn, ($filter));

        if ($this->_errorNo = $this->ldapErrno()) {
            return $this->_errorNo;
        }

        $data = self::getEntries($searchResults);

        return $data;
    }

    public function ldapModify($baseDn = null, $modifyInfo)
    {
        return @ldap_modify($this->_conn, $baseDn, $modifyInfo);
    }

    /**
     * set unicodePwd password
     * @param $password
     * @return string
     */
    public function setPassword($password)
    {
        if (empty($password)) {
            return 'password is empty';
        }

        return iconv('UTF-8', 'UTF-16LE', '"'. $password .'"');
    }

    /**
     * {@inheritdoc}
     */
    private function getEntries($searchResults)
    {
        return @ldap_get_entries($this->_conn, $searchResults);
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
     * get the errno from ldap
     * @return int
     */
    public function ldapErrno()
    {
        return ldap_errno($this->_conn);
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