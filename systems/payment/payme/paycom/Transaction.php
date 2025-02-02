<?php

class Transaction
{
    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 43200000;

    const STATE_CREATED                  = 1;
    const STATE_COMPLETED                = 2;
    const STATE_CANCELLED                = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND         = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED            = 3;
    const REASON_CANCELLED_BY_TIMEOUT        = 4;
    const REASON_FUND_RETURNED               = 5;
    const REASON_UNKNOWN                     = 10;

    /** @var string Paycom transaction id. */
    public $paycom_transaction_id;

    /** @var int Paycom transaction time as is without change. */
    public $paycom_time;

    /** @var string Paycom transaction time as date and time string. */
    public $paycom_time_datetime;

    /** @var int Transaction id in the merchant's system. */
    public $id;

    /** @var string Transaction create date and time in the merchant's system. */
    public $create_time;
    public $create_time2;
    /** @var string Transaction perform date and time in the merchant's system. */
    public $perform_time;

    /** @var string Transaction cancel date and time in the merchant's system. */
    public $cancel_time;

    /** @var int Transaction state. */
    public $state;

    /** @var int Transaction cancelling reason. */
    public $reason;

    /** @var int Amount value in coins, this is service or product price. */
    public $amount;

    /** @var string Pay receivers. Null - owner is the only receiver. */
    public $receivers;

    // additional fields:
    // - to identify order or product, for example, code of the order
    // - to identify client, for example, account id or phone number

    /** @var string Code to identify the order or service for pay. */
    public $order_id;

    /**
     * Saves current transaction instance in a data store.
     * @return bool true - on success
     */
    public function save()
    {
        // todo: Implement creating/updating transaction into data store
        // todo: Populate $id property with newly created transaction id

        // Example implementation

        if (!$this->id) {

            // Create a new transaction

            $this->create_time = Format::timestamp2datetime(Format::timestamp());

            $insert = smart_insert('uni_payme_transactions', [
                'paycom_transaction_id'    => $this->paycom_transaction_id,
                'paycom_time'    => $this->paycom_time,
                'paycom_time_datetime' => $this->paycom_time_datetime,
                'create_time'    => $this->create_time,
                'create_time2'    => $this->create_time2,
                'amount'        => 1 * $this->amount,
                'state'         => $this->state,
                'receivers'     => $this->receivers ? json_encode($this->receivers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null,
                'order_id'       => 1 * $this->order_id,
            ]);

            if ($insert) {
                // set the newly inserted transaction id
                $this->id = $insert;
            }
        } else {

            // Update an existing transaction by id

            $perform_time = $this->perform_time ? $this->perform_time : null;
            $cancel_time  = $this->cancel_time ? $this->cancel_time : null;
            $reason       = $this->reason ? 1 * $this->reason : null;

            if ($this->amount) {
                update('update uni_payme_transactions set perform_time=?, cancel_time=?, state=?, reason=?, amount=? where paycom_transaction_id=?', [$perform_time, $cancel_time, $this->state, $reason, $this->amount,$this->paycom_transaction_id]);
            }else{
                update('update uni_payme_transactions set perform_time=?, cancel_time=?, state=?, reason=? where paycom_transaction_id=?', [$perform_time, $cancel_time, $this->state, $reason,$this->paycom_transaction_id]);
            }
        }

        return true;
    }

    /**
     * Cancels transaction with the specified reason.
     * @param int $reason cancelling reason.
     * @return void
     */
    public function cancel($reason)
    {
        // todo: Implement transaction cancelling on data store

        // todo: Populate $cancel_time with value
        $this->cancel_time = Format::timestamp2datetime(Format::timestamp());

        // todo: Change $state to cancelled (-1 or -2) according to the current state

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        // set reason
        $this->reason = $reason;

        // todo: Update transaction on data store
        $this->save();
    }

    /**
     * Determines whether current transaction is expired or not.
     * @return bool true - if current instance of the transaction is expired, false - otherwise.
     */
    public function isExpired()
    {
        // todo: Implement transaction expiration check
        // for example, if transaction is active and passed TIMEOUT milliseconds after its creation, then it is expired
        return false;
        //return $this->state == self::STATE_CREATED && abs(Format::datetime2timestamp($this->create_time) - Format::timestamp(true)) > self::TIMEOUT;
    }

    /**
     * Find transaction by given parameters.
     * @param mixed $params parameters
     * @return Transaction|Transaction[]
     * @throws PaycomException invalid parameters specified
     */
    public function find($params)
    {

        // todo: Implement searching transaction by id, populate current instance with data and return it
        if (isset($params['id'])) {
            $row = findOne('uni_payme_transactions', 'paycom_transaction_id=?', [$params['id']]);
        } elseif (isset($params['account'], $params['account']['id_order'])) {
            // todo: Implement searching transactions by given parameters and return the list of transactions
            // search by order id active or completed transaction
            $row = findOne('uni_payme_transactions', 'state IN (1, 2) AND order_id=?', [$params['account']['id_order']]);
        } else {
            throw new PaycomException(
                $params['request_id'],
                'Parameter to find a transaction is not specified.',
                PaycomException::ERROR_INTERNAL_SYSTEM
            );
        }

        // if SQL operation succeeded, then try to populate instance properties with values
        if ($row) {

            $this->id                    = $row['id'];
            $this->paycom_transaction_id = $row['paycom_transaction_id'];
            $this->paycom_time           = $row['paycom_time'];
            $this->paycom_time_datetime  = $row['paycom_time_datetime'];
            $this->create_time           = $row['create_time'];
            $this->create_time2           = $row['create_time2'];
            $this->perform_time          = $row['perform_time'];
            $this->cancel_time           = $row['cancel_time'];
            $this->state                 = $row['state'];
            $this->reason                = $row['reason'] ? $row['reason'] : null;
            $this->amount                = $row['amount'];
            $this->receivers             = $row['receivers'] ? json_decode($row['receivers'], true) : null;
            $this->order_id              = $row['order_id'];

            return $this;

        }

        // transaction not found, return null
        return null;

        // Possible features:
        // Search transaction by product/order id that specified in $params
        // Search transactions for a given period of time that specified in $params
    }

    /**
     * Gets list of transactions for the given period including period boundaries.
     * @param int $from_date start of the period in timestamp.
     * @param int $to_date end of the period in timestamp.
     * @return array list of found transactions converted into report format for send as a response.
     */
    public function report($from_date, $to_date)
    {
        $from_date = Format::timestamp2datetime($from_date);
        $to_date   = Format::timestamp2datetime($to_date);

        // container to hold rows/document from data store
        $rows = [];

        // todo: Retrieve transactions for the specified period from data store

        // Example implementation

        $rows = getAll('select * from uni_payme_transactions where paycom_time_datetime BETWEEN '.$from_date.' AND '.$to_date.' ORDER BY paycom_time_datetime', []);

        // assume, here we have $rows variable that is populated with transactions from data store
        // normalize data for response
        $result = [];
        foreach ($rows as $row) { 
            $result[] = [
                'id'           => $row['paycom_transaction_id'], // paycom transaction id
                'time'         => 1 * $row['paycom_time'], // paycom transaction timestamp as is
                'amount'       => 1 * $row['amount'],
                'account'      => [
                    'order_id' => 1 * $row['order_id'], // account parameters to identify client/order/service
                    // ... additional parameters may be listed here, which are belongs to the account
                ],
                'create_time'  => Format::datetime2timestamp($row['create_time']),
                'perform_time' => Format::datetime2timestamp($row['perform_time']),
                'cancel_time'  => Format::datetime2timestamp($row['cancel_time']),
                'transaction'  => 1 * $row['id'],
                'state'        => 1 * $row['state'],
                'reason'       => isset($row['reason']) ? 1 * $row['reason'] : null,
                'receivers'    => isset($row['receivers']) ? json_decode($row['receivers'], true) : null,
            ];
        }

        return $result;

    }
}
