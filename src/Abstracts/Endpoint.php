<?php

namespace Morningtrain\Economic\Abstracts;

abstract class Endpoint
{
    public function __construct(protected string $endpoint, protected ?array $references = null) {}

    public function getEndpoint(...$references): string
    {
        $endpoint = $this->endpoint;

        // Find all references in endpoint
        preg_match_all('/\:[a-zA-Z]+/', $endpoint, $matches);

        if (! empty($references) && is_array(array_values($references)[0])) {
            $references = array_values($references)[0];
        }

        $matches = $matches[0]; // Get first match group

        foreach ($references as $name => $reference) {
            if (is_int($name)) {
                if (isset($matches[$name])) {
                    $name = $matches[$name];
                } else {
                    throw new \Exception('Reference not found');
                }
            }

            $name = str_starts_with($name, ':') ? $name : ':'.$name;

            if (! in_array($name, $matches)) {
                throw new \Exception('Reference not found');
            }

            // Use a callback so the reference is inserted literally ("$1" or "\1" are not backreferences),
            // and a lookahead so ":accountingYear" does not match the start of ":accountingYearPeriod".
            $endpoint = preg_replace_callback(
                '/'.preg_quote($name, '/').'(?![a-zA-Z])/',
                fn () => static::encodeReference($reference),
                $endpoint,
                1
            );
        }

        return $endpoint;
    }

    /**
     * Encode a reference as a single path segment using e-conomic's resource URL scheme:
     * a subset of characters is replaced by _N_ and everything else is URL encoded.
     * This also means a reference can never add path segments or resolve to "." or "..".
     *
     * @see https://restdocs.e-conomic.com/#custom-resource-self-link-encoding
     */
    public static function encodeReference(mixed $reference): string
    {
        return rawurlencode(strtr((string) $reference, [
            '<' => '_0_',
            '>' => '_1_',
            '*' => '_2_',
            '%' => '_3_',
            ':' => '_4_',
            '&' => '_5_',
            '/' => '_6_',
            '\\' => '_7_',
            '_' => '_8_',
            ' ' => '_9_',
            '?' => '_10_',
            '.' => '_11_',
            '#' => '_12_',
            '+' => '_13_',
        ]));
    }

    public function getEndpointReferences(): array
    {
        return $this->references;
    }

    /**
     * Check if endpoint is the same as the given slug
     */
    public function is(string $slug): bool
    {
        if (! isset($this->slug)) {
            return false;
        }

        return $this->slug === $slug;
    }
}
