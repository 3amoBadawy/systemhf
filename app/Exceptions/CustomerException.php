<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerException extends Exception
{
    protected int $customerId;

    protected string $operation;

    public function __construct(string $message, int $customerId, string $operation, int $code = 0, ?Exception $previous = null)
    {
        $this->customerId = $customerId;
        $this->operation = $operation;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the customer ID associated with this exception
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * Get the operation that caused this exception
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Render the exception into an HTTP response
     */
    public function render(Request $request): Response|\Illuminate\Http\JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'error' => $this->getMessage(),
                'customer_id' => $this->customerId,
                'operation' => $this->operation,
            ], $this->getCode() ?: 500);
        }

        return response()->view('errors.customer', [
            'message' => $this->getMessage(),
            'customer_id' => $this->customerId,
            'operation' => $this->operation,
        ], $this->getCode() ?: 500);
    }

    /**
     * Report the exception
     */
    public function report(): void
    {
        logger()->error('Customer operation failed', [
            'message' => $this->getMessage(),
            'customer_id' => $this->customerId,
            'operation' => $this->operation,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ]);
    }
}
