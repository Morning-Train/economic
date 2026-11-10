<?php

use Morningtrain\Economic\Abstracts\Endpoint;
use Morningtrain\Economic\Attributes\Resources\Delete;
use Morningtrain\Economic\Attributes\Resources\GetSingle;

it('encodes references with the e-conomic resource url scheme', function (string $reference, string $expected) {
    expect(Endpoint::encodeReference($reference))->toBe($expected);
})->with([
    'plain number' => ['422', '422'],
    'underscore' => ['CK_velkomst', 'CK_8_velkomst'],
    'whitespace' => ['Limited - 22maj', 'Limited_9_-_9_22maj'],
    'question mark' => ['GD-dev?ice', 'GD-dev_10_ice'],
    'documented example' => ['My Awesome Product_Discount5%', 'My_9_Awesome_9_Product_8_Discount5_3_'],
    'non-ascii' => ['1-CAcabblå', '1-CAcabbl%C3%A5'],
    'slash' => ['a/b', 'a_6_b'],
    'dot' => ['.', '_11_'],
    'dot dot' => ['..', '_11__11_'],
]);

it('inserts references literally instead of as regex backreferences', function (string $reference, string $expected) {
    expect((new Delete('invoices/drafts/:draftInvoiceNumber'))->getEndpoint($reference))
        ->toBe($expected)
        ->not->toBe('invoices/drafts/');
})->with([
    '$1' => ['$1', 'invoices/drafts/%241'],
    '$0' => ['$0', 'invoices/drafts/%240'],
    '${1}' => ['${1}', 'invoices/drafts/%24%7B1%7D'],
    '\1' => ['\1', 'invoices/drafts/_7_1'],
    '\0' => ['\0', 'invoices/drafts/_7_0'],
]);

it('does not replace a placeholder that is the prefix of another placeholder', function () {
    $endpoint = new GetSingle('accounting-years/:accountingYear/periods/:accountingYearPeriod');

    expect($endpoint->getEndpoint(['accountingYearPeriod' => 3, 'accountingYear' => 2024]))
        ->toBe('accounting-years/2024/periods/3');
});
