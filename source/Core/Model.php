<?php

namespace Source\Core;

use PDOException;
use Source\Core\Connect;
use Source\Support\Message;

/**
 * Class Model Layer Supertype Pattern
 * 
 * @author Pablo O.Mesquita <pablo_omesquita@hotmail.com>
 * @package Source\Core
 */

abstract class Model
{

    /** @var string $entity database table */
    protected $entity;

    /** @var array $protected */
    protected $protected;

    /** @var array $required */
    protected $required;

    /** @var string $query */
    protected $query;

    /** @var object|null $data */
    protected $data;

    /** @var array|null */
    protected $params;

    /** @var string */
    protected $order;
    
    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var \PDOException|null */
    protected $fail;

    /** @var Message */
    protected $message;
    
    /**
     * __construct
     *
     * @param string $entity
     * @param array $protected
     * @param array $required
     */
    public function __construct(string $entity, array $protected, array $required)
    {
        $this->entity = $entity;
        $this->protected = array_merge($protected, ['created_at', "updated_at"]);
        $this->required = $required;
        $this->message = new Message();
    }

    /**
     * __set
     * @param $name
     * @param 
     */
    public function __set($name, $value)
    {
        if(empty($this->data)){
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * __get
     *
     * @param  $name
     * @return 
     */
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * data
     *
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * fail
     *
     * @return
     */
    public function fail()
    {
        return $this->fail ?? null;
    }

    /**
     * message
     *
     * @return null|Message
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * limit
     *
     * @param integer $limit 
     */
    public function limit(int $limit)
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset =  " OFFSET {$offset}";
        return $this;
    }

    /**
     * find
     *
     * @param string|null $terms
     * @param string|null $params
     * @param string|null $columns
     * 
     */
    public function find(?string $terms = null, ?string $params = null, $columns = '*')
    {
        if(!empty($terms)){
            $this->query = "SELECT {$columns} FROM `{$this->entity}` WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        
        }

        $this->query = "SELECT {$columns} FROM `{$this->entity}`";
        return $this;
    }

        /**
     * findById
     *
     * @param integer $id
     * @return
     */
    public function findById(int $id)
    {
        $find = $this->read("SELECT * FROM users WHERE id=:id", "id={$id}");

        return $find->fetchObject(static::class);
    }

    /**
     * fetch
     *
     * @param boolean $all
     * 
     */
    public function fetch(bool $all = false)
    {
        try{
            $stmt = Connect::getInstance()->prepare($this->query .  $this->order . $this->limit . $this->offset);

            $stmt->execute($this->params);

            if(!$stmt->rowCount()){
                return null;
            }

            if($all){
                return $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
            }

            return $stmt->fetchObject(static::class);

        }catch(\PDOException $exception)
        {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * create
     *
     * @param array $data
     * @return integer|null
     */
    public function create(array $data): ?int
    {
        try{
            $columns = implode(", ", array_keys($data));
            $values = ":".implode(", :", array_keys($data));

            $stmt =  Connect::getInstance()->prepare("INSERT INTO {$this->entity} ({$columns}) VALUES({$values})");


            $stmt->execute($this->filter($data));

            return Connect::getInstance()->lastInsertId();

           
        }catch(\PDOException $exception)
        {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * read
     *
     * @param string $select
     * @param [type] $params
     * @return 
     */
    public function read(string $select, $params = null)
    {
        try{

            $stmt = Connect::getInstance()->prepare($select);

            if($params){
                parse_str($params, $params);
                foreach($params as $key => $value){
                    $type = (is_numeric($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                    $stmt->bindValue(":{$key}", $value , $type);
                }
            }

            $stmt->execute();

            return $stmt;

        }catch(\PDOException $exception){
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * update
     *
     * @param  string $entity
     * @param  array $data
     * @param  string $terms
     * @param  string $params
     * @return void
     */
    public function update(array $data, string $terms = null, string $params = null)
    {
        try{

            $dataSet = [];

            foreach($data as $bind => $value){
                $dataSet[] = "{$bind} = :{$bind}";
            }

            $dataSet = implode(", ", $dataSet);

            $stmt = Connect::getInstance()->prepare("UPDATE {$this->entity} SET {$dataSet} WHERE {$terms}");

            parse_str($params, $params);

            $stmt->execute($this->filter(array_merge($data, $params)));

            return ($stmt->rowCount() ?? 1);

        }catch(\PDOException $exception){
            $this->fail = $exception;
            var_dump($exception);
            return null;
        }
    }

    /**
     * delete
     *
     * @param string $terms
     * @param  $params
     * @return boolean|null
     */
    public function delete(string $terms,  $params): ?bool
    {
        try{
            $stmt = Connect::getInstance()->prepare("DELETE FROM `{$this->entity}` WHERE {$terms}");
            if($params){
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

        }catch(\PDOException $exception){
            $this->fail =  $exception;
            return false;
        }
    }

    /**
     * destroy
     *
     * @return bool
     */
    public function destroy(): bool
    {
        if(empty($this->id)){
            return false;
        }

        $destroy = $this->delete("id = :id", "id={$this->id}");
        return $destroy;
    }

    /**
     * save
     *
     * @return bool
     */
    public function save(): bool
    {
        if(!$this->required()){
            $this->message->info("Preencha todos os campos para continuar");
            return false;
        }

        /** update */
        if(!empty($this->id)){
            $id = $this->id;
            $this->update($this->filter($this->safe()), "id = :id", "id={$id}");
            if($this->fail()){
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        if(empty($this->id)){
            $id = $this->create($this->filter($this->safe()));
            if($this->fail()){
                $this->message->info('Erro ao cadastrar, verifique os dados');
                return false;
            }
        }


        $this->data = $this->find("id=:id", "id={$id}")->fetch()->data();
        return true;
    }

    /**
     * safe
     *
     * @return array|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach($this->protected as $unset){
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * filter
     *
     * @param array $data
     * @return array:null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach($data as $key => $value){
            $filter[$key] =  (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }

        return $filter;
    }

    /**
     * required
     *
     * @return boolean|null
     */
    public function required(): ?bool
    {
        $data = (array)$this->data();
        foreach($this->required as $field){
            if(empty($data[$field])){
                return false;
            }
        }

        return true;
    }


}