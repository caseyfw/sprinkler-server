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

    public function getInstruction($id)
    {
        $sprinkler = $this->get($id);
        switch ($sprinkler['state']) {
            case 'turning_on':
                $sprinkler['state'] = 'on';
                $this->update($id, $sprinkler);
                return 'turn_on';

            case 'turning_off':
                $sprinkler['state'] = 'off';
                $this->update($id, $sprinkler);
                return 'turn_off';

            case 'on':
            case 'off':
                return 'stay_' . $sprinkler['state'];
            default:
                return 'error';
        }
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
