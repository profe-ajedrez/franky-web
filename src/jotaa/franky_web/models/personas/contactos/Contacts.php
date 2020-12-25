<?php declare(strict_types = 1);

namespace jotaa\franky_web\models\personas\contactos;

use jotaa\franky_web\models\FrankyRecord;
use jotaa\franky_web\interfaces\model_interfaces\FrankyModelInterface;

/**
 * Contact
 *
 * Tiene la responsabilidad de manejar los datos de contacto de una persona
 * siendo estos, correos y telefonos
 *
 * $contactResult = Contact::fromArray($_POST);
 * if ($contactResult['success']) {
 *    $contact = $contactResult['contact'];
 * }
 *
 *
 *
 * @author Andrés Reyes
 * @version 1.0
 * @copyright 2020 Jota A Diseño y Desarrollo
 * @package solylago
 */
final class Contacts extends FrankyRecord implements FrankyModelInterface
{

    public static function fromArray(array $dataArray) : array
    {
        $v = new \Valitron\Validator($dataArray);
        $v->rule('optional', 'id')
          ->rule('integer', 'id')
          ->rule('required', 'contact_type')
          ->rule('integer', 'contact_type')
          ->rule('required', 'contact_data')
          ->rule('required', 'personas_id')
          ->rule('integer', 'personas_id');

        if (!$v->validate()) {
            return [
                'success' => false,
                'errors'  => $v->errors(),
                'contact' => null
            ];
        }

        list($id, $contact_type, $contact_data, $personas_id) = self::sanitizeArray($dataArray);

        $contact = Contacts::findById($id);
        if (!(bool) $contact) {
            $contact = new Contacts([
                'id' => $id,
                'contact_type' => $contact_type,
                'contact_data' => $contact_data,
                'personas_id' => $personas_id
            ]);
        } else {
            $contact->contact_type = $contact_type;
            $contact->contact_data = $contact_data;
            $contact->personas_id = $personas_id;
        }


        return [
            'success' => true,
            'errors'  => [],
            'contact' => $contact
        ];
    }

    public static function sanitizeArray(array $dataArray) : array
    {
        $raw = [];
        if (array_key_exists('id', $dataArray) && intval($dataArray['id']) !== 0) {
            $raw[] = intval($dataArray['id']);
        } else {
            $raw[] = null;
        }

        $raw[] = intval($dataArray['contact_type']);
        $raw[] = htmlentities($dataArray['contact_data'], ENT_QUOTES, 'UTF-8', false);
        $raw[] = intval($dataArray['personas_id']);

        return $raw;
    }

    public static function getEmptRaw() : array
    {
        return [
            'contact_type' => 0,
            'contact_data' => '',
            'personas_id'  => 0,
        ];
    }
}
