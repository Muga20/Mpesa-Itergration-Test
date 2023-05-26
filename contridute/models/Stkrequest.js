import Sequelize from "sequelize";
import db from "../config/database.js";
import { DataTypes } from 'sequelize';

const Stkrequest = db.define(
  'Stkrequest',
  {
    phone: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    amount: {
      type: DataTypes.INTEGER,
      allowNull: false,
    },
    reference: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    description: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    MerchantRequestID: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    CheckoutRequestID: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    status: {
      type: DataTypes.STRING,
      allowNull: false,
    },
    TransactionDate: {
      type: DataTypes.DATE,
    },
    MpesaReceiptNumber: {
      type: DataTypes.STRING,
    },
    ResultDesc: {
      type: DataTypes.STRING,
    },
  },
  {
    tableName: 'stkrequests', // Specify the table name in your MySQL database
    timestamps: false, // Disable timestamps
  }
);

// Synchronize the model with the database
(async () => {
  try {
    await Stkrequest.sync();
    console.log('Stkrequest model synchronized with the database');
  } catch (error) {
    console.error('Failed to synchronize Stkrequest model with the database:', error);
  }
})();

export default Stkrequest;
