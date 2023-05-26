import express from 'express';
import {
  initiateStkToken,
  initiateStkPush,
  stkPushCallback,
} from '../controllers/PaymentController.js';

const MpesaRoutes = express.Router();

MpesaRoutes.get('/initiate' , initiateStkToken );
MpesaRoutes.get('/initiateStkPush', initiateStkPush);
MpesaRoutes.post('/stkCallback', stkPushCallback);


export default MpesaRoutes;
