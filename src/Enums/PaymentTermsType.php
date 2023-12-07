<?php

namespace MorningTrain\Economic\Enums;

enum PaymentTermsType: string
{
    case NET = 'net';
    case INVOICE_MONTH = 'invoiceMonth';
    case PAID_IN_CASH = 'paidInCash';
    case PREPAID = 'prepaid';
    case DUE_DATE = 'dueDate';
    case FACTORING = 'factoring';
    case INVOICE_WEEK_STARTING_SUNDAY = 'invoiceWeekStartingSunday';
    case INVOICE_WEEK_STARTING_MONDAY = 'invoiceWeekStartingMonday';
    case CREDIT_CARD = 'creditcard';
}
