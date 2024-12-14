<?php

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

//extend from this model to execute basic db operations
class My_Crud_model extends Model {

    protected $table;
    protected $table_without_prefix;    
    protected $db;
    protected $db_builder = null;
    private $log_activity = false;
    protected $allowedFields = array();
    
    function __construct($table = null, $db = null) {
        $this->db = $db ? $db : db_connect('default');
        $this->db->query("SET sql_mode = ''");
        $this->use_table($table);
    }

    protected function use_table($table) {
        $db_prefix = $this->db->getPrefix();
        $this->table = $db_prefix . $table;
        $this->table_without_prefix = $table;
        $this->db_builder = $this->db->table($this->table);
    }


    function get_one($id = 0) {
        return $this->get_one_where(array('id' => $id));
    }

    function get_one_where($where = array()) {
        $where = $this->escape_array($where);
        $result = $this->db_builder->getWhere($where, 1);

        if ($result->getRow()) {
            return $result->getRow();
        } else {
            $db_fields = $this->db->getFieldNames($this->table);
            $fields = new \stdClass();
            foreach ($db_fields as $field) {
                $fields->$field = "";
            }

            return $fields;
        }
    }

    function get_all($include_deleted = false) {
        $where = array("deleted" => 0);
        if ($include_deleted) {
            $where = array();
        }
        return $this->get_all_where($where);
    }

    function escape_array($values = array()) {
        if ($values && is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = ($value && !is_array($value)) ? $this->db->escapeString($value) : $value;
            }
        }

        return $values;
    }

    function get_all_where($where = array(), $limit = 1000000, $offset = 0, $sort_by_field = null) {
        $where = $this->escape_array($where);
        $where_in = get_array_value($where, "where_in");
        if ($where_in) {
            foreach ($where_in as $key => $value) {
                $this->db_builder->whereIn($key, $value);
            }
            unset($where["where_in"]);
        }

        if ($sort_by_field) {
            $this->db_builder->orderBy($sort_by_field, 'ASC');
        }

        return $this->db_builder->getWhere($where, $limit, $offset);
    }

    function ci_save(&$data = array(), $id = 0) {
        //allowed fields should be assigned
        $db_fields = $this->db->getFieldNames($this->table);
        foreach ($db_fields as $field) {
            if ($field !== "id") {
                array_push($this->allowedFields, $field);
            }
        }

        if ($id) {
            $id = $this->db->escapeString($id);

            //update
            $where = array("id" => $id);

            //to log an activity we have to know the changes. now collect the data before update anything
            if ($this->log_activity) {
                $data_before_update = $this->get_one($id);
            }

            $success = $this->update_where($data, $where);

            return $success;
        } else {
            //insert
            if ($this->db_builder->insert($data)) {
                $insert_id = $this->db->insertID();

                return $insert_id;
            }
        }
    }

    function update_where($data = array(), $where = array()) {
        if (count($where)) {
            if ($this->db_builder->update($data, $where)) {
                $id = get_array_value($where, "id");
                if ($id) {
                    return $id;
                } else {
                    return true;
                }
            }
        }
    }

    function delete($id = 0, $undo = false) {
        validate_numeric_value($id);
        $data = array('deleted' => 1);
        if ($undo === true) {
            $data = array('deleted' => 0);
        }
        $this->db_builder->where("id", $id);
        $success = $this->db_builder->update($data);
        
        return $success;
    }


    function delete_permanently($id = 0) {
        if ($id) {
            validate_numeric_value($id);
            $this->db_builder->where('id', $id);
            $this->db_builder->delete();
        }
    }

    protected function _get_clean_value($options, $key) {

        $value = get_array_value($options, $key);
        if ($value) {
            return $this->db->escapeString($value);
        } else {
            return $value; //false, 0, null
        }
    }

}
