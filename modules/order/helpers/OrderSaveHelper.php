<?php

namespace app\modules\order\helpers;

use app\modules\order\models\Order;
use app\modules\order\models\Booking;
use app\modules\order\models\CashRegister;
use app\modules\order\models\Invoice;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use app\modules\options\models\OptionsTable;

class OrderSaveHelper
{
    /**
     * Array of order attributes sent by controller
     *
     * @var array
     */
    private $attributes;

    /**
     * Order ID
     *
     * @var int
     */
    public $orderId;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
        $this->proccess();
    }

    /**
     * Creates instance of this model
     *
     * @return OrderSaveHelper
     */
    public static function factory(array $attributes): OrderSaveHelper
    {
        return new static($attributes);
    }

    /**
     * Processes all order, invoice and cash register data
     *
     * @return void
     */
    private function proccess()
    {
        $attributes = $this->attributes;

        //new order record
        if ($attributes['id'] == 0) {
            $orderModel = new Order;
        } else {
            $orderModel = Order::findOne($attributes['id']);
        }

        if (empty($orderModel)) throw new NotFoundHttpException;

        //handle order atttributes
        $orderModel = $this->handleBasicAttributes($orderModel);

        //if something went wrong, terminate processing
        if (!$orderModel->save(true)) {
            throw new UnprocessableEntityHttpException;
        }

        $this->orderId = $orderModel->id;

        //pass order ID to source booking
        $bookingModel = Booking::find()->where(['id' => $attributes['booking_id']])->one();
        $bookingModel->order_id = $orderModel->id;
        $bookingModel->status = Booking::STATUS_SOLVED;
        $bookingModel->save(false);

        //if rent invoice creation, handle it
        if ($attributes['create_invoice'] == 1) {
            $orderModel->invoice_id = $this->handleInvoice('rent');
        }

        //if not invoice creation, delete old one
        if ($attributes['create_invoice'] == 0) {
            $orderModel->invoice_id = 0;
            $this->deleteInvoice('rent');
        }

        //if bail registed invoice creation, handle it
        if ($attributes['create_bail_invoice'] == 1) {
            $orderModel->bail_invoice_id = $this->handleInvoice('bail');
        }

        //if not invoice creation, delete old one
        if ($attributes['create_bail_invoice'] == 0) {
            $orderModel->bail_invoice_id = 0;
            $this->deleteInvoice('bail');
        }

        //if rent cash register creation, handle it
        if ($attributes['create_cash_register'] == 1) {
            $orderModel->cash_register_id = $this->handleCashRegister('rent');
        }

        //if not cash register creation, delete old one
        if ($attributes['create_cash_register'] == 0) {
            $orderModel->cash_register_id = 0;
            $this->deleteCashRegister('rent');
        }

        //if bail cash register creation, handle it
        if ($attributes['create_bail_cash_register'] == 1) {
            $orderModel->bail_cash_register_id = $this->handleCashRegister('bail');
        }

        //if not cash register creation, delete old one
        if ($attributes['create_bail_cash_register'] == 0) {
            $orderModel->bail_cash_register_id = 0;
            $this->deleteCashRegister('bail');
        }

        //mark numbering base as used, so next record numbering will be derived from previous invoice/cash register
        if ($attributes['create_invoice'] == 1 || $attributes['create_bail_invoice'] == 1) {
            $option = OptionsTable::find()->where(['name' => 'invoice_numbering_used'])->one();
            $option->value = 1;
            $option->save(false);
        }

        if ($attributes['create_cash_register'] == 1 || $attributes['create_bail_cash_register'] == 1) {
            $option = OptionsTable::find()->where(['name' => 'cash_register_numbering_used'])->one();
            $option->value = 1;
            $option->save(false);
        }

        $orderModel->save(false);

        return $orderModel->id;
    }

    /**
     * Processes order attributes
     *
     * @param Order $orderModel
     * @return Order
     */
    private function handleBasicAttributes(Order $orderModel): Order
    {
        $attributes = $this->attributes;

        $orderModel->booking_id = $attributes['booking_id'];

        $orderModel->create_invoice = $attributes['create_invoice'];
        $orderModel->create_bail_invoice = $attributes['create_bail_invoice'];

        $orderModel->create_cash_register = $attributes['create_cash_register'];
        $orderModel->create_bail_cash_register = $attributes['create_bail_cash_register'];

        $orderModel->operator_id = $attributes['operator_id'];

        $orderModel->is_company = $attributes['is_company'];

        $orderModel->name = $attributes['name'];
        $orderModel->email = $attributes['email'];
        $orderModel->phone = $attributes['phone'];
        $orderModel->birth_number = $attributes['birth_number'];
        $orderModel->identity_card_number = $attributes['identity_card_number'];

        if ($attributes['is_company'] == 0) {
            $orderModel->birth_date = $attributes['birth_date'];
            $orderModel->street = $attributes['street'];
            $orderModel->zip = $attributes['zip'];
            $orderModel->town = $attributes['town'];
        }

        if ($attributes['is_company'] == 1) {
            $orderModel->permanent_residence = $attributes['permanent_residence'];
            $orderModel->company_name = $attributes['company_name'];
            $orderModel->company_street = $attributes['company_street'];
            $orderModel->company_zip = $attributes['company_zip'];
            $orderModel->company_town = $attributes['company_town'];
            $orderModel->ico = $attributes['ico'];
            $orderModel->dic = $attributes['dic'];
        }

        $orderModel->different_bill_address = $attributes['different_bill_address'];

        if ($attributes['different_bill_address'] == 1) {
            $orderModel->billing_name = $attributes['billing_name'];
            $orderModel->billing_street = $attributes['billing_street'];
            $orderModel->billing_zip = $attributes['billing_zip'];
            $orderModel->billing_town = $attributes['billing_town'];
            $orderModel->billing_ico = $attributes['billing_ico'];
            $orderModel->billing_dic = $attributes['billing_dic'];
        }

        $orderModel->car_id = $attributes['car_id'];
        $orderModel->lease_date_from = $attributes['lease_date_from'];
        $orderModel->lease_date_to = $attributes['lease_date_to'];
        $orderModel->mileage = $attributes['mileage'];
        $orderModel->price = $attributes['price'];
        $orderModel->bail_value = $attributes['bail_value'];
        $orderModel->use_rider = $attributes['use_rider'];
        $orderModel->contractual_fine = $attributes['contractual_fine'];

        $orderModel->vehicle_handover_date = $attributes['vehicle_handover_date'];
        $orderModel->vehicle_handover_place = $attributes['vehicle_handover_place'];
        $orderModel->vehicle_return_date = $attributes['vehicle_return_date'];
        $orderModel->vehicle_return_place = $attributes['vehicle_return_place'];
        $orderModel->vehicle_handover_time = $attributes['vehicle_handover_time'];
        $orderModel->vehicle_return_time = $attributes['vehicle_return_time'];

        if ($attributes['id'] == 0) {
            $orderModel->status = Order::STATUS_IN_PROGRESS;
        } else {
            $orderModel->status = $attributes['status'];
        }

        $orderModel->note = $attributes['note'];

        return $orderModel;
    }

    /**
     * Process invoices creation/update
     *
     * @param string $type
     * @return void
     */
    private function handleInvoice(string $type)
    {
        $attributes = $this->attributes;

        $invoiceModel = Invoice::findSingle($this->orderId, $type);

        if (empty($invoiceModel)) $invoiceModel = new Invoice;

        $invoiceModel->related_order_id = $this->orderId;
        $invoiceModel->related_type = $type;
        $invoiceModel->payment_method = $attributes['invoice_' . $type . '_payment_method'];
        $invoiceModel->base_prefix = $attributes['invoice_base_prefix'];
        $invoiceModel->base_number = $attributes['invoice_base_number'];
        $invoiceModel->actual_prefix = $attributes['invoice_' . $type . '_actual_prefix'];
        $invoiceModel->actual_number = $attributes['invoice_' . $type . '_actual_number'];
        $invoiceModel->full_number = $attributes['invoice_' . $type . '_full_number'];
        $invoiceModel->issue_date = $attributes['invoice_' . $type . '_issue_date'];
        $invoiceModel->supply_date = $attributes['invoice_' . $type . '_supply_date'];
        $invoiceModel->due_at = $attributes['invoice_' . $type . '_due_at'];
        $invoiceModel->variable_symbol = $attributes['invoice_' . $type . '_variable_symbol'];

        if ($invoiceModel->save(true)) {
            return $invoiceModel->id;
        };

        throw new UnprocessableEntityHttpException;
    }

    /**
     * Process cash registers creation/update
     *
     * @param string $type
     * @return void
     */
    private function handleCashRegister(string $type)
    {
        $attributes = $this->attributes;

        $cashRegisterModel = CashRegister::findSingle($this->orderId, $type);

        if (empty($cashRegisterModel)) $cashRegisterModel = new CashRegister;

        $cashRegisterModel->related_order_id = $this->orderId;
        $cashRegisterModel->related_type = $type;
        $cashRegisterModel->base_prefix = $attributes['cash_register_base_prefix'];
        $cashRegisterModel->base_number = $attributes['cash_register_base_number'];
        $cashRegisterModel->actual_prefix = $attributes['cash_register_' . $type . '_actual_prefix'];
        $cashRegisterModel->actual_number = $attributes['cash_register_' . $type . '_actual_number'];
        $cashRegisterModel->full_number = $attributes['cash_register_' . $type . '_full_number'];
        $cashRegisterModel->payment_date = $attributes['cash_register_' . $type . '_payment_date'];

        if ($cashRegisterModel->save(true)) {
            return $cashRegisterModel->id;
        };

        throw new UnprocessableEntityHttpException;
    }

    /**
     * Delete unused invoices
     *
     * @param string $type
     * @return void
     */
    private function deleteInvoice(string $type)
    {
        $attributes = $this->attributes;

        $model = Invoice::find()
            ->where(['related_order_id' => $attributes['id']])
            ->andWhere(['related_type' => $type])
            ->one();

        if ($model) $model->delete();
    }

    /**
     * Delete unused cash registers
     *
     * @param string $type
     * @return void
     */
    private function deleteCashRegister(string $type)
    {
        $attributes = $this->attributes;

        $model = CashRegister::find()
            ->where(['related_order_id' => $attributes['id']])
            ->andWhere(['related_type' => $type])
            ->one();

        if ($model) $model->delete();
    }
}
