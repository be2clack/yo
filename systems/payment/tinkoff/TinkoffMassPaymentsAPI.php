<?php

class TinkoffMassPaymentsAPI
{
    public $apiUrl;
    public $terminalKey;
    public $serialNumber;
    public $certificateKey;
    public $certificatePath;

    public function __construct()
    {
        global $config;

        $paramPayment = paymentParams('tinkoff');

        $this->apiUrl = 'https://securepay.tinkoff.ru/e2c/v2/';
        $this->terminalKey = $paramPayment["terminal_key"];
        $this->serialNumber = $paramPayment["serial_number"];
        $this->certificateKey = $paramPayment["certificate_key"];
        $this->certificatePath = $config['basePath'].'/systems/payment/tinkoff/';
    }

    public function sendRequest($api_url, $args)
    {

        if (is_array($args)) {
            $args = json_encode($args);
        }

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $api_url);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));

            $out = curl_exec($curl);
            $json = json_decode($out, true);

            curl_close($curl);

            return $json;

        } else {
            throw new HttpException('Can not create connection to ' . $api_url . ' with args ' . $args, 404);
        }
    }

    public function toSha256($args)
    {
        $token = '';
        ksort($args);

        foreach ($args as $key => $arg) {
            $token .= $arg;
        }

        $token = hash('sha256', $token);

        return $token;
    }

    public function buildSignature($params=[]){

        // Вычисляем sha256
        $sha256 = $this->toSha256($params);

        // Конвертируем sha256 в бинарь
        $bin = pack("H*" , $sha256);

        // Сохраняем бинарь
        $tempBinName = 'bin_'.md5(uniqid()).'.dat';
        file_put_contents($this->certificatePath.$tempBinName, $bin);

        // Вычисляем SignatureValue
        $tempSignName = 'sign_'.md5(uniqid()).'.dat.sign';

        $tempCertificateName = 'certificat_'.md5(uniqid()).'.key';

        file_put_contents($this->certificatePath.$tempCertificateName, $this->certificateKey);

        exec('openssl dgst -sign '.$this->certificatePath.$tempCertificateName.' -keyform PEM -sha256 -out '.$this->certificatePath.$tempSignName.' -binary '.$this->certificatePath.$tempBinName);

        $signatureValue = file_get_contents($this->certificatePath.$tempSignName);

        unlink($this->certificatePath.$tempBinName);
        unlink($this->certificatePath.$tempSignName);
        unlink($this->certificatePath.$tempCertificateName);

        return [
            'DigestValue' => base64_encode($bin),
            'SignatureValue' => base64_encode($signatureValue),
            'X509SerialNumber' => $this->serialNumber,
        ];

    }

    public function addCard($userId, $cardId){

        $params = [
            'TerminalKey' => $this->terminalKey,
            'CustomerKey'  => 'client_'.$userId,
        ];

        $output = array_merge($params, $this->buildSignature($params));

        if($cardId){

            $result = $this->sendRequest($this->apiUrl.'AddCard', $output);

            if($result['Success'] == true){
                return ['status' => true, 'link' => $result['PaymentURL']];
            }else{
                return ['status' => false, 'answer' => $result['Message'].$result['Details']];
            }

        }

        $result = $this->sendRequest($this->apiUrl.'AddCustomer', $output);

        if($result['Success'] == true){

            $result = [];

            $result = $this->sendRequest($this->apiUrl.'AddCard', $output);
            
            if($result['Success'] == true){
                return ['status' => true, 'link' => $result['PaymentURL']];
            }else{
                return ['status' => false, 'answer' => $result['Message'].$result['Details']];
            }

        }else{
            return ['status' => false, 'answer' => $result['Message'].$result['Details']];
        }

    }

    public function deleteCard($userId, $cardId){

        $params = [
            'TerminalKey' => $this->terminalKey,
            'CardId' => $cardId,
            'CustomerKey'  => 'client_'.$userId,
        ];

        $output = array_merge($params, $this->buildSignature($params));

        $result = $this->sendRequest($this->apiUrl.'RemoveCard', $output);  
        
        if($result['Success'] == true){
            return ['status' => true];
        }else{
            return ['status' => false, 'answer' => $result['Message'].$result['Details']];
        }

    }

    public function payment($orderId, $cardId, $amount){

        $params = [
           'TerminalKey' => $this->terminalKey,
           'OrderId' => $orderId,
           'CardId' => $cardId,
           'Amount' => $amount * 100,
        ];

        $output = array_merge($params, $this->buildSignature($params));

        $result = $this->sendRequest($this->apiUrl.'Init', $output);

        if ($result['Success'] == "true")
        {

          $params = [];
          $output = [];

          $params = [
             'PaymentId' => $result['PaymentId'],
          ];

          $output = array_merge($params, $this->buildSignature($params));
 
          $result = $this->sendRequest($this->apiUrl.'Payment', $output);  

          if($result['Success'] == "true"){

             return ['status' => true]; 

         }else{
            return ['status' => false, 'answer' => $result['Message'].$result['Details']];
         }

        }
        else
        {
            return ['status' => false, 'answer' => $result['Message'].$result['Details']];
        }

    }


}