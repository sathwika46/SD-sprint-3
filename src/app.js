
import express from "express";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";
import carsRouter from "./routes/cars.js";
import customersRouter from "./routes/customers.js";
import bookingsRouter from "./routes/bookings.js";

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;

// ✅ Serve static CSS and other public files
app.use(express.static(path.join(__dirname, "public")));

app.set("views", path.join(__dirname, "views"));
app.set("view engine", "pug");

app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// ✅ Routes
app.use("/cars", carsRouter);
app.use("/customers", customersRouter);
app.use("/", bookingsRouter);

app.listen(PORT, () => {
  console.log(`🚗 Server running on http://localhost:${PORT}`);
});
