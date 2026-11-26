<?php

namespace Morningtrain\Economic\Enums;

/**
 * How e-conomic should send an invoice to the recipient when it books it (the "sendBy" property of POST /invoices/booked).
 *
 * The values are the ones in e-conomic's schema (invoices.booked.post.schema.json: "none", "EAN", "Email"),
 * which e-conomic validates the request body against. The examples in e-conomic's documentation write "ean"
 * in lower case instead; the schema's "EAN" is used here.
 *
 * The invoice is only sent if the booking succeeds, and leaving sendBy out means it is not sent.
 *
 * @see https://restdocs.e-conomic.com/#post-invoices-booked
 * @see https://restapi.e-conomic.com/schema/invoices.booked.post.schema.json
 */
enum SendBy: string
{
    case None = 'none';
    case Ean = 'EAN';
    case Email = 'Email';
}
