<?php

// Define the namespace
namespace App\Http\Controllers\Api;

// Include any required classes, interfaces etc...
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Support\Cerberus\Cerberus;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Pagination\Paginator;
use App\Support\Contracts\TransformerInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Support\Contracts\TransformableModelInterface;
use App\Support\Contracts\SimplifiedPaginatorInterface;
use InvalidArgumentException;

//

abstract class AbstractController extends Controller
{
    use DispatchesJobs;

    /**
     * The authentication guard.
     *
     * @var Cerberus
     */
    protected $shield;

    /**
     * Abstract controller constructor
     */
    public function __construct()
    {
        $this->shield = auth('api')->user();
    }

    /**
     * Returns the id of the user supplied and translates
     * self into the logged in users id
     *
     * @param integer $id
     * @return string
     */
    protected function translateUserId(int $id)
    {
        return (!$id || $id == 'self') ? $this->shield->id() : $id;
    }

    /**
     * Returns the formatted json API response.
     * The timestamp returned with the response is showing
     * the moment when the incoming request was received.
     *
     * @param array $data
     * @param integer $status
     * @return JsonResponse
     */
    protected function returnResponse(array $data, $status = 200)
    {
        $data['timestamp'] = Request::server('REQUEST_TIME_FLOAT');

        return Response::json($data, $status, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper method to respond with a boolean status
     *
     * @param bool $status - The value of the status returned
     * @param null $data - Additional data returned with the status response
     * @param integer $code
     * @return JsonResponse
     */
    protected function respondWithStatus($status = true, $data = null, $code = 200)
    {
        $response['status'] = (bool) $status;

        if ($data) {
            $response['data'] = $data;
        }

        return $this->returnResponse($response, $code);
    }

    /**
     * Helper method to respond with already formatted data
     *
     * @param $data
     * @return JsonResponse
     */
    protected function respondWithData($data)
    {
        return $this->returnResponse(['data' => $data]);
    }

    /**
     * Helper method to respond with a single item implementing the TransformableModelInterface.
     * The returned data will be transformed following the passed transformer rules.
     *
     * @param TransformableModelInterface $item
     * @param TransformerInterface $transformer
     * @param null $meta
     * @return JsonResponse
     */
    protected function respondWithItem(TransformableModelInterface $item, TransformerInterface $transformer, $meta = null)
    {
        $response = ['data' => $transformer->transform($item)];

        if ($meta) {
            $response['meta'] = $meta;
        }

        return $this->returnResponse($response);
    }

    /**
     * Helper method to respond with a collection of items implementing the TransformableModelInterface.
     * The returned data will be transformed following the passed transformer rules.
     *
     * @param Collection $items
     * @param TransformerInterface $transformer
     * @param null $meta
     * @return JsonResponse
     */
    protected function respondWithItems(Collection $items, TransformerInterface $transformer, $meta = null)
    {
        $response = [
            'data' => $transformer ? $transformer->collection($items->all()) : $items,
            'count' => count($items)
        ];

        if ($meta) {
            $response['meta'] = $meta;
        }

        return $this->returnResponse($response);
    }

    /**
     * Respond with a paginated list of items
     *
     * @param Paginator $paginator
     * @param TransformerInterface $transformer
     * @return JsonResponse
     */
    protected function respondWithPagination(Paginator $paginator, TransformerInterface $transformer)
    {
        if (!$paginator instanceof LengthAwarePaginator && !$paginator instanceof SimplifiedPaginatorInterface) {
            throw new InvalidArgumentException;
        }

        $response = [
            'data' => $transformer->collection($paginator->items()),
            'pagination' => [
                'total' => $paginator->total(),
                'count' => count($paginator->items()),
                'page' => $paginator->currentPage(),
                'continue' => $paginator->hasMorePages()
            ]
        ];

        return $this->returnResponse($response);
    }

    /**
     * Respond with a paginated list of items
     *
     * @param Paginator $paginator
     * @param TransformerInterface $transformer
     * @return JsonResponse
     */
    protected function respondWithPaginationServerTable($data, $count)
    {
        $response = [
            'data'  => $data,
            'count' => $count
        ];

        return $this->returnResponse($response);
    }

    protected function applyFiltersForServerTable($params, $data)
    {
        $all_data = $data->get();
        // $sort       = $params->get('sort');
        // $direction  = $params->get('direction');
        // $query      = $params->get('query');
        // $created_by = $params->get('created_by');
        // $type       = $params->get('type');
        $limit      = (int)$params->get('limit');
        $page       = (int)$params->get('page');
        // $created_at = $params->get('created_at');
        // if ($sort !== null && $direction !== null) {
        //     $this->orderBy($sort, $direction);
        // }
        // if ($query !== null) {
        //     $this->where('code', 'like', '%' . $query . '%');
        // }
        // if ($created_by !== null) {
        //     $this->where('created_by', 'like', '%' . $created_by . '%');
        // }
        // if ($type !== null) {
        //     $this->where('type', 'like', '%' . $type . '%');
        // }

        $data->offset($limit * ($page - 1))->limit($limit);
        $response = $data->get();
        // $response = $data->get()
		// ->sortBy(function($useritem, $key) {
		// 	return $useritem->organizeRow->procurementPlan->cpv->code;
		// });
		
        $count = count($all_data);

        return array(
            'data'  => $response,
            'count' => $count
        );
    }
}
