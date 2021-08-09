<?php

declare(strict_types=1);

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    /**
     * @return mixed
     */
    public static function getField(
        Request $request,
        string $fieldName,
        bool $isRequired = true,
        bool $isArray = false
    ) {
        // Get data from request object
        $requestData = \json_decode($request->getContent(), true);

        if ($isArray) {
            // si es array multidimensional convertimos a unidimensional
            $arrayData = self::arrayFlatten($requestData);

            foreach ($arrayData as $key => $value) {
                if ($fieldName === $key) {
                    return $value; // return field
                }
            }
            // chek if field is required
            if ($isRequired) {
                throw new BadRequestHttpException(\sprintf('Missing field %s', $fieldName));
            }

            return null;
        }

        // check if not is array
        if (\array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }

        if ($isRequired) {
            throw new BadRequestHttpException(\sprintf('Missing field %s', $fieldName));
        }

        return null;
    }

    // Function for iterate arrays with more than one level, return unidimensional array
    public static function arrayFlatten(array $array): array
    {
        $return = [];

        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                $return = \array_merge($return, self::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}
