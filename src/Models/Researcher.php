<?php

namespace Whozidis\HallOfFame\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Researcher extends BaseModel
{
    protected $table = 'hof_researchers';

    protected $fillable = [
        'name',
        'email',
        'website',
        'twitter',
        'github',
        'bio',
    ];

    public function vulnerabilityReports(): HasMany
    {
        return $this->hasMany(VulnerabilityReport::class, 'researcher_id');
    }
}
