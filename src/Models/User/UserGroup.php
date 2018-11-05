<?php
namespace DaydreamLab\User\Models\User;

use DaydreamLab\JJAJ\Models\BaseModel;
use Kalnoy\Nestedset\NodeTrait;

class UserGroup extends BaseModel
{
    use NodeTrait;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_groups';

    protected $order_by = 'id';

    protected $order = 'asc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'ordering',
        'created_by',
        'updated_by'
    ];


    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    protected $hidden = [
        '_lft',
        '_rgt',
        'pivot',
        'created_at',
        'updated_at'
    ];


    /**
     * The attributes that should be append for arrays
     *
     * @var array
     */
    protected $appends = [
        //'veiwlevels'
        'tree_title'
    ];



    public function getTreeListTitleAttribute()
    {
        $depth = $this->depth-1;
        $str = '';
        for ($j = 0 ; $j < $depth ; $j++) {
            $str .= '-';
        }

        return $depth == 0  ? $this->title : $str . ' '. $this->title;
    }

}