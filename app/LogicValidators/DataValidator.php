<?php

namespace App\LogicValidators;

use Illuminate\Contracts\Validation\Factory as Validator;
use \Exception;

class DataValidator extends Exception
{

    protected $validator;
    private $messages = array(
        'end_date.after_or_equal' => 'The end date must be greater than or equal to start date!',
    );

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $params
     * @param array $rules
     * @param array $messages
     *
     * @return null
     */
    protected function validate(array $params, array $rules)
    {
        $validator = $this->validator->make(
            $params, $rules, $this->messages
        );

        if ($validator->fails()) {
            throw new Exception(
                $validator->messages()
            );
        }
    }

    /**
     * @param array $params
     *
     * @return null
     */
    public function validateDate(array $params)
    {
        $this->validate($params, [
            'date' => 'required|date_format:Y-m-d',
        ]);
    }

    /**
     * @param array $params
     *
     * @return null
     */
    public function validatehotelRoomsDateRangePriceAndType(array $params)
    {
        $this->validate(
            $params, 
            [
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
                'room_type_id' => 'required|exists:hotels_room_type,id',
                'slug' => 'required|exists:hotels,slug',
                'available_rooms' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:1',
                'filtered_days' => 'string',
            ]);
    }

     /**
     * @param array $params
     *
     * @return null
     */
    public function validateUpdate(array $params)
    {
        $this->validate($params, [
            'pk' => 'required|exists:hotels_room_details,id',
            'name' => 'required|in:price,available_rooms',
            'value' => 'required|numeric|min:0',
        ]);
    }
}
