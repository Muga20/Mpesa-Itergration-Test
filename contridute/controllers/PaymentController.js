import axios from 'axios';
import { DateTime } from 'luxon';
import Stkrequest from '../models/Stkrequest.js';





export const initiateStkToken = async (req, res) => {
  try {
    const consumerKey = 'AfpekwX1GS7AFIpwA7HOncXf4lCUW818';
    const consumerSecret = 'A3vtjnKKAvVgMmJJ';
    const url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

    try {
      const response = await axios.get(url, {
        auth: {
          username: consumerKey,
          password: consumerSecret,
        },
      });
      const responseData = response.data;
      const accessToken = responseData.access_token || null;
      res.send(accessToken);
    } catch (error) {
      console.error(error);
      res.send(null);
    }
  } catch (error) {
    console.error(error);
    throw error;
  }
};


export const initiateStkPush = async () => {
  try {
    const accessToken = await initiateStkToken();
    const url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    const PassKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    const BusinessShortCode = '174379';
    const Timestamp = DateTime.now().toFormat('yyyyMMddHHmmss');
    const password = Buffer.from(`${BusinessShortCode}${PassKey}${Timestamp}`).toString('base64');
    const TransactionType = 'CustomerPayBillOnline';
    const Amount = '1';
    const PartyA = '2543051520';
    const PartyB = BusinessShortCode;
    const PhoneNumber = '254743051520';
    const CallbackUrl = 'https://18c3-197-248-136-59.ngrok-free.app/mpesa/stkCallback';
    const AccountReference = 'Donation for goods';
    const TransactionDesc = 'Payment for goods';

    const response = await axios.post(
      url,
      {
        BusinessShortCode,
        Password: password,
        Timestamp,
        TransactionType,
        Amount,
        PartyA,
        PartyB,
        PhoneNumber,
        CallBackURL: CallbackUrl,
        AccountReference,
        TransactionDesc,
      },
      {
        headers: {
          Authorization: `Bearer ${accessToken}`,
        },
      }
    );

    const resData = response.data;

    if (resData.ResponseCode !== '0') {
      throw new Error(resData.errorMessage);
    }

    const { MerchantRequestID, CheckoutRequestID, CustomerMessage } = resData;

    // Save to database
    const payment = new Stkrequest();
    payment.phone = PhoneNumber;
    payment.amount = Amount;
    payment.reference = AccountReference;
    payment.description = TransactionDesc;
    payment.MerchantRequestID = MerchantRequestID;
    payment.CheckoutRequestID = CheckoutRequestID;
    payment.status = 'Requested';
    await payment.save();

    return CustomerMessage;
  } catch (error) {
    console.error(error);
    throw error;
  }
};


export const stkPushCallback = async (req, res) => {
  try {
    const data = req.body;
    const response = JSON.parse(data);
    const ResultCode = response.Body.stkCallback.ResultCode;

    if (ResultCode === '0') {
      const MerchantRequestID = response.Body.stkCallback.MerchantRequestID;
      const CheckoutRequestID = response.Body.stkCallback.CheckoutRequestID;
      const ResultDesc = response.Body.stkCallback.ResultDesc;
      const Amount = response.Body.stkCallback.CallbackMetadata.Item[0].Value;
      const MpesaReceiptNumber = response.Body.stkCallback.CallbackMetadata.Item[1].Value;
      const TransactionDate = response.Body.stkCallback.CallbackMetadata.Item[3].Value;
      const PhoneNumber = response.Body.stkCallback.CallbackMetadata.Item[4].Value;

      const payment = await Stkrequest.findOneAndUpdate(
        { CheckoutRequestID },
        {
          $set: {
            status: 'Paid',
            TransactionDate,
            MpesaReceiptNumber,
            ResultDesc,
          },
        },
        { new: true }
      );

      if (!payment) {
        throw new Error('Payment not found');
      }

      res.sendStatus(200);
    } else {
      const CheckoutRequestID = response.Body.stkCallback.CheckoutRequestID;
      const ResultDesc = response.Body.stkCallback.ResultDesc;

      const payment = await Stkrequest.findOneAndUpdate(
        { CheckoutRequestID },
        {
          $set: {
            status: 'Failed',
            ResultDesc,
          },
        },
        { new: true }
      );

      if (!payment) {
        throw new Error('Payment not found');
      }

      res.sendStatus(200);
    }
  } catch (error) {
    console.error(error);
    res.status(500).send('Internal Server Error');
  }
};
