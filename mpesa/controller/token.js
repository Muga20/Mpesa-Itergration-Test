const axios = require("axios");

const createToken = async (req, res, next) => {
  const secret = "";
  const consumer = "";
  const auth = new Buffer.from(`${consumer}:${secret}`).toString("base64");

  await axios.get( "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials",{
        headers: {
          authorization: `Basic ${auth}`,
        },
      }
    )
    .then((data) => {
      token = data.data.access_token;
      console.log(data.data);
      next();
    })
    .catch((err) => {
      console.log(err);
      res.status(400).json(err.message);
    });
};

const stkPush = async (req, res) => { 
  const shortCode = 174379;
  const phone = req.body.phone.substring(1);
  
  const amount = req.body.amount;

  const passkey ="";
  const url = "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest";


  const date = new Date();
  const timestamp =
    date.getFullYear() +
    ("0" + (date.getMonth() + 1)).slice(-2) +
    ("0" + date.getDate()).slice(-2) +
    ("0" + date.getHours()).slice(-2) +
    ("0" + date.getMinutes()).slice(-2) +
    ("0" + date.getSeconds()).slice(-2);
  const password = new Buffer.from(shortCode + passkey + timestamp).toString( "base64");

  const data = {
    BusinessShortCode: shortCode,
    Password: password,
    Timestamp: timestamp,
    TransactionType: "CustomerPayBillOnline",
    Amount: amount,
    PartyA: `254${phone}`,
    PartyB: 174379,
    PhoneNumber: `254${phone}`,
    CallBackURL: "https://fe8a-197-248-136-59.ngrok-free.app",
    AccountReference: "Mpesa Test",
    TransactionDesc: "Testing stk push",
  };

  await axios.post(url, data, {
    headers: {
      authorization: `Bearer ${token}`,
    },
  })
  .then((data) => {
    console.log(data);
    res.status(200).json(data.data);
  })
  .catch((err) => {
    console.log(err);
    res.status(400).json(err.message);
  });

}

module.exports = { createToken,stkPush };
