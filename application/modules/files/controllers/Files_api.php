<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Files_api
 *
 * @property File_model $file_model
 * @property Folder_model $folder_model
 */
class Files_api extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('files/the_file');
        $this->lang->load('files/files');
    }

    public function index()
    {
        $folderId = $this->input->get('folder');
        if (!$folderId) {
            $this->output->set_status_header('400', lang('msg::request_failed'));
            $this->template->build_json([
                'success' => false,
                'message' => lang('msg::request_failed')
            ]);
            return false;
        }

        if (!is_numeric($folderId)) {
            $findFolder = $this->folder_model->get(['slug' => $folderId]);
        } else {
            $findFolder = $this->folder_model->get(['id' => $folderId]);
        }

        $folders = $this->folder_model->as_array()->get_all(['parent_id' => $findFolder->id]);
        $files = $this->file_model->as_array()->get_all(['folder_id' => $findFolder->id]);

        // let's be nice and add a date in that's formatted like the rest of the CMS
        if ($folders) {
            foreach ($folders as &$folder) {
                $folder['file_count'] = $this->file_model->count_rows(['folder_id' => $findFolder->id]);
            }
        }

        $this->template->build_json([
            'success'   => true,
            'data'      => [
                'id'                => $findFolder->id,
                'parentId'          => $findFolder->parent_id,
                'slug'              => $findFolder->slug,
                'name'              => $findFolder->name,
                'location'          => $findFolder->location,
                'remoteContainer'   => $findFolder->remote_container,
                'createdAt'         => $findFolder->created_at,
                'updatedAt'         => $findFolder->updated_at,
                'folders'           => $folders ? $folders : [],
                'files'             => $files ? $files : [],
            ]
        ]);
    }

    public function folders()
    {
        $this->template->build_json([
            'folders' => The_file::folderTree()
        ]);
    }
}