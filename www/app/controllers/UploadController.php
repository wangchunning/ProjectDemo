<?php namespace Controllers;

use App;
use Input;
use Request;
use Administrator;

/**
 *  文件上传控制器，用于前后台文件上传功能
 *  其中包括管理员帮助用户上传文件，因此这里要求管理员登录或者用户登录
 *
 */
Class UploadController extends BaseController {
	
	/**
	 * 仅针对异步上传 (ajax)
	 * 仅针对登录用户可用，可能是用户本身或者管理员帮其操作
	 */
	public function __construct()
	{
		parent::__construct();
		
		// ajax请求
		if (!Request:: ajax() ||
				(!is_login() && !is_login(Administrator::LABEL)))
		{
			App::abort(404);
		}
		
	}
	
    /**
     * 上传图片
     * 
     * 这里采用异步上传，主要考虑批量上传及图片预览等情况
     * 
     * 为避免浪费磁盘空间及后期维护，不再使用的图片不应存在，具体方案取决于业务逻辑
     * 如，头像对于用户唯一，则可以以 user id 作为文件名，如果用户多次上传，旧文件即被覆盖
     * 
     */
    public function postIndex()
    {   
        // 上传失败
        if (!Input::hasFile('file') ||
        		!Input::file('file')->isValid())
        {
        	return Ret::json(Ret::RET_FAILED, array(), 
        						Ret::ERR_FILE_UPLOAD, '文件上传失败');
        }
        
        // 上传目录
        $_path = 'uploads';
        if(!is_dir($_path))
        {
            @mkdir($_path, 0777, TRUE);
        }

        $_file = Input::file('file');
        
        // 重命名文件
        $_file_name = str_random(12) . '.' . $_file->getClientOriginalExtension();
        $_file->move($_path, $_file_name);

		/**
		 * make result
		 */
        $_ret_data = array();
        $_ret_data['file_path'] 	= sprintf('%s/%s', $_path, $_file_name);
        $_ret_data['original_name'] = $_file->getClientOriginalName();
        
        return Ret::json(Ret::RET_SUCC, $_ret_data);

    }
    
}