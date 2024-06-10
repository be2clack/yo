<?php

class Order
{
    /** Order is available for sell, anyone can buy it. */
    const STATE_AVAILABLE = 0;

    /** Pay in progress, order must not be changed. */
    const STATE_WAITING_PAY = 1;

    /** Order completed and not available for sell. */
    const STATE_PAY_ACCEPTED = 2;

    /** Order is cancelled. */
    const STATE_CANCELLED = 3;

    public $request_id;
    public $params;

    // todo: Adjust Order specific fields for your needs

    /**
     * Order ID
     */
    public $id;

    /**
     * IDs of the selected products/services
     */
    public $product_ids;

    /**
     * Total price of the selected products/services
     */
    public $amount;

    /**
     * State of the order
     */
    public $state;

    /**
     * ID of the customer created the order
     */
    public $user_id;

    /**
     * Phone number of the user
     */
    public $phone;

    public function __construct($request_id)
    {
        $this->request_id = $request_id;
    }

    /**
     * Validates amount and account values.
     * @param array $params amount and account parameters to validate.
     * @return bool true - if validation passes
     * @throws PaycomException - if validation fails
     */
    public function validate(array $params)
    {

        if (!is_numeric($params['amount'])) {
            throw new PaycomException(
                $this->request_id,
                'Incorrect amount.',
                PaycomException::ERROR_INVALID_AMOUNT
            );
        }


        if (!isset($params['account']['id_order']) || !$params['account']['id_order']) {
            throw new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'id_order'
            );
        }

        $order = $this->find($params['account']);

        // Check, is order found by specified order_id
        if (!$order || !$order->id) {
            throw new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'id_order'
            );
        }

        if ($this->amount * 100 != $params['amount'] && $this->amount != $params['amount']) {
            throw new PaycomException(
                $this->request_id,
                'Incorrect amount.1',
                PaycomException::ERROR_INVALID_AMOUNT
            );
        }

        if ($this->state != self::STATE_WAITING_PAY) {
            throw new PaycomException(
                $this->request_id,
                'Order state is invalid.',
                PaycomException::ERROR_COULD_NOT_PERFORM
            );
        }

        $this->params = $params;

        return true;
    }

    /**
     * Find order by given parameters.
     * @param mixed $params parameters.
     * @return Order|Order[] found order or array of orders.
     */
    public function find($params)
    {

        if (isset($params['id_order'])) {

            $row = findOne('uni_orders_parameters', 'orders_parameters_id_uniq=?', [$params['id_order']]);

            $paramOrder = json_decode($row['orders_parameters_param'], true);

            if ($row) {


                    $this->id          = $row['orders_parameters_id_uniq'];
                    $this->amount      = $paramOrder['amount'];
                    $this->product_ids = 0;
                    $this->state       = $row['orders_parameters_state'];
                    $this->user_id     = 0;
                    $this->phone       = $paramOrder['phone'];

                    return $this;


            }

        }

        return null;
    }

    /**
     * Change order's state to specified one.
     * @param int $state new state of the order
     * @return void
     */
    public function changeState($state)
    {

        $this->state = $state;
        $this->save();
    }

    /**
     * Check, whether order can be cancelled or not.
     * @return bool true - order is cancellable, otherwise false.
     */
    public function allowCancel()
    {
        return false; 
    }

    /**
     * Saves this order.
     * @throws PaycomException
     */
    public function save()
    {

        if (!$this->id) {

            // If new order, set its state to waiting
            $this->state = self::STATE_WAITING_PAY;

        } else {

            update('update uni_orders_parameters set orders_parameters_state=? where orders_parameters_id_uniq=?', [$this->state,$this->id]);

        }

    }
}