<?php

declare(strict_types=1);

namespace Maginium\Foundation\Exceptions;

use Magento\Framework\Phrase;
use Maginium\Foundation\Enums\HttpStatusCode;
use Throwable;

/**
 * Class TimeoutException.
 *
 * Represents a TimeoutException error, indicating a request has timed out.
 */
class TimeoutException extends LocalizedException
{
    /**
     * The error type for timeout errors.
     *
     * @var string
     */
    protected string $type = 'TimeoutError';

    /**
     * Private constructor to prevent direct instantiation.
     *
     * @param Phrase $message The error message encapsulated in a Phrase object.
     * @param Throwable|null $cause The original exception that caused this exception (optional).
     * @param int|null $statusCode The status code for the error (optional).
     * @param string|int|null $code The error code associated with the exception (optional).
     * @param string[]|null $context Additional context or data related to the exception (optional).
     */
    public function __construct(
        Phrase $message,
        ?Throwable $cause = null,
        ?int $statusCode = null,
        string|int|null $code = null,
        ?array $context = null,
    ) {
        // Call the parent constructor with necessary parameters
        parent::__construct(
            $message, // The error message
            $cause,   // The cause of the error
            $statusCode ?? HttpStatusCode::REQUEST_TIMEOUT, // Default to 408 if no statusCode provided
            $code,    // The error code
            $context,  // Additional context
        );
    }

    /**
     * Static factory method to create a new instance of TimeoutException.
     *
     * @param Phrase $message The error message encapsulated in a Phrase object.
     * @param Throwable|null $cause The original exception that caused this exception (optional).
     * @param int|null $statusCode The status code for the error (optional).
     * @param string|int|null $code The error code associated with the exception (optional).
     * @param string[]|null $context Additional context or data related to the exception (optional).
     *
     * @return self A new instance of the TimeoutException.
     */
    public static function make(
        Phrase $message,
        ?Throwable $cause = null,
        ?int $statusCode = null,
        string|int|null $code = null,
        ?array $context = null,
    ): self {
        return new self(
            $message,
            $cause,
            $statusCode ?? HttpStatusCode::REQUEST_TIMEOUT, // Default to 408 if no statusCode provided
            $code,
            $context,
        );
    }

    /**
     * Creates a new array representing a solution.
     *
     * @return array An associative array containing the solution details.
     */
    public function solution(): array
    {
        return [
            'title' => $this->type,
            'description' => $this->message,
            'links' => [
                'More Info' => 'https://docs.maginium.com/errors/' . $this->type, // Example link
            ],
        ];
    }
}
