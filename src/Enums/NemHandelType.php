<?php

namespace Morningtrain\Economic\Enums;

enum NemHandelType: string
{
    case EAN = 'ean';
    case CORPORATE_IDENTIFICATION_NUMBER = 'corporateIdentificationNumber';
    case P_NUMBER = 'pNumber';
}
