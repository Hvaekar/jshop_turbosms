<?php
defined('_JEXEC') or die('Restricted access');

class plgjshoppingcheckoutSms_status_change extends JPlugin
{

    private $_params;

    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);

        $addon = \JSFactory::getTable('addon', 'jshop');
        $addon->loadAlias('sms_status_change');
        $this->_params = (object)$addon->getParams();
    }

    public function onAfterDisplayCheckoutFinish($text, &$order, $pm_method)
    {
        if (!$this->_params->active || !$this->_params->ch_finish || !$this->_params->turbosms_login || !$this->_params->turbosms_password || !$order->mobil_phone) return;
        $vendorinfo = $order->getVendorInfo();

        $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');

        $message = $this->getMessage($order, $vendorinfo);
        if (!$message) return;

        $auth = array(
            'login' => $this->_params->turbosms_login,
            'password' => $this->_params->turbosms_password
        );

        $result = $client->Auth($auth);

        $sms = [
            'sender' => $this->_params->turbosms_sender,
            'destination' => $order->mobil_phone,
            'text' => trim($message)
        ];
        $result = $client->SendSMS($sms);
    }

    private function getMessage($order, $vendorinfo)
    {
        $lang = $this->_params->lang ? $this->_params->lang : $order->getLang();

        $orderstatus = JSFactory::getTable('orderStatus', 'jshop');
        $orderstatus->load($order->order_status);

        $message = '';
        if (!$orderstatus->{'send_sms'}) return $message;

        $message = $orderstatus->{'sms_text_' . $lang};
        if (!$message) return $message;

        $message = str_replace('{first_name}', $order->f_name, $message);
        $message = str_replace('{last_name}', $order->l_name, $message);
        $message = str_replace('{order_number}', $order->order_number, $message);
        $message = str_replace('{order_number_without_zeros}', ltrim($order->order_number, '0'), $message);
        $message = str_replace('{order_status}', $orderstatus->{'name_' . $lang}, $message);
        $message = str_replace('{comment}', '', $message);
        $message = str_replace('{company}', $vendorinfo->company_name, $message);
        $message = str_replace('{address}', $vendorinfo->adress, $message);
        $message = str_replace('{zip}', $vendorinfo->zip, $message);
        $message = str_replace('{city}', $vendorinfo->city, $message);
        $message = str_replace('{country}', $vendorinfo->country, $message);
        $message = str_replace('{phone}', $vendorinfo->phone, $message);
        $message = str_replace('{fax}', $vendorinfo->fax, $message);
        $message = str_replace('{tracking_number}', $order->tracking_number ?? '', $message);
        $shipping = $order->getShipping();
        $payment = $order->getPayment();
        if (isset($shipping->tracking_url)) {
            $tracking_number_url = str_replace('{number}', $order->tracking_number, $shipping->tracking_url);
        } else {
            $tracking_number_url = '';
        }
        $message = str_replace('{tracking_number_url}', $tracking_number_url, $message);
        $message = str_replace('{shipping}', $shipping->getName(), $message);
        $message = str_replace('{payment}', $payment->getName(), $message);

        $message = str_replace('{order_total}', $order->order_total, $message);
        $message = str_replace('{shipping_price}', $order->order_shipping, $message);
        $message = str_replace('{payment_price}', $order->order_payment, $message);
        $message = str_replace('{order_tax}', $order->order_tax, $message);
        $message = str_replace('{currency_code}', $order->currency_code, $message);

        return $message;
    }

}