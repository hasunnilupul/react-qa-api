<?php


namespace App\Http\Library;


use App\Models\Question;
use Exception;
use Illuminate\Http\Response;

trait ApiHelpers
{
    /**
     * @param $user
     * @return bool
     */
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan("Administrator");
        }
        return false;
    }

    /**
     * @param $user
     * @return bool
     */
    protected function isUser($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan("User");
        }
        return false;
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return Response
     */
    protected function onSuccess($data, string $message = '', int $code = 200): Response
    {
        return response([
            'status' => $code,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param int $code
     * @param string $message
     * @return Response
     */
    protected function onError(int $code, string $message = ''): Response
    {
        return response([
            'status' => $code,
            'message' => $message,
        ], $code);
    }


    /**
     * Generate unique id for question
     *
     * @return int
     * @throws Exception
     */
    protected function generateUId(): int
    {
        $uid = random_int(1000000000, 9999999999);
        if (Question::whereUnique($uid)->exists()) {
            return $this->generateUId();
        }
        return $uid;
    }
}
