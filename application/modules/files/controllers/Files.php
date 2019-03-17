<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Class Files
 *
 * @property File_model $file_model
 */
class Files extends MY_Controller
{
    private	$_path = '';

    public function __construct()
    {
        parent::__construct();

        $this->load->library('files/the_file');

        $this->_path = $this->config->item('file_path', 'the_file') . DIRECTORY_SEPARATOR;
    }

    public  function index() {}

    public function thumb($id = 0, $width = 150, $height = 150, $mode = null)
    {
        $file = new stdClass();
        if ((strlen($id) === 15 and strpos($id, '.') === false) or (is_numeric($id) and strpos($id, '.') === false))
        {
            $file = $this->file_model->get(['id' => $id]);
        }
        else {
            $data = getimagesize($this->_path.$id) OR show_404();
            $ext = '.'.end(explode('.', $id));
            $file->width 		= $data[0];
            $file->height 		= $data[1];
            $file->filename 	= $id;
            $file->extension 	= $ext;
            $file->mimetype 	= $data['mime'];
        }

        if ( ! $file) {
            set_status_header(404);
            exit;
        }

        $cache_dir = $this->config->item('cache_dir') . 'image_files/';
        is_dir($cache_dir) or mkdir($cache_dir, 0777, true);
        
        $modes = array('fill', 'fit');
        $args = func_num_args();
        $args = $args > 3 ? 3 : $args;
        $args = $args === 3 && in_array($height, $modes) ? 2 : $args;

        switch ($args) {
            case 2:
                if (in_array($width, $modes)) {
                    $mode	= $width;
                    $width	= $height; // 100

                    continue;
                }
                else if (in_array($height, $modes)) {
                    $mode	= $height;
                    $height	= empty($width) ? null : $width;
                }
                else {
                    $height	= null;
                }

                if ( ! empty($width)) {
                    if (($pos = strpos($width, 'x')) !== false) {
                        if ($pos === 0) {
                            $height = substr($width, 1);
                            $width	= null;
                        }
                        else {
                            list($width, $height) = explode('x', $width);
                        }
                    }
                }
            case 3:
                if (in_array($height, $modes)) {
                    $mode	= $height;
                    $height	= empty($width) ? null : $width;
                }

                foreach (array('height' => 'width', 'width' => 'height') as $var1 => $var2)
                {
                    if (${$var1} === 0 or ${$var1} === '0') {
                        ${$var1} = null;
                    }
                    elseif (empty(${$var1}) or ${$var1} === 'auto') {
                        ${$var1} = (empty(${$var2}) OR ${$var2} === 'auto' OR ! is_null($mode)) ? null : 100000;
                    }
                }
                break;
        }

        // Path to image thumbnail
        $thumb_filename = $cache_dir . ($mode ? $mode : 'normal');
        $thumb_filename .= '_' . ($width === null ? 'a' : ($width > $file->width ? 'b' : $width));
        $thumb_filename .= '_' . ($height === null ? 'a' : ($height > $file->height ? 'b' : $height));
        $thumb_filename .= '_' . md5($file->filename) . $file->extension;

        $expire = 60 * Setting::get('files_cache', 'files');
        if ($expire) {
            header("Pragma: public");
            header("Cache-Control: public");
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expire) . ' GMT');
        }

        $source_modified = filemtime($this->_path . $file->filename);
        $thumb_modified = filemtime($thumb_filename);

        if ( ! file_exists($thumb_filename) OR ($thumb_modified < $source_modified)) {
            if ($mode === $modes[1]) {
                $crop_width		= $width;
                $crop_height	= $height;
                $ratio		    = $file->width / $file->height;
                $crop_ratio	    = (empty($crop_height) OR empty($crop_width)) ? 0 : $crop_width / $crop_height;
                
                if ($ratio >= $crop_ratio and $crop_height > 0) {
                    $width	= $ratio * $crop_height;
                    $height	= $crop_height;
                } else {
                    $width	= $crop_width;
                    $height	= $crop_width / $ratio;
                }

                $width	= ceil($width);
                $height	= ceil($height);
            }

            if ($height or $width) {
                $this->load->library('image_lib');

                $config['image_library']    = 'gd2';
                $config['source_image']     = $this->_path.$file->filename;
                $config['new_image']        = $thumb_filename;
                $config['maintain_ratio']   = is_null($mode);
                $config['height']           = $height;
                $config['width']            = $width;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();

                if ($mode === $modes[1] && ($crop_width !== null && $crop_height !== null)) {
                    $x_axis = floor(($width - $crop_width) / 2);
                    $y_axis = floor(($height - $crop_height) / 2);

                    $config['image_library']    = 'gd2';
                    $config['source_image']     = $thumb_filename;
                    $config['new_image']        = $thumb_filename;
                    $config['maintain_ratio']   = false;
                    $config['width']			= $crop_width;
                    $config['height']			= $crop_height;
                    $config['x_axis']			= $x_axis;
                    $config['y_axis']			= $y_axis;
                    $this->image_lib->initialize($config);
                    $this->image_lib->crop();
                    $this->image_lib->clear();
                }
            }
            else {
                $thumb_modified = $source_modified;
                $thumb_filename = $this->_path.$file->filename;
            }
        }
        else if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $thumb_modified) && $expire ) {
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', $thumb_modified).' GMT', true, 304);
            exit;
        }

        header('Content-type: ' . $file->mimetype);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($thumb_filename)) . ' GMT');
        ob_end_clean();

        readfile($thumb_filename);
    }

    public function large($id)
    {
        return $this->thumb($id, null, null);
    }

    public function download($id = 0)
    {
        $this->load->helper('download');
        $file = $this->file_model->get(array('id' => $id));

        if ($file === FALSE) {
            $this->template->show_404('FILE_ID_NOT_FOUND');
            return false;
        }

        // increment the counter
        $this->file_model->update(array('download_count' => $file->download_count + 1), array('id' => $id));
        
        // Read the file's contents
        $data = file_get_contents($this->_path . $file->filename);

        // if it's the default name it will contain the extension. Otherwise we need to add the extension
        $name = (strpos($file->name, $file->extension) !== false ? $file->name : $file->name . $file->extension);

        force_download($name , $data);
    }
}