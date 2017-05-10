<?php
/*------------------------------------------
* @AUTHOR: TANDAT @EMAIL:tidusvn05@gmail.com
* @PHONE: +84933731173
* -----------------------------------------*/

/*------------------------------------------
* 1 crawler de get data
* 
* -----------------------------------------*/
class NHKModel extends Zend_Db_Table_Abstract {
	

	public function getLast($options=array()){
		$sql = $this->select()
		->order("id desc");
		
		if(isset($options["finished_at"])){
			$sql->where("finished_at {$options["finished_at"]}", $options["finished_at"]);
		}
		return $this->fetchRow($sql);
	}
    
    public function update(array $data, $where){
        $data["updated_at"] = date("Y-m-d H:i:s");
        return parent::update($data,$where);
    }
	
	public function insert(array $data){
		$data["created_at"] = date("Y-m-d H:i:s");
		$data["updated_at"] = date("Y-m-d H:i:s");
		//var_dump($data);
		//return;
		return parent::insert($data);
	}
	
	//fetch arow if exist
	//input: where data array
	// ex: array('a'=>1, 'b'=>2)  => where a = 1 and b = 2
	public function fetchOne($data){
		$sql = $this->select();
		if(count($data) >0):
			foreach ($data as $key => $value):
				$sql->where("$key=?",$value);
			endforeach;;
		endif;
		
		return $this->fetchRow($sql);
	}
	
	public function find_all($options = array()){
		$sql = $this->select()->setIntegrityCheck(false);
		$sql->where("deleted_at is null");		
		if(isset($options['order'])){
			$sql->order($options['order']);
		}
		return $this->fetchAll($sql);
	}
	
	//parse to array
	public function execute($sql){
		return $this->getAdapter()->fetchAll($sql);
	}
	
	public function quote($text, $value=""){
		return $this->getAdapter()->quoteInto($text, $value);
	}
	
	function finish($id){
		$data = array('finished_at' => Time::now());
		$this->update($data, "id=".$id);
	}
	
	//Utils
	public static function convert_To_Hash($datas, $key_name, $value_name){
		$result = array();
		foreach($datas as $item){
			$key = $item->$key_name;
			$value = $item->$value_name;
			$result["{$key}"] = $value;
		}
		//var_dump($result);
		return $result;
	}
	
	
	function find_by_options($options){
		$sql = $this->select()->setIntegrityCheck(false)
			->from(array('model' => $this->_name), array('*'))
		;
		//where
		if(!empty($options['where'])){
			foreach ($options['where'] as $key=>$value){
				$sql->where("$key = ?", $value);
			}
		}
		
		//sample: ['id'] = 'asc'
		if(!empty($options['order'])){ 
			foreach ($options['order'] as $key=>$value){
				$sql->order("$key $value");
			}
		}
		
		//paginator
		if(!empty($options['paginator'])){
			$start = $options['paginator']['item_per_page'] * ($options['paginator']['page'] - 1);
			echo $start;
			$sql->limit($options['paginator']['item_per_page'], $start);
		}
		
		return $this->fetchAll($sql); 
	}
	
	function paginator($options){
		$result = array();
		$sql = $this->select()->setIntegrityCheck(false)
		->from(array('model' => $this->_name), array('*'))
		;
		//where
		if(!empty($options['where'])){
			foreach ($options['where'] as $key=>$value){
				$sql->where("$key = ?", $value);
			}
		}
		//order
		//sample: ['id'] = 'asc'
		if(!empty($options['order'])){
			foreach ($options['order'] as $key=>$value){
				$sql->order("$key $value");
			}
		}
		$result['total'] = count( $this->fetchAll($sql) );
		
		//paginator
		if(!empty($options['paginator'])){
			$start = $options['paginator']['item_per_page'] * ($options['paginator']['page'] - 1);
			$sql->limit($options['paginator']['item_per_page'], $start);
		}
		
		$result['data'] = $this->fetchAll($sql);
		
		return $result;
	}
    
    function countAll(){
        $sql=$this->select()->setIntegrityCheck(false)
			->from(array('u'=>$this->_name),array('count(u.id) as total'))
			->where("deleted_at is null");
			;		
			
		return $this->fetchRow($sql)->total;
    }
	
	function deleted_by_conditions($conditions = array()){
		if(empty($conditions)){
			return false;
		}
		$data = array('deleted_at' => D_Utils::get_date());
		return  $this->update($data, $conditions);
	}
	
}

?>