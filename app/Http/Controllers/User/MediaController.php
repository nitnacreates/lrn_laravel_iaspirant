<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function __construct(Request $request)
    {
        view()->share('page', 'user-media');
        view()->share('pagetitle', 'User Media');
        $this->request = $request;
    }

    public function show()
    {
        $user_id = \UserHelper::getUserId();
        $view_arr['files_arr'] = $this->getMediaFiles();

        return view('user.media.show')->with($view_arr);
    }

    public function picker()
    {
        $view_arr['files_arr'] = $this->getMediaFiles();
        return view('user.media.picker')->with($view_arr);
    }

    public function store()
    {
        $params = $this->request->all();
        $msg = 'There was an error';
        if ($this->request->isMethod('post')) {

            $site_url = $this->getUserMediaUrl();
            $this->validate($this->request, [
                'current-dir' => 'required|starts_with:'.$site_url,
                'create-folder' => 'nullable|max:50|regex:/^[a-z0-9\-_]+$/',
                'create-image' => 'nullable|image|max:5000',
            ],[
                'create-folder.regex' => 'Only small alpha, digits, hyphen and underscore allowed',
                'current-dir.starts_with' => 'Invalid directory provided',
                'create-image.max' => 'Image should be smaller than 5 mb'
            ]);

            $params = $this->request->all();
            
            $dir = $this->toAbsolutePath($params['current-dir']);
            $abs_usr_path = $this->getUserAbsPath();

            if(stripos($dir, $abs_usr_path) === 0){ // reconfirm paths
                if(File::isDirectory($dir)){
                    if(!empty($params['create-folder'])){
                        $new_dir = $dir . DIRECTORY_SEPARATOR . $params['create-folder'];
                        if(!File::isDirectory($new_dir)){
                            File::makeDirectory($new_dir);
                            $msg = 'Direcctory successfully created';
                        } else{
                            $msg = 'Direcctory already exists';
                        }
                    }

                    if(!empty($params['create-image'])){
                        $orig_name = $this->request->file('create-image')->getClientOriginalName();
                        $filename = File::name($orig_name);
                        $extname = File::extension($orig_name);

                        $filename = strtolower($filename);
                        $filename = preg_replace('/[\s]+/','-',$filename);
                        $filename = preg_replace('/[^a-z0-9\-_]+/','',$filename);

                        $abs_base_path = $this->toBaseAbsolutePath($dir);
                        $new_name = $filename . '.'. $extname;
                        if(!File::exists($dir . $new_name)){
                            Storage::disk('aspirant')->putFileAs($abs_base_path, $this->request->file('create-image'), $new_name);
                            $msg = 'File saved as ' . $new_name;
                        } else{
                            $msg = 'File already exists';
                        }
                    }  
                } else{
                    $msg = 'Directory does not exist';
                }
            } else{
                $msg = 'Invalid path';
            }
        }

        $flash_msgs[] = $msg;
        $this->request->session()->flash('msgs', $flash_msgs);
        return redirect()->route('user::media', ['action' => 'show']);
    }

    public function summon()
    {
        if($this->request->ajax()){
            $type = $this->request->input('type');
            
            $data = ['item' => $this->request->input('item')];
            $site_url = $this->getUserMediaUrl();
            $validator = Validator::make($data, [
                'item' => 'required|starts_with:'.$site_url,
            ],[
                'item.starts_with' => 'Invalid item'
            ]);
            if($validator->fails()){
                return response()->json(['message' => 'An error occurred'], 404);
            }

            switch ($type) {
                case 'delete-folder':
                    $folder = $this->request->input('item');
                    $abs_fld = $this->toAbsolutePath($folder);
                    $abs_pub_path = $this->getUserAbsPath();
                    
                    // recheck for path
                    if(stripos($abs_fld, $abs_pub_path) === 0){
                        if(File::isDirectory($abs_fld)){
                            File::deleteDirectory($abs_fld);
                            return response()->json(['message' => 1]);
                        }
                    }
                    return response()->json(['message' => 'An error occurred'], 404);
                    break;
                case 'delete-image':
                    $img = $this->request->input('item');
                    $abs_img = $this->toAbsolutePath($img);
                    $abs_img = rtrim($abs_img, '/\/');
                    $abs_pub_path = $this->getUserAbsPath();
                    
                    // recheck for path
                    if(stripos($abs_img, $abs_pub_path) === 0){
                        if(File::isFile($abs_img)){
                            File::delete($abs_img);
                            return response()->json(['message' => 1]);
                        }
                    }
                    return response()->json(['message' => 'An error occurred'], 404);
                    break;
                default:
                    # code...
                    break;
            }
        }
        return response('Invalid request', 404)->header('Content-Type', 'text/plain');
    }

    /* ******* protected ******* */
    protected function getUserAbsPath()
    {
        $media_dir = Storage::disk('aspirant')->path('') . \UserHelper::getUserId() . DIRECTORY_SEPARATOR;

        if(!File::isDirectory($media_dir)){
            File::makeDirectory($media_dir);
        }

        if(!File::isWritable($media_dir)){
            File::chmod($media_dir, 0755);
        }

        return $media_dir;
    }

    protected function getUserMediaUrl()
    {
        return Storage::disk('aspirant')->url('') . \UserHelper::getUserId() . '/';
    }

    protected function toBaseAbsolutePath($abs_path)
    {
        $abs_pub_path = Storage::disk('aspirant')->path('');
        return str_ireplace($abs_pub_path, '', $abs_path);
    }

    protected function toAbsolutePath($rel_path)
    {
        $rel_path = rtrim($rel_path, '/\/');
        $rel_path .= DIRECTORY_SEPARATOR;

        $abs_pub_path = $this->getUserAbsPath();
        $site_url = $this->getUserMediaUrl();
        return str_ireplace($site_url, $abs_pub_path, $rel_path);
    }

    protected function toRelativePath($abs_path)
    {
        $abs_pub_path = $this->getUserAbsPath();
        $site_url = $this->getUserMediaUrl();
        return str_ireplace($abs_pub_path, $site_url, $abs_path);
    }

    protected function getMediaFiles()
    {
        $media_dir = $this->getUserAbsPath();

        $startpath = realpath($media_dir);
        $reiterate = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($startpath, \FilesystemIterator::SKIP_DOTS|\FilesystemIterator::UNIX_PATHS), \RecursiveIteratorIterator::CHILD_FIRST);
        $files_arr = array();
        foreach ($reiterate as $splFileInfo) {
            $name = $splFileInfo->getFilename();
            $full_path = $splFileInfo->getPathname();
            $path = $splFileInfo->isDir() ? array($name => array('folder_path' => $this->toRelativePath($full_path))) : array($name => $this->toRelativePath($full_path));

           for ($depth = $reiterate->getDepth() - 1; $depth >= 0; $depth--) {
                $subname = $reiterate->getSubIterator($depth)->current()->getFilename();
                $path = array($subname => $path);
           }
           $files_arr = array_merge_recursive($files_arr, $path);
        }
        $files_arr['folder_path'] = $this->getUserMediaUrl();

        return $files_arr;
    }
}
