<?php

declare(strict_types=1);

namespace Maginium\Foundation\Exceptions;

use Magento\Framework\Phrase;
use Magento\Framework\Phrase\Renderer\Placeholder;
use Maginium\Foundation\Enums\HttpStatusCode;
use Maginium\Framework\Support\Facades\Container;
use Throwable;

/**
 * Class LocalizedException.
 *
 * Represents a LocalizedException error, indicating HTTP 400 Bad Request.
 */
class LocalizedException extends BaseException
{
    /**
     * The log message.
     *
     * @var string|null
     */
    protected ?string $logMessage = null;

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
        // Set the message property
        $this->message = $message;

        // Call the parent constructor with necessary parameters
        parent::__construct(
            'LocalizedError', // Type of error (usually a predefined constant like 'LocalizedError')
            $message, // The error message
            $code ?? $statusCode ?? HttpStatusCode::INTERNAL_SERVER_ERROR, // Default to Internal Server Error if not provided
            $code ?? $statusCode ?? HttpStatusCode::INTERNAL_SERVER_ERROR, // Default to Internal Server Error if not provided
            $cause, // The original exception that caused this exception (if any)
            $context, // Additional context or data related to the exception
        );
    }

    /**
     * Static factory method to create a new instance of LocalizedException.
     *
     * @param Phrase $message The error message encapsulated in a Phrase object.
     * @param Throwable|null $cause The original exception that caused this exception (optional).
     * @param int|null $statusCode The status code for the error (optional).
     * @param string|int|null $code The error code associated with the exception (optional).
     * @param string[]|null $context Additional context or data related to the exception (optional).
     *
     * @return self A new instance of the LocalizedException.
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
            $statusCode ?? HttpStatusCode::INTERNAL_SERVER_ERROR, // Default to Internal Server Error if not provided
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
            'message' => $this->message,
            'documentationLinks' => [
                'More Info' => 'https://docs.maginium.com/errors/' . $this->type, // Example link
            ],
        ];
    }

    /**
     * Get the un-processed message, without the parameters filled in.
     *
     * @return string
     */
    public function getRawMessage(): string
    {
        return $this->message;
    }

    /**
     * Get parameters, corresponding to placeholders in the raw exception message.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->message->getArguments();
    }

    /**
     * Get the un-localized message, but with the parameters filled in.
     *
     * @return string
     */
    public function getLogMessage(): string
    {
        // Check if the log message has already been generated.
        if ($this->logMessage === null) {
            /** @var Placeholder $renderer */
            $renderer = Container::create(className: Placeholder::class);

            // Render the raw message with the provided parameters, caching the result.
            $this->logMessage = $renderer->render([$this->getRawMessage()], $this->getParameters());
        }

        return $this->logMessage;
    }
}
