<?php

class Test
{
	protected $__id;
	protected $__name;
	protected $__duration;
	protected $__description;

	protected $config = array(
		'nameMaxLength'   => 50,
		'timeMaxDuration' => 120,
	);

	public function __construct( $id = false )
	{
		$this->db = DB::getInstance();

		$id = intval($id);

		if( !empty($id) )
		{
			$res = $this->db->query('select * from Tests where TestId = '.$id);
			$row = $res->fetch_assoc();
			$this->__id          = $row['TestId'];
			$this->__name        = $row['TestName'];
			$this->__duration    = $row['TestDuration'];
			$this->__description = $row['TestDescription'];
		}
	}

	public function __get( $name )
	{
		if( property_exists($this, '__'.$name ))
		{
			return $this->{'__'.$name};
		}
		else
		{
			return false;
		}
	}

	public function setName( $name )
	{
		if( !empty($name) && mb_strlen($name) < $this->config['nameMaxLength'] )
		{
			$this->__name = $name;
		}
		else
		{
			throw new Exception('['.__CLASS__.'] Niepoprawna nazwa testu');
		}
	}

	public function setDuration( $duration )
	{
		$duration = intval($duration);
		if( $duration > 0 && $duration < $this->config['timeMaxDuration'] )
		{
			$this->__duration = $duration;
		}
		else
		{
			throw new Exception('['.__CLASS__.'] Niepoprawny czas trwania testu');
		}
	}

	public function setDescription( $description )
	{
		$this->__description = $description;
	}

	public function save()
	{
		if( !empty($this->__id) )
		{
			$res = $this->db->query('update Tests set
				TestName = "'. $this->__name .'",
				TestDuration = "'. $this->__duration .'",
				TestDescription = "'. $this->__description .'"
				where TestId = '. $this->__id);
		}
		else
		{
			$res = $this->db->query('insert into Tests
				(TestName, TestDuration, TestDescription)
				values ("'.$this->__name.'",'.$this->__duration.',"'.$this->__description.'")');
			$this->__id = $this->db->insert_id;
		}

		if( $res === false )
		{
			throw new Exception('['.__CLASS__.'] Błąd aktualizacji bazy (<i>'.$this->db->error.'</i>)');
		}

	}

}