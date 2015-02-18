<?php namespace Controllers\Admin;

use Input;
use Controllers\AdminController;
/**
 *  上传控制器
 *
 *  @author     Kshan <kshan@qq.com>
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
Class UploadController extends AdminController {

    /**
     * 上传图片
     *
     * @return void
     */
    public function postIndex()
    {   
        // 上传目录
        $uploadPath = 'uploads/admin';

        // 指定目录不存在?
        if( ! is_dir($uploadPath))
        {
            @mkdir($uploadPath, 0777, TRUE);
        }

        $file = Input::file('file');

        // 上传失败？
        if ( ! Input::hasFile('file'))
        {
            return $this->push('error', array('msg' => '文件上传失败'));
        }

        // 重命名文件
        $fileName = str_random(12) . '.' . $file->getClientOriginalExtension();

        Input::file('file')->move($uploadPath, $fileName);

        // 文件路径
        $filePath = sprintf('%s/%s', $uploadPath, $fileName);

        return $this->push('ok', array('filePath' => $filePath, 'original_name' => $file->getClientOriginalName()));
    }
}