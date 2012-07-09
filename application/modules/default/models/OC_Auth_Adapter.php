<?php

require_once 'Zend/Auth/Adapter/Exception.php';

class OC_Auth_Adapter extends Zend_Auth_Adapter_DbTable
{

    /**
     * $_idColumn - the column to use as the id
     *
     * @var string
     */
    protected $_idColumn = null;
    
    /**
     * $_hashColumn - the column to use as the hash
     *
     * @var string
     */
    protected $_hashColumn = null;

    /**
     * $_saltColumns - columns to be used as the salt
     *
     * @var string
     */
    protected $_saltColumn = null;
    
    /**
     * __construct() - Sets configuration options
     *
     * @param  Zend_Db_Adapter_Abstract $zendDb If null, default database adapter assumed
     * @param  string                   $tableName
     * @param  string                   $identityColumn
     * @param  string                   $hashColumn
     * @param  string                   $saltColumn
     * @param  string                   $idColumn
     * @return void
     */
    public function __construct(Zend_Db_Adapter_Abstract $zendDb = null, $tableName = null, $identityColumn = null,
                                $hashColumn = null, $saltColumn = null, $idColumn = null)
    {
        $this->_setDbAdapter($zendDb);

        if (null !== $tableName) {
            $this->setTableName($tableName);
        }

        if (null !== $identityColumn) {
            $this->setIdentityColumn($identityColumn);
        }

        if (null !== $hashColumn) {
            $this->setHashColumn($hashColumn);
        }

        if (null !== $saltColumn) {
            $this->setSaltColumn($saltColumn);
        }

        if (null !== $idColumn) {
            $this->setIdColumn($idColumn);
        }
    }
    
    /**
     * setCredentialColumn() - Ei tee mitään!
     *
     * @param  string $credentialColumn
     * @return void
     */
    public function setCredentialColumn($credentialColumn)
    {}
    
    /**
     * setCredentialTreatment() - Ei tee mitään!
     *
     * @param  string $treatment
     * @return void
     */
    public function setCredentialTreatment($treatment)
    {}

    /**
     * setAmbiguityIdentity() - Ei tee mitään!
     *
     * @param  int|bool $flag
     * @return void
     */
    public function setAmbiguityIdentity($flag)
    {}
    
    /**
     * setHashColumn() - set the column name to be used as the hash column
     *
     * @param  string $hashColumn
     * @return OC_Auth_Adapter Provides a fluent interface
     */
    public function setHashColumn($hashColumn)
    {
        $this->_hashColumn = $hashColumn;
        return $this;
    }
    
    /**
     * setSaltColumn() - set the column name to be used as the salt column
     *
     * @param  string $saltColumn
     * @return OC_Auth_Adapter Provides a fluent interface
     */
    public function setSaltColumn($saltColumn)
    {
        $this->_saltColumn = $saltColumn;
        return $this;
    }
    
    /**
     * setIdColumn() - set the column name to be used as the id column
     *
     * @param  string $idColumn
     * @return OC_Auth_Adapter Provides a fluent interface
     */
    public function setIdColumn($idColumn)
    {
        $this->_idColumn = $idColumn;
        return $this;
    }
    
    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     * 
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $this->_authenticateSetup();
        $dbSelect = $this->_authenticateCreateSelect();
        $resultIdentities = $this->_authenticateQuerySelect($dbSelect);

        if ( ($authResult = $this->_authenticateValidateResultSet($resultIdentities)) instanceof Zend_Auth_Result) {
            return $authResult;
        }

        $authResult = $this->_authenticateValidateResult(array_shift($resultIdentities));
        return $authResult;
    }
    
    /**
     * _authenticateSetup() - This method abstracts the steps involved with
     * making sure that this adapter was indeed setup properly with all
     * required pieces of information.
     *
     * @throws Zend_Auth_Adapter_Exception - in the event that setup was not done properly
     * @return true
     */
    protected function _authenticateSetup()
    {
        $exception = null;

        if ($this->_tableName == '') {
            $exception = 'A table must be supplied for the OC_Auth_Adapter authentication adapter.';
        } elseif ($this->_identityColumn == '') {
            $exception = 'An identity column must be supplied for the OC_Auth_Adapter authentication adapter.';
        } elseif ($this->_hashColumn == '') {
            $exception = 'A hash column must be supplied for the OC_Auth_Adapter authentication adapter.';
        } elseif ($this->_saltColumn == '') {
            $exception = 'A salt column must be supplied for the OC_Auth_Adapter authentication adapter.';
        } elseif ($this->_idColumn == '') {
            $exception = 'A id column must be supplied for the OC_Auth_Adapter authentication adapter.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with OC_Auth_Adapter.';
        } elseif ($this->_credential === null) {
            $exception = 'A credential value was not provided prior to authentication with OC_Auth_Adapter.';
        }

        if (null !== $exception) {
            throw new Zend_Auth_Adapter_Exception($exception);
        }

        $this->_authenticateResultInfo = array(
            'code'     => Zend_Auth_Result::FAILURE,
            'identity' => $this->_identity,
            'messages' => array()
            );

        return true;
    }

    /**
     * _authenticateCreateSelect() - This method creates a Zend_Db_Select object that
     * is completely configured to be queried against the database.
     *
     * @return Zend_Db_Select
     */
    protected function _authenticateCreateSelect()
    {
        // get select
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->_tableName)
                 ->where($this->_zendDb->quoteIdentifier($this->_identityColumn, true) . ' = ?', $this->_identity);

        return $dbSelect;
    }

    /**
     * _authenticateQuerySelect() - This method accepts a Zend_Db_Select object and
     * performs a query against the database with that object.
     *
     * @param Zend_Db_Select $dbSelect
     * @throws Zend_Auth_Adapter_Exception - when an invalid select
     *                                       object is encountered
     * @return array
     */
    protected function _authenticateQuerySelect(Zend_Db_Select $dbSelect)
    {
        try {
            if ($this->_zendDb->getFetchMode() != Zend_DB::FETCH_ASSOC) {
                $origDbFetchMode = $this->_zendDb->getFetchMode();
                $this->_zendDb->setFetchMode(Zend_DB::FETCH_ASSOC);
            }
            $resultIdentities = $this->_zendDb->fetchAll($dbSelect);
            if (isset($origDbFetchMode)) {
                $this->_zendDb->setFetchMode($origDbFetchMode);
                unset($origDbFetchMode);
            }
        } catch (Exception $e) {
            throw new Zend_Auth_Adapter_Exception('The supplied parameters to OC_Auth_Adapter failed to '
                                                . 'produce a valid sql statement, please check table and column names '
                                                . 'for validity.', 0, $e);
        }
        return $resultIdentities;
    }

    /**
     * _authenticateValidateResult() - This method attempts to validate that
     * the record in the resultset is indeed a record that matched the
     * identity provided to this adapter.
     *
     * @param array $resultIdentity
     * @return Zend_Auth_Result
     */
    protected function _authenticateValidateResult($resultIdentity)
    {
        $hash = $this->_generateHash($resultIdentity);

        if ($resultIdentity[$this->_hashColumn] != $hash) {
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
            $this->_authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
            return $this->_authenticateCreateAuthResult();
        }

        $this->_resultRow = $resultIdentity;
        
        $id = $resultIdentity[$this->_idColumn];

        $this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
        $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
        // Muutetaan identiteetti käyttäjänimestä ID:ksi.
        $this->_authenticateResultInfo['identity'] = $id;
        
        return $this->_authenticateCreateAuthResult();
    }
    
    /**
     * _generateHash - laskee salasanan (credential) ja suolan perusteella
     * hash-kentän arvon.
     * 
     * @param array $resultIdentity 
     * @return string hash 
     */
    protected function _generateHash($resultIdentity)
    {
        $credential = $this->_credential;
        $salt = $resultIdentity[$this->_saltColumn];
        
        // $blowfished = '$2a$10$salt(21)hash(32)'
        $blowfished = crypt($credential, '$2a$10$' . $salt . '$');
        $hash = substr($blowfished, 28);
        
        return $hash;
    }
}
