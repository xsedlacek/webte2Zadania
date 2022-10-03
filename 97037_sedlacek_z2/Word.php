<?php

class Word
{
    protected $db;
    protected int $id;
    protected string $title;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    /**
     * @return mixed
     */

    public function __construct(MyPDO $db)
    {
        $this->db = $db;

    }

    public function find($id){
        $data = $this->db->run("SELECT * FROM words WHERE id = ?",[$id])->fetch();
        $this->id = $data['id'];
        $this->title = $data['title'];
    }

    public function save(){
        $this->db->run("INSERT INTO `words`(`title`) VALUES (?)",[$this->title])->fetch();
        $this->id = $this->db->lastInsertId();
    }

}