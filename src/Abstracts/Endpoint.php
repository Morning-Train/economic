<?php

namespace Morningtrain\Economic\Abstracts;

abstract class Endpoint
{
    public function __construct(protected string $endpoint, protected ?array $references = null)
    {
    }

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

            $endpoint = preg_replace("/$name/", $reference, $endpoint, 1);
        }

        return $endpoint;
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
