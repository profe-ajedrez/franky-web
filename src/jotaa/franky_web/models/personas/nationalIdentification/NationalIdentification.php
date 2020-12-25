<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\nationalIdentification;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * NationalIdentification
 *
 * @author Javier Campos
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class NationalIdentification extends FrankyRecord implements FrankyModelInterface
{

    public const RUT = 'RUT';

    public const PASAPORTE = 'Pasaporte';

    public static function fromArray(array $dataArray): array
    {
        $v = new \Valitron\Validator();
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'document_name')
          ->rule('required', 'document_value')
          ->rule('required', 'personas_id');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors' => $v->errors(),
                'nationalIdentification' => null
            ];
        }

        list($id, $document_name, $document_value, $personas_id) = self::sanitizeArray($dataArray);

        $nationalIdentification = NationalIdentification::findById($id);
        if (!isset($nationalIdentification->id)) {
            $nationalIdentification = new NationalIdentification([
                'document_name' => $document_name,
                'document_value' => $document_value,
                'personas_id' => $personas_id
            ]);
        } else {
            $nationalIdentification->document_name = $document_name;
            $nationalIdentification->document_value = $document_value;
            $nationalIdentification->personas_id = $personas_id;
        }

        return [
            'success' => true,
            'errors' => [],
            'nationalIdentification' => $nationalIdentification
        ];
    }

    public static function sanitizeArray(array $dataArray): array
    {
        $raw = [];
        if (array_key_exists('id', $dataArray) && intval($dataArray['id']) !== 0) {
            $raw[] = intval($dataArray['id']);
        } else {
            $raw[] = null;
        }

        $raw[] = htmlentities($dataArray['document_name'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = htmlentities($dataArray['document_value'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = intval($dataArray['personas_id']);

        return $raw;
    }

    public static function getEmptRaw(): array
    {
        return [
            'document_name' => '',
            'document_value' => '',
            'personas_id' => 0
        ];
    }
}
