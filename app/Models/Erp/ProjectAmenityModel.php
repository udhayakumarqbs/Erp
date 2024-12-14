<?php

namespace App\Models\Erp;

use CodeIgniter\Model;

class ProjectAmenityModel extends Model
{
    protected $table      = 'project_amenity';
    protected $primaryKey = 'project_amen_id';

    protected $allowedFields = [
        'project_id',
        'amenity_id',
    ];

    protected $useTimestamps = false;
}
