<?php

namespace App\Models\HelpTicket;

use Config;
use DB;
use Carbon\Carbon;
use App\Models\Model;
use App\Traits\Commentable;

class HelpTicket extends Model
{
    use Commentable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'staff_id', 'url',
        'comments', 'staff_comments', 'parsed_staff_comments',
        'status', 'data', 'error_type', 'is_br', 'ticket_type'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'help_ticket';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;
    
    /**
     * Validation rules for helpticket creation.
     *
     * @var array
     */
    public static $createRules = [
        'url' => 'required',
    ];
    
    /**
     * Validation rules for helpticket updating.
     *
     * @var array
     */
    public static $updateRules = [
        'url' => 'required',
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    /**
     * Get the user who made the helpticket.
     */
    public function user() 
    {
        return $this->belongsTo('App\Models\User\User', 'user_id');
    }
    
    /**
     * Get the staff who processed the helpticket.
     */
    public function staff() 
    {
        return $this->belongsTo('App\Models\User\User', 'staff_id');
    }

    /**********************************************************************************************
    
        SCOPES

    **********************************************************************************************/

    /**
     * Scope a query to only include pending helptickets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include helptickets assigned to a given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssignedToMe($query, $user)
    {
        return $query->where('status', 'Assigned')->where('staff_id', $user->id);
    }

    /**
     * Scope a query to only include viewable helptickets.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeViewable($query, $user)
    {
        if($user && $user->hasPower('manage_helptickets')) return $query;
        return $query->where(function($query) use ($user) {
            if($user) $query->where('user_id', $user->id)->orWhere('error_type', '!=', 'exploit');
            else $query->where('error_type', '!=', 'exploit');
        });
    }

    /**
     * Scope a query to sort helptickets oldest first.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortOldest($query)
    {
        return $query->orderBy('id');
    }

    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the data attribute as an associative array.
     *
     * @return array
     */
    public function getDataAttribute()
    {
        return json_decode($this->attributes['data'], true);
    }

    /**
     * Get the viewing URL of the helpticket/claim.
     *
     * @return string
     */
    public function getViewUrlAttribute()
    {
        return url('helptickets/view/'.$this->id);
    }

    /**
     * Get the admin URL (for processing purposes) of the submission/claim.
     *
     * @return string
     */
    public function getAdminUrlAttribute()
    {
        return url('admin/helptickets/edit/'.$this->id);
    }

    /**
     * Displays the news post title, linked to the news post itself.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return '<a href="'.$this->viewurl.'">'.'helpticket #-' . $this->id.'</a>';
    }

}
