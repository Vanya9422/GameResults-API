<?php

use App\Repository\Member\{MemberRepository, MemberRepositoryInterface};
use App\Repository\Result\{ResultRepository, ResultRepositoryInterface};

return [
    'bindings' => [ // интерфейсы и их реализации
        ResultRepositoryInterface::class => ResultRepository::class,
        MemberRepositoryInterface::class => MemberRepository::class,
    ],
];
