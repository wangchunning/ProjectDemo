<?php namespace Controllers\Admin;

use Input;
use Receipt;
use Transaction AS Trans;
use Auth;
use Notes;
use Controllers\AdminController;
use Activity;

/**
 *  Note 控制器
 *
 *  @copyright  2013 ZOYU Solution Pty. Ltd.
 */
class NoteController extends AdminController {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 添加
     *
     * @return Response
     */
    public function postAdd()
    {
        $item_id = Input::get('item_id');
        $type = Input::get('type');
        $desc = Input::get('desc');
        $uid = Input::get('uid');

        switch ($type)
        {
            case 'transaction':
                // 检查权限
                $perm = check_perms('view_note,add_note', 'AND', FALSE);
                $item = Trans::find($item_id);
                // 检查编辑与删除权限
                $edit_perm = check_perms('view_note,edit_note', 'AND', FALSE);
                $remove_perm = check_perms('view_note,remove_note', 'AND', FALSE);
                break;

            case 'receipt':
                // 检查权限
                $perm = check_perms('view_note,add_note', 'AND', FALSE);
                $item = Receipt::find($item_id);
                // 检查编辑与删除权限
                $edit_perm = check_perms('view_note,edit_note', 'AND', FALSE);
                $remove_perm = check_perms('view_note,remove_note', 'AND', FALSE);
                break;

            default:
                // 检查权限
                $perm = check_perms('view_customer_note,add_customer_note', 'AND', FALSE);
                // 检查编辑与删除权限
                $edit_perm = check_perms('view_customer_note,edit_customer_note', 'AND', FALSE);
                $remove_perm = check_perms('view_customer_note,remove_customer_note', 'AND', FALSE);
                break;
        }
        if ( ! $perm) 
        {
            return $this->push('error', array('msg' => "You don't have the permissions!"));
        }

        // 检查提交的 transaction、receipt id 是否有效
        if (in_array($type, array('transaction', 'receipt')) AND ! $item)
        {
            $this->push('error', array('msg' => "The {$type} is not exists!"));            
        }

        $note = Notes::create(array(
            'operator_id' => Auth::admin()->user()->uid,
            'uid'         => $uid,
            'item_id'     => $item_id,
            'type'        => $type,
            'desc'        => Input::get('desc')
        ));

        // add log
        Activity::log(array(
            'obj_id'        => $type == 'overview' ? $uid : $item_id,
            'customer_id'   => $type == 'overview' ? $uid : $item->uid,
            'activity_type' => $type == 'overview' ? 'Customers' : 'Transactions',
            'action'        => 'Add Notes',
            'description'   => $type == 'overview' ? sprintf('Notes：%s', $desc) : sprintf('%s Notes: %s', $type == 'transaction' ? 'Transaction ' . transaction_detail_url($item_id) : 'Receipt ' . receipt_detail_url($item_id), $desc)
        ));

        $output = '<p><span class="f16p">' . Auth::admin()->user()->full_name . '</span>';
        
        if ($edit_perm)
        {
            $output .= '<a class="underline desc-edit" data-id="' . $note->id . '" href="javascript:;">edit</a>';
        }

        if ($remove_perm)
        {
            $output .= '<a class="underline desc-delete" data-id="' . $note->id . '" href="javascript:;">delete</a>';
        }
        $output .= '<span class="pull-right">just now</span></p>';

        return $this->push('ok', array(
            'output'     => $output, 
            'desc'       => $desc
            ));
    }

    /**
     * 修改
     *
     * @return Response
     */
    public function postEdit()
    {
        if ( ! $note = Notes::find(Input::get('note_id')))
        {
            return $this->push('error', array('msg' => 'This note is not exists!'));
        }

        // 检查权限
        switch ($note->type) {
            case 'overview':
                $perm = check_perms('view_customer_note,edit_customer_note', 'AND', FALSE);
                break;
            
            default:
                $perm = check_perms('view_note,edit_note', 'AND', FALSE);
                break;
        }
        if ( ! $perm) 
        {
            return $this->push('error', array('msg' => "You don't have the permissions!"));
        }

        $note->update(array('desc' => Input::get('desc')));

        // add log
        Activity::log(array(
            'obj_id'        => $note->item_id,
            'customer_id'   => $note->uid,
            'activity_type' => 'Transactions',
            'action'        => 'Edit Notes',
            'description'   => sprintf('%s Notes: %s', $note->type == 'transaction' ? 'Transaction ' . transaction_detail_url($note->item_id) : 'Receipt ' . receipt_detail_url($note->item_id), Input::get('desc'))
        ));

        return $this->push('ok');        
    }

    /**
     * 删除
     *
     * @return Response
     */
    public function postRemove()
    {
        if ( ! $note = Notes::find(Input::get('note_id')))
        {
            return $this->push('error', array('msg' => 'This note is not exists!'));
        }

        // 检查权限
        switch ($note->type) {
            case 'overview':
                $perm = check_perms('view_customer_note,remove_customer_note', 'AND', FALSE);
                break;
            
            default:
                $perm = check_perms('view_note,remove_note', 'AND', FALSE);
                break;
        }
        if ( ! $perm) 
        {
            return $this->push('error', array('msg' => "You don't have the permissions!"));
        }

        // add log
        Activity::log(array(
            'obj_id'        => $note->item_id,
            'customer_id'   => $note->uid,
            'activity_type' => 'Transactions',
            'action'        => 'Deleted Notes',
            'description'   => sprintf('%s Notes: %s', $note->type == 'transaction' ? 'Transaction ' . transaction_detail_url($note->item_id) : 'Receipt ' . receipt_detail_url($note->item_id), $note->desc)
        ));

        $note->delete(Input::get('note_id'));

        return $this->push('ok');        
    }
}