<?php 

	
class character{

	private $id;
	private $name;
	private $avatar;
	private $hp;
	private $last_combo_hit;
	private $hit_count;


	/**
	 * Class Constructor
	 * @param    $array     
	 */
	public function __construct($array){
		$this->id = $array['id'] ?? "";
		$this->name = $array['name'];
		$this->avatar = $array['avatar'];
		$this->hp = $array['hp'] ?? "";
		$this->last_combo_hit = $array['last_combo_hit'] ?? "";
		$this->hit_count = $array['hit_count'] ?? "";
	}

	//GETTERS AND SETTERS

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     *
     * @return self
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHp()
    {
        return $this->hp;
    }

    /**
     * @param mixed $hp
     *
     * @return self
     */
    public function setHp($hp)
    {
        $this->hp = $hp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastComboHit()
    {
        return $this->last_combo_hit;
    }

    /**
     * @param mixed $last_combo_hit
     *
     * @return self
     */
    public function setLastComboHit($last_combo_hit)
    {
        $this->last_combo_hit = $last_combo_hit;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHitCount()
    {
        return $this->hit_count;
    }

    /**
     * @param mixed $hit_count
     *
     * @return self
     */
    public function setHitCount($hit_count)
    {
        $this->hit_count = $hit_count;

        return $this;
    }
}	

?>