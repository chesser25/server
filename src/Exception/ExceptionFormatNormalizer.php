<?php
namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ExceptionFormatNormalizer
{
    /**
     * @param ConstraintViolationListInterface $validationErrors
     */
    static function normalize(ConstraintViolationListInterface $validationErrors) : array
    {
        $messages = [];
        foreach ($validationErrors as $error) {
            $messages[] = array(
                'property' => $error->getPropertyPath(),
                'messages' => $error->getMessage()
            );
        }
        return $messages;
    }
}