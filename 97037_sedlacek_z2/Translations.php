<?php

class Translations
{
protected $db;
protected int $id;
protected string $title;
protected string $description;

protected int $word_id;
protected Word $word;

protected int $language_id;

public function __construct(MyPDO $db)
{
    $this->db = $db;

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
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getWordId(): int
    {
        return $this->word_id;
    }

    /**
     * @param int $word_id
     */
    public function setWordId(int $word_id): void
    {
        $this->word_id = $word_id;
    }

    /**
     * @return int
     */
    public function getLanguageId(): int
    {
        return $this->language_id;
    }

    /**
     * @param int $language_id
     */
    public function setLanguageId(int $language_id): void
    {
        $this->language_id = $language_id;
    }

    public function getAll(){
        $data = $this->db->run("SELECT * FROM Translations")->fetch();
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description =$data['description'];
        $this->word_id = $data['word_id'];
        $this->language_id = $data['language_id'];
        echo "$this";
    }
    public function find($id){
        $data = $this->db->run("SELECT * FROM Translations WHERE id = ?",[$id])->fetch();
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description =$data['description'];
        $this->word_id = $data['word_id'];
        $this->language_id = $data['language_id'];

    }


    public function save(){
        $this->db->run("INSERT INTO Translations (`title`,`description`,`language_id`,`word_id`) values (?,?,?,?)",
            [$this->title,$this->description,$this->language_id,$this->word_id]);
    }
}