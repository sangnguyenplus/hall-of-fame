<?php

namespace Whozidis\HallOfFame\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Researcher extends Authenticatable
{
    use Notifiable;

    protected $table = 'hof_researchers';

    protected $fillable = [
        'name',
        'email',
        'website',
        'twitter',
        'github',
        'bio',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function vulnerabilityReports(): HasMany
    {
        return $this->hasMany(VulnerabilityReport::class, 'researcher_id');
    }
}
