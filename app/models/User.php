<?php



use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation;

class User extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    protected $login;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    protected $password;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    protected $firstname;

    /**
     *
     * @var string
     * @Column(type="string", length=45, nullable=true)
     */
    protected $lastname;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    protected $idrole;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field login
     *
     * @param string $login
     * @return $this
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Method to set the value of field password
     *
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Method to set the value of field firstname
     *
     * @param string $firstname
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Method to set the value of field lastname
     *
     * @param string $name
     * @return $this
     */
    public function setLastname($name)
    {
        $this->lastname = $name;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field idrole
     *
     * @param integer $idrole
     * @return $this
     */
    public function setIdrole($idrole)
    {
        $this->idrole = $idrole;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Returns the value of field password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the value of field firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Returns the value of field lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field idrole
     *
     * @return integer
     */
    public function getIdrole()
    {
        return $this->idrole;
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
     {
        $validator = new Validation();

        $validator->add(
            'email', //your field name
            new EmailValidator([
                'model' => $this,
                'message' => 'Please enter a correct email address'
            ])
        );

        return $this->validate($validator);
    }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Host', 'idUser', ['alias' => 'Host']);
        $this->hasMany('id', 'Virtualhost', 'idUser', ['alias' => 'Virtualhosts']);
        $this->belongsTo('idrole', 'Role', 'id', ['alias' => 'Role']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function __toString(){
    	return $this->firstname." ".$this->lastname." (".$this->login.")";
    }

}
