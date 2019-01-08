<?php

namespace Phpactor\Completion\Core;

/**
 * Represents the signature of something callable. A signature
 * can have a label, like a function-name, a doc-comment, and
 * a set of parameters.
 */
class SignatureInformation
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var ParameterInformation[]
     */
    private $parameters;

    /**
     * @var string|null
     */
    private $documentation;

    private function __construct(string $label, array $parameters, string $documentation = null)
    {
        $this->label = $label;
        $this->documentation = $documentation;

        foreach ($parameters as $parameter) {
            $this->add($parameter);
        }
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function documentation(): ?string
    {
        return $this->documentation;
    }

    public function label(): string
    {
        return $this->label;
    }

    private function add(ParameterInformation $parameter)
    {
        $this->parameters[] = $parameter;
    }
}
