<?php

namespace App\Repository\Member;

use App\Models\Member;

interface MemberRepositoryInterface {

    /**
     * @param string $email
     * @return Member
     */
    public function findByEmailOrCreate(string $email): Member;
}
