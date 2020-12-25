<?php declare(strict_types = 1);

namespace jotaa\franky_web\interfaces\model_interfaces;

interface ReservaStatusInterface
{
    public const RESERVED = 10;
    public const RESERVED_AND_PAID_HALF = 11;
    public const RESERVED_AND_PAID = 12;
    public const COMPLETED = 13;
    public const PENDING = 30;
    public const PENDING_BY_REGISTER = 31;
    public const PROCESSING = 32;
    public const PENDING_PAYMENT = 33;
    public const PENDING_BY_LOGIN = 34;
    public const PENDING_CONFIRMATION = 35;
    public const DECLINED = 40;
}
