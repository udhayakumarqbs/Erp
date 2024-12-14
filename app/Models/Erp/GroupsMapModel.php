<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class GroupsMapModel extends Model
{
    protected $table = 'erp_groups_map';
    protected $primaryKey = 'groupmap_id';
    protected $allowedFields = ['groupmap_id', 'group_id', 'related_id'];
}
