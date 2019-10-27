<?php

namespace App\Models;

class Author extends Model {
    protected $fillable = ['name'];

    public function addAuthor(array $fields) {
        $result = $this->insert($fields);

        return $result;
    }

    public function getAuthors($page = 0, $perPage = 10) {
        $authors = $this->get($page, $perPage);

        return $authors;
    }

    public function getAuthor($id) {
        $author = $this->find($id);

        return $author;
    }

    public function deleteAuthor($id) {
        $result = $this->delete($id);

        return $result;
    }

    public function updateAuthor($author, $id) {
        $result = $this->update($author, $id);

        return $result;
    }
}