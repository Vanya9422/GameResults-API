<?php

namespace App\Repository\Member;

use App\Models\Member;
use App\Repository\BaseRepository;

class MemberRepository extends BaseRepository implements MemberRepositoryInterface {

    /**
     * @return string
     */
    protected function getModelClass(): string {
        return Member::class;
    }

    /**
     * Находит участника по email или создает нового.
     *
     * @param string $email Email участника.
     * @return Member Найденный или созданный экземпляр модели Member.
     */
    public function findByEmailOrCreate(string $email): Member {
      return $this->getModel()->firstOrCreate(['email' => $email]);
    }
}
