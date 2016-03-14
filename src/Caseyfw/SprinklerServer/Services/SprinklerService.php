<?php

namespace Caseyfw\SprinklerServer\Services;

class SprinklerService extends BaseService
{

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM sprinklers");
    }

    function save($sprinkler)
    {
        $this->db->insert("sprinklers", $sprinkler);
        return $this->db->lastInsertId();
    }

    function update($id, $sprinkler)
    {
        return $this->db->update('sprinklers', $sprinkler, ['id' => $id]);
    }

    function delete($id)
    {
        return $this->db->delete("sprinklers", array("id" => $id));
    }

}