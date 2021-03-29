<?php
declare(strict_types=1);

namespace OniBus\Handler;

use Closure;
use OniBus\Exception\UnresolvableMenssageExcpetion;
use OniBus\Message;

class ResolverComposite implements HandlerResolver
{
    /**
     * @var array
     */
    protected $resolvers;

    public function __construct(HandlerResolver ...$resolvers)
    {
        $this->resolvers = $resolvers;
    }

    public function resolve(Message $message): Closure
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->canResolve($message)) {
                return $resolver->resolve($message);
            }
        }

        throw new UnresolvableMenssageExcpetion($message);
    }

    public function canResolve(Message $message): bool
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->canResolve($message)) {
                return true;
            }
        }

        return false;
    }
}
