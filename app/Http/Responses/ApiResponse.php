<?php
declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class ApiResponse implements Responsable
{
    public function __construct(
        private mixed $data = null,
        private ?string $message = null,
        private int $status = 200,
    ) {}

    public function toResponse($request): JsonResponse|Response
    {
        $payload = [
            'status'  => $this->status < 400 ? 'success' : 'error',
            'message' => $this->message,
            'data'    => $this->data,
        ];

        return response()->json($payload, $this->status);
    }
}
