<?php

namespace Caseyfw\SprinklerServer\Services;

class SprinklerService extends BaseService
{

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM sprinklers");
    }

    public function get($id)
    {
        $sprinkler = $this->db->fetchAssoc(
            "SELECT * FROM sprinklers WHERE id = ?",
            [$id]
        );
        return $sprinkler;
    }

    public function save($sprinkler)
    {
        $this->db->insert("sprinklers", $sprinkler);
        return $this->db->lastInsertId();
    }

    public function update($id, $sprinkler)
    {
        return $this->db->update('sprinklers', $sprinkler, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete("sprinklers", ["id" => $id]);
    }
}
