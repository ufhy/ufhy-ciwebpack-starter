<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends MY_Model
{
    public $table = 'user_profile';
    public $primary_key = 'user_id';
    public $fillable = [
        'user_id','full_name','phone','photo_file','nik','position'
    ];

    public function __construct()
    {
        $this->timestamps = FALSE;
        $this->soft_deletes = FALSE;

        parent::__construct();
    }
}