<?php

namespace MorningTrain\Economic\DTOs;

class Attention
{
    public function __construct(
        public ?int $customerContactNumber = null,
    ) {
    }
}
