<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Paginate\Paginate;
use App\Services\Strategies\StrategyManager;
use App\Services\Transformers\Transformer;
use Exception;
use Illuminate\Support\Collection;

class ApiController extends Controller
{
    protected $transformer = null;
    protected $strategy = null;

    protected function respond($data, $statusCode = 200, $headers = [])
    {
        return response()->json($data, $statusCode, $headers);
    }

    protected function executeStrategies()
    {
        $this->checkStrategy();

        try {
            $this->strategy->execute();
        } catch (Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    protected function respondWithTransformer($data, $statusCode = 200, $headers = [])
    {
        $this->checkTransformer();

        if ($data instanceof Collection) {
            $data = $this->transformer->collection($data);
        } else {
            $data = $this->transformer->item($data);
        }

        return $this->respond($data, $statusCode, $headers);
    }

    protected function respondWithPagination($paginated, $statusCode = 200, $headers = [])
    {
        $this->checkPaginated($paginated);

        $this->checkTransformer();

        $data = $this->transformer->paginate($paginated);

        return $this->respond($data, $statusCode, $headers);
    }

    protected function respondSuccess()
    {
        return $this->respond(null);
    }

    protected function respondCreated($data)
    {
        return $this->respond($data, 201);
    }

    protected function respondNoContent()
    {
        return $this->respond(null, 204);
    }

    protected function respondError($message, $statusCode)
    {
        return $this->respond([
            'errors' => [
                'message' => $message,
                'status_code' => $statusCode
            ]
        ], $statusCode);
    }

    protected function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->respondError($message, 401);
    }

    protected function respondForbidden($message = 'Forbidden')
    {
        return $this->respondError($message, 403);
    }

    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }

    protected function respondFailedLogin()
    {
        return $this->respond([
            'errors' => [
                'email or password' => 'is invalid',
            ]
        ], 422);
    }

    protected function respondInternalError($message = 'Internal Error')
    {
        return $this->respondError($message, 500);
    }

    private function checkTransformer()
    {
        if ($this->transformer === null || ! $this->transformer instanceof Transformer) {
            throw new Exception('Invalid data transformer');
        }
    }

    private function checkPaginated($paginated)
    {
        if (! $paginated instanceof Paginate) {
            throw new Exception('Expected instance of Paginate');
        }
    }

    private function checkStrategy()
    {
        if (! $this->strategy === null || ! $this->strategy instanceof StrategyManager) {
            throw new Exception('Expected instance of RepaymentStrategy');
        }
    }
}
