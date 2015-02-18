<?php namespace WeXchange\Model;

class VerificationObserver {

    /**
     * 保存用户验证信息时触发事件
     *
     * @param  WeXchange\Model\Verification
     * @return void
     */
    public function saved($verify)
    {
        // 邮件、证件、安全号码均通过验证？
        if ($verify->email_verified AND $verify->photo_id_verified AND $verify->addr_proof_verified AND $verify->security_verified)
        {   
            // 修改用户状态为通过验证
            $verify->user->profile->status = 'verified';
        }
        else
        {
            $verify->user->profile->status = 'unverified';            
        }

        $verify->user->profile->save();
    }
}