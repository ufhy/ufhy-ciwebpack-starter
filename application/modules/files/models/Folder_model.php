<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Folder_model extends MY_Model
{
    public $table = 'files_folders';
    public $primary_key = 'id';
    public $fillable = [
        'id','parent_id','slug','name','location','remote_container'
    ];

    public function __construct()
    {
        $this->soft_deletes = FALSE;

        parent::__construct();
    }

    public function foldersTree()
    {
        $allFolders = $this->folder_model->order_by('name')
            ->get_all();

        $folders = array();
        $folderArr = array();
        if ($allFolders) {
            foreach ($allFolders as $row) {
                $folders[$row->id] = [
                    'id'                => $row->id,
                    'parentId'          => $row->parent_id,
                    'slug'              => $row->slug,
                    'name'              => $row->name,
                    'location'          => $row->location,
                    'remoteContainer'   => $row->remote_container,
                    'createdAt'         => $row->created_at,
                    'updatedAt'         => $row->updated_at,
                ];
            }
        }

        // build a multidimensional array of parent > children
        foreach ($folders as $row) {
            if (array_key_exists($row['parentId'], $folders)) {
				// add this folder to the children array of the parent folder
				$folders[$row['parentId']]['children'][] =& $folders[$row['id']];
			}

			// this is a root folder
			if ($row['parentId'] == 0) {
				$folderArr[] =& $folders[$row['id']];
			}
        }

        return $folderArr;
    }
}