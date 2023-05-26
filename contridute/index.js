import express from 'express';
import cors from 'cors';
import MpesaRoutes from './routes/mpesa.js';

const app = express();
const port = 5000;


app.get('/', (req, res) => {
    // Handle the request here
    res.send('Hello,This is the root route.');
  });

  
app.use(express.json());
app.use(cors());
app.use('/mpesa', MpesaRoutes);

// Route for the root URL ("/")


app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
